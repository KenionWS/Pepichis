<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    public function index(): View
    {
        return view('menu-items.index', [
            'menuItems' => MenuItem::query()
                ->orderBy('sort_order')
                ->orderBy('label')
                ->get(),
            'typeOptions' => MenuItem::typeOptions(),
        ]);
    }

    public function create(): View
    {
        return view('menu-items.form', [
            'menuItem' => new MenuItem([
                'item_type' => MenuItem::TYPE_ROUTE,
                'item_value' => 'front.about',
                'is_active' => true,
                'open_in_new_tab' => false,
            ]),
            'typeOptions' => MenuItem::typeOptions(),
            'homeSectionOptions' => MenuItem::homeSectionOptions(),
            'routeOptions' => MenuItem::routeOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['sort_order'] = (MenuItem::max('sort_order') ?? 0) + 1;

        MenuItem::create($data);

        return redirect()->route('menu-items.index')->with('success', 'Item del menu creado.');
    }

    public function edit(MenuItem $menuItem): View
    {
        return view('menu-items.form', [
            'menuItem' => $menuItem,
            'typeOptions' => MenuItem::typeOptions(),
            'homeSectionOptions' => MenuItem::homeSectionOptions(),
            'routeOptions' => MenuItem::routeOptions(),
        ]);
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $menuItem->update($this->validatedData($request));

        return redirect()->route('menu-items.index')->with('success', 'Item del menu actualizado.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();

        return redirect()->route('menu-items.index')->with('success', 'Item del menu eliminado.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ordered_ids' => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['integer', 'exists:menu_items,id'],
        ]);

        foreach (array_values($data['ordered_ids']) as $index => $menuItemId) {
            MenuItem::whereKey($menuItemId)->update([
                'sort_order' => $index + 1,
            ]);
        }

        return redirect()->route('menu-items.index')->with('success', 'Orden del menu actualizado.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'item_type' => ['required', 'in:' . implode(',', array_keys(MenuItem::typeOptions()))],
            'home_section' => ['nullable', 'in:' . implode(',', array_keys(MenuItem::homeSectionOptions()))],
            'route_name' => ['nullable', 'in:' . implode(',', array_keys(MenuItem::routeOptions()))],
            'external_url' => ['nullable', 'url', 'max:2048'],
            'open_in_new_tab' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $itemValue = match ($data['item_type']) {
            MenuItem::TYPE_HOME_SECTION => $data['home_section'] ?? null,
            MenuItem::TYPE_ROUTE => $data['route_name'] ?? null,
            MenuItem::TYPE_EXTERNAL_URL => $data['external_url'] ?? null,
        };

        if (! $itemValue) {
            throw ValidationException::withMessages([
                'item_value' => 'Completa el destino del item segun el tipo elegido.',
            ]);
        }

        return [
            'label' => $data['label'],
            'item_type' => $data['item_type'],
            'item_value' => $itemValue,
            'open_in_new_tab' => (bool) ($data['open_in_new_tab'] ?? false),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];
    }
}
