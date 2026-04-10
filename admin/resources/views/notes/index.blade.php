@extends('layouts.app', ['title' => 'Notas'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">Notas</h1>
            <p class="page-copy">Gestion&aacute; la secci&oacute;n editorial del sitio: art&iacute;culos, novedades y contenido pensado para posicionar org&aacute;nicamente.</p>
        </div>
        <a class="btn" href="{{ route('notes.create') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="M12 5v14" />
                <path d="M5 12h14" />
            </svg>
            Nueva nota
        </a>
    </div>

    <div class="card table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Portada</th>
                    <th>T&iacute;tulo</th>
                    <th>Slug</th>
                    <th>Publicaci&oacute;n</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notes as $note)
                    <tr>
                        <td>
                            <span class="pill" style="{{ $note->is_published ? 'background: rgba(220,231,141,.35); color:#4d6312;' : 'background: rgba(128,0,32,.1); color:#800020;' }}">
                                {{ $note->is_published ? 'Publicada' : 'Borrador' }}
                            </span>
                        </td>
                        <td>
                            @if($note->cover_image_path)
                                <img src="{{ asset($note->cover_image_path) }}" alt="{{ $note->title }}" class="thumb">
                            @else
                                <span class="help">Sin imagen</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-title">
                                <strong>{{ $note->title }}</strong>
                                <div class="help">{{ \Illuminate\Support\Str::limit(trim(strip_tags($note->excerpt)), 130) ?: 'Sin extracto.' }}</div>
                            </div>
                        </td>
                        <td><code>{{ $note->slug }}</code></td>
                        <td>{{ $note->published_at ? $note->published_at->format('d/m/Y H:i') : 'Sin fecha' }}</td>
                        <td>
                            <div class="actions">
                                <a class="btn-secondary" href="{{ route('notes.edit', $note) }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <path d="m4 20 4.5-1 9-9-3.5-3.5-9 9L4 20Z" />
                                        <path d="m13.5 6.5 3.5 3.5" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('notes.destroy', $note) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit" onclick="return confirm('&iquest;Eliminar nota?')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                            <path d="M3 6h18" />
                                            <path d="M8 6V4h8v2" />
                                            <path d="m19 6-1 14H6L5 6" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <path d="M6 4h9l3 3v13H6z" />
                                        <path d="M15 4v4h4" />
                                    </svg>
                                </div>
                                <div>No hay notas cargadas.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
