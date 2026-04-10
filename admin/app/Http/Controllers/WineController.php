<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Producer;
use App\Models\Wine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WineController extends Controller
{
    public function index(): View
    {
        $filters = [
            'name' => trim((string) request('name', '')),
            'producer_id' => request('producer_id'),
            'attribute_value_id' => request('attribute_value_id'),
        ];

        $query = Wine::with(['producer', 'attributeValues.attribute']);

        if ($filters['name'] !== '') {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if ($filters['producer_id']) {
            $query->where('producer_id', $filters['producer_id']);
        }

        if ($filters['attribute_value_id']) {
            $query->whereHas('attributeValues', fn ($attributeQuery) => $attributeQuery->where('attribute_values.id', $filters['attribute_value_id']));
        }

        $query
            ->orderBy('producer_id')
            ->orderBy('sort_order')
            ->orderBy('name');

        return view('wines.index', [
            'wines' => $query->get(),
            'producers' => Producer::orderBy('sort_order')->orderBy('name')->get(),
            'attributeValues' => AttributeValue::with('attribute')
                ->whereHas('attribute', fn ($attributeQuery) => $attributeQuery->whereIn('scope', [Attribute::SCOPE_WINE, Attribute::SCOPE_BOTH]))
                ->get()
                ->sortBy(fn ($value) => ($value->attribute->name ?? '') . ' ' . $value->value)
                ->values(),
            'filters' => $filters,
            'canReorder' => filled($filters['producer_id']) && empty($filters['name']) && empty($filters['attribute_value_id']),
        ]);
    }

    public function create(): View
    {
        return view('wines.form', [
            'wine' => new Wine([
                'show_on_home' => false,
            ]),
            'producers' => Producer::orderBy('name')->get(),
            'attributes' => $this->wineAttributes(),
            'selectedAttributeValues' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeImage($request, 'wines');
        }

        $data['sort_order'] = (Wine::where('producer_id', $data['producer_id'])->max('sort_order') ?? 0) + 1;

        $wine = Wine::create($data);
        $this->syncAttributeValues($wine, $request->input('attribute_values', []));

        return redirect()->route('wines.index')->with('success', 'Vino creado.');
    }

    public function edit(Wine $wine): View
    {
        return view('wines.form', [
            'wine' => $wine->load('attributeValues'),
            'producers' => Producer::orderBy('name')->get(),
            'attributes' => $this->wineAttributes(),
            'selectedAttributeValues' => $wine->attributeValues->pluck('id', 'attribute_id')->all(),
        ]);
    }

    public function update(Request $request, Wine $wine): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['name'], $wine->id);
        $originalProducerId = $wine->producer_id;

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeImage($request, 'wines');
        }

        if ((int) $originalProducerId !== (int) $data['producer_id']) {
            $data['sort_order'] = (Wine::where('producer_id', $data['producer_id'])->max('sort_order') ?? 0) + 1;
        }

        $wine->update($data);
        $this->syncAttributeValues($wine, $request->input('attribute_values', []));

        return redirect()->route('wines.index')->with('success', 'Vino actualizado.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'producer_id' => ['required', 'exists:producers,id'],
            'ordered_ids' => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['integer', 'exists:wines,id'],
        ]);

        $wines = Wine::where('producer_id', $data['producer_id'])
            ->whereIn('id', $data['ordered_ids'])
            ->pluck('id')
            ->all();

        if (count($wines) !== count($data['ordered_ids'])) {
            return back()->withErrors(['ordered_ids' => 'Solo se puede reordenar vinos del productor filtrado.']);
        }

        foreach (array_values($data['ordered_ids']) as $index => $wineId) {
            Wine::whereKey($wineId)->update([
                'sort_order' => $index + 1,
            ]);
        }

        return redirect()->route('wines.index', [
            'producer_id' => $data['producer_id'],
        ])->with('success', 'Orden de vinos actualizado.');
    }

    public function destroy(Wine $wine): RedirectResponse
    {
        $wine->delete();

        return redirect()->route('wines.index')->with('success', 'Vino eliminado.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'producer_id' => ['required', 'exists:producers,id'],
            'name' => ['required', 'string', 'max:255'],
            'show_on_home' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
            'short_description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
        ]) + [
            'show_on_home' => $request->boolean('show_on_home'),
        ];
    }

    private function wineAttributes()
    {
        return Attribute::with('values')
            ->whereIn('scope', [Attribute::SCOPE_WINE, Attribute::SCOPE_BOTH])
            ->orderBy('name')
            ->get();
    }

    private function syncAttributeValues(Wine $wine, array $attributeValues): void
    {
        $wine->attributeValues()->sync(collect($attributeValues)->filter()->values()->all());
    }

    private function storeImage(Request $request, string $folder): string
    {
        $directory = public_path('uploads/' . $folder);
        File::ensureDirectoryExists($directory);

        $file = $request->file('image');
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '-' . now()->format('YmdHis')
            . '.' . $file->getClientOriginalExtension();

        $file->move($directory, $filename);

        return 'uploads/' . $folder . '/' . $filename;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        while (
            Wine::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
