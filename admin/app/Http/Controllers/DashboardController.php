<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Note;
use App\Models\Producer;
use App\Models\Wine;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard.index', [
            'producerCount' => Producer::count(),
            'wineCount' => Wine::count(),
            'noteCount' => Note::count(),
            'attributeCount' => Attribute::count(),
            'latestProducers' => Producer::latest()->take(5)->get(),
            'latestWines' => Wine::with('producer')->latest()->take(5)->get(),
            'latestNotes' => Note::orderByDesc('published_at')->orderByDesc('created_at')->take(5)->get(),
        ]);
    }
}
