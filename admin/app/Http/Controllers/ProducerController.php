<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Producer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProducerController extends Controller
{
    public function index(): View
    {
        return view('producers.index', [
            'producers' => Producer::with(['attributeValues.attribute', 'wines'])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('producers.form', [
            'producer' => new Producer(),
            'attributes' => $this->producerAttributes(),
            'selectedAttributeValues' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['name']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeImage($request, 'producers');
        }

        $data['sort_order'] = (Producer::max('sort_order') ?? 0) + 1;

        $producer = Producer::create($data);
        $this->syncAttributeValues($producer, $request->input('attribute_values', []));

        return redirect()->route('producers.index')->with('success', 'Productor creado.');
    }

    public function edit(Producer $producer): View
    {
        return view('producers.form', [
            'producer' => $producer->load('attributeValues'),
            'attributes' => $this->producerAttributes(),
            'selectedAttributeValues' => $producer->attributeValues->pluck('id', 'attribute_id')->all(),
        ]);
    }

    public function update(Request $request, Producer $producer): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['name'], $producer->id);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeImage($request, 'producers');
        }

        $producer->update($data);
        $this->syncAttributeValues($producer, $request->input('attribute_values', []));

        return redirect()->route('producers.index')->with('success', 'Productor actualizado.');
    }

    public function destroy(Producer $producer): RedirectResponse
    {
        $producer->delete();

        return redirect()->route('producers.index')->with('success', 'Productor eliminado.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ordered_ids' => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['integer', 'exists:producers,id'],
        ]);

        foreach (array_values($data['ordered_ids']) as $index => $producerId) {
            Producer::whereKey($producerId)->update([
                'sort_order' => $index + 1,
            ]);
        }

        return redirect()->route('producers.index')->with('success', 'Orden de productores actualizado.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'image' => ['nullable', 'image', 'max:5120'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
        ]);
    }

    private function producerAttributes()
    {
        return Attribute::with('values')
            ->whereIn('scope', [Attribute::SCOPE_PRODUCER, Attribute::SCOPE_BOTH])
            ->orderBy('name')
            ->get();
    }

    private function syncAttributeValues(Producer $producer, array $attributeValues): void
    {
        $producer->attributeValues()->sync(collect($attributeValues)->filter()->values()->all());
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

    private function resolveSlug(?string $inputSlug, string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($inputSlug ?: $name);
        $slug = $baseSlug;
        $counter = 2;

        while (
            Producer::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
