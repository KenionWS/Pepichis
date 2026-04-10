<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AttributeController extends Controller
{
    public function index(): View
    {
        return view('attributes.index', [
            'attributes' => Attribute::with('values')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('attributes.form', [
            'attribute' => new Attribute(),
            'scopeOptions' => Attribute::scopeOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $attribute = Attribute::create($data);
        $this->syncValues($attribute, $request->input('values_text'));

        return redirect()->route('attributes.index')->with('success', 'Característica creada.');
    }

    public function edit(Attribute $attribute): View
    {
        return view('attributes.form', [
            'attribute' => $attribute->load('values'),
            'scopeOptions' => Attribute::scopeOptions(),
        ]);
    }

    public function update(Request $request, Attribute $attribute): RedirectResponse
    {
        $attribute->update($this->validatedData($request, $attribute->id));
        $this->syncValues($attribute, $request->input('values_text'));

        return redirect()->route('attributes.index')->with('success', 'Característica actualizada.');
    }

    public function destroy(Attribute $attribute): RedirectResponse
    {
        $attribute->delete();

        return redirect()->route('attributes.index')->with('success', 'Característica eliminada.');
    }

    private function validatedData(Request $request, ?int $attributeId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:attributes,name,' . $attributeId],
            'scope' => ['required', 'in:' . implode(',', array_keys(Attribute::scopeOptions()))],
        ]);
    }

    private function syncValues(Attribute $attribute, ?string $valuesText): void
    {
        $desiredValues = $this->parseValues($valuesText);
        $existingValues = $attribute->values()->get()->keyBy('value');

        foreach ($desiredValues as $value) {
            if (! $existingValues->has($value)) {
                $attribute->values()->create(['value' => $value]);
            }
        }

        $attribute->values()
            ->whereNotIn('value', $desiredValues->all())
            ->delete();
    }

    private function parseValues(?string $valuesText): Collection
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $valuesText))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->unique()
            ->values();
    }
}
