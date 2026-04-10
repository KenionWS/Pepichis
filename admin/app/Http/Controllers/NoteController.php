<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(): View
    {
        return view('notes.index', [
            'notes' => Note::query()
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('notes.form', [
            'note' => new Note(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['title']);
        $data = $this->normalizePublishData($request, $data);

        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $this->storeImage($request, 'notes', 'cover_image');
        }

        Note::create($data);

        return redirect()->route('notes.index')->with('success', 'Nota creada.');
    }

    public function edit(Note $note): View
    {
        return view('notes.form', [
            'note' => $note,
        ]);
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['title'], $note->id);
        $data = $this->normalizePublishData($request, $data, $note);

        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $this->storeImage($request, 'notes', 'cover_image');
        }

        $note->update($data);

        return redirect()->route('notes.index')->with('success', 'Nota actualizada.');
    }

    public function destroy(Note $note): RedirectResponse
    {
        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Nota eliminada.');
    }

    public function storeEditorImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:6144'],
        ]);

        $path = $this->storeImage($request, 'notes/inline', 'image');

        return response()->json([
            'url' => asset($path),
            'path' => $path,
        ]);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function normalizePublishData(Request $request, array $data, ?Note $note = null): array
    {
        $data['is_published'] = $request->boolean('is_published');

        if ($data['is_published']) {
            $data['published_at'] = $data['published_at']
                ?? optional($note)->published_at
                ?? now();
        } else {
            $data['published_at'] = null;
        }

        return $data;
    }

    private function storeImage(Request $request, string $folder, string $field): string
    {
        $directory = public_path('uploads/' . $folder);
        File::ensureDirectoryExists($directory);

        $file = $request->file($field);
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '-' . now()->format('YmdHis')
            . '.' . $file->getClientOriginalExtension();

        $file->move($directory, $filename);

        return 'uploads/' . $folder . '/' . $filename;
    }

    private function resolveSlug(?string $inputSlug, string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($inputSlug ?: $title);
        $slug = $baseSlug;
        $counter = 2;

        while (
            Note::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
