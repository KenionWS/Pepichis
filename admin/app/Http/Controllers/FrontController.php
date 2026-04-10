<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Producer;
use Illuminate\View\View;

class FrontController extends Controller
{
    public function home(): View
    {
        $producers = Producer::with([
            'attributeValues.attribute',
            'wines.producer',
            'wines.attributeValues.attribute',
        ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $bottleItems = $producers->flatMap(function ($producer) {
            return $producer->wines->where('show_on_home', true)->values();
        })->values();

        if ($bottleItems->isEmpty()) {
            $bottleItems = $producers->flatMap->wines->values();
        }

        if ($bottleItems->isNotEmpty()) {
            while ($bottleItems->count() < 24) {
                $bottleItems = $bottleItems->concat($bottleItems);
            }
        }

        return view('front.home', [
            'producers' => $producers,
            'bottleItems' => $bottleItems->take(24)->values(),
            'latestNotes' => Note::published()
                ->orderByDesc('published_at')
                ->take(3)
                ->get(),
        ]);
    }

    public function producers(): View
    {
        return view('front.producers.index', [
            'producers' => Producer::with(['attributeValues.attribute', 'wines'])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function producerDetail(Producer $producer): View
    {
        return view('front.producers.show', [
            'producer' => $producer->load([
                'attributeValues.attribute',
                'wines.attributeValues.attribute',
            ]),
        ]);
    }

    public function notes(): View
    {
        return view('front.notes.index', [
            'notes' => Note::published()
                ->orderByDesc('published_at')
                ->paginate(9),
            'featuredNote' => Note::published()
                ->orderByDesc('published_at')
                ->first(),
        ]);
    }

    public function noteDetail(Note $note): View
    {
        abort_unless($note->is_published && (!$note->published_at || $note->published_at->isPast()), 404);

        return view('front.notes.show', [
            'note' => $note,
            'relatedNotes' => Note::published()
                ->whereKeyNot($note->id)
                ->orderByDesc('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
