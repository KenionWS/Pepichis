<?php

namespace App\Providers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('front.layouts.app', function ($view) {
            $menuItems = [];

            if (Schema::hasTable('menu_items')) {
                $menuItems = MenuItem::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('label')
                    ->get()
                    ->map(function (MenuItem $item) {
                        $href = '#';

                        if ($item->item_type === MenuItem::TYPE_HOME_SECTION) {
                            $homeAnchor = request()->routeIs('front.home') ? '' : route('front.home');
                            $href = $homeAnchor . '#' . ltrim($item->item_value, '#');
                        }

                        if ($item->item_type === MenuItem::TYPE_ROUTE && Route::has($item->item_value)) {
                            $href = route($item->item_value);
                        }

                        if ($item->item_type === MenuItem::TYPE_EXTERNAL_URL) {
                            $href = $item->item_value;
                        }

                        return (object) [
                            'label' => $item->label,
                            'href' => $href,
                            'open_in_new_tab' => $item->open_in_new_tab,
                        ];
                    })
                    ->all();
            }

            if ($menuItems === []) {
                $homeAnchor = request()->routeIs('front.home') ? '' : route('front.home');

                $menuItems = [
                    (object) ['label' => 'Nosotros', 'href' => $homeAnchor . '#nosotros', 'open_in_new_tab' => false],
                    (object) ['label' => 'Seleccion', 'href' => $homeAnchor . '#seleccion', 'open_in_new_tab' => false],
                    (object) ['label' => 'Productores', 'href' => $homeAnchor . '#productores', 'open_in_new_tab' => false],
                    (object) ['label' => 'Notas', 'href' => route('front.notes.index'), 'open_in_new_tab' => false],
                    (object) ['label' => 'Contacto', 'href' => $homeAnchor . '#contacto', 'open_in_new_tab' => false],
                ];
            }

            $view->with('menuItems', $menuItems);
        });
    }
}
