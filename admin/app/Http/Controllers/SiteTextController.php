<?php

namespace App\Http\Controllers;

use App\Models\SiteText;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SiteTextController extends Controller
{
    public function editAbout(): View
    {
        return view('site-texts.about-form', [
            'siteText' => $this->aboutText(),
        ]);
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('site_texts')) {
            return back()->withErrors([
                'database' => 'Todavia no existe la tabla site_texts en la base de datos. Crea la tabla antes de guardar esta seccion.',
            ]);
        }

        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $siteText = $this->aboutText();
        $siteText->fill($data);
        $siteText->save();

        return redirect()->route('site-texts.about.edit')->with('success', 'Seccion Nosotros actualizada.');
    }

    private function aboutText(): SiteText
    {
        if (! Schema::hasTable('site_texts')) {
            return new SiteText(SiteText::defaultAbout());
        }

        return SiteText::firstOrCreate(
            ['key' => SiteText::KEY_ABOUT],
            SiteText::defaultAbout()
        );
    }
}
