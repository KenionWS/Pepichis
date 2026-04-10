@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-copy">Una vista rápida del catálogo cargado desde el sitio actual, con foco en <strong>mantenimiento ágil</strong> y lectura clara.</p>
        </div>
    </div>

    <div class="metric-grid">
        <div class="metric">
            <div class="metric-top">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M16 20a4 4 0 0 0-8 0" />
                        <circle cx="12" cy="8" r="4" />
                        <path d="M20 19a3.5 3.5 0 0 0-3-3.46" />
                    </svg>
                </div>
            </div>
            <span>Productores activos</span>
            <strong>{{ $producerCount }}</strong>
        </div>

        <div class="metric">
            <div class="metric-top">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M7 3h10" />
                        <path d="M9 3v6a3 3 0 0 0 6 0V3" />
                        <path d="M12 12v8" />
                        <path d="M8 21h8" />
                    </svg>
                </div>
            </div>
            <span>Vinos cargados</span>
            <strong>{{ $wineCount }}</strong>
        </div>

        <div class="metric">
            <div class="metric-top">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M6 4h9l3 3v13H6z" />
                        <path d="M15 4v4h4" />
                        <path d="M9 12h6" />
                        <path d="M9 16h6" />
                    </svg>
                </div>
            </div>
            <span>Notas cargadas</span>
            <strong>{{ $noteCount }}</strong>
        </div>

        <div class="metric">
            <div class="metric-top">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="m7 7 10 10" />
                        <path d="M7 17 17 7" />
                        <circle cx="7" cy="7" r="3" />
                        <circle cx="17" cy="17" r="3" />
                        <circle cx="17" cy="7" r="3" />
                        <circle cx="7" cy="17" r="3" />
                    </svg>
                </div>
            </div>
            <span>Características disponibles</span>
            <strong>{{ $attributeCount }}</strong>
        </div>
        <div class="metric">
            <div class="metric-top">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M4 7h16" />
                        <path d="M4 12h12" />
                        <path d="M4 17h9" />
                    </svg>
                </div>
            </div>
            <span>Items del menu</span>
            <strong>{{ $menuItemCount }}</strong>
        </div>
    </div>

    <div class="two-col" style="margin-bottom: 20px;">
        <div class="card">
            <div class="card-head">
                <h2 class="section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M16 20a4 4 0 0 0-8 0" />
                        <circle cx="12" cy="8" r="4" />
                    </svg>
                    Últimos productores
                </h2>
                <a class="btn-secondary" href="{{ route('producers.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                    Ver todos
                </a>
            </div>
            <div class="list-stack">
                @forelse($latestProducers as $producer)
                    <div class="list-item">
                        <div class="list-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M16 20a4 4 0 0 0-8 0" />
                            </svg>
                        </div>
                        <div class="row-title">
                            <strong>{{ $producer->name }}</strong>
                            <div class="help">{{ $producer->city ?: 'Sin ciudad' }}{{ $producer->country ? ' · ' . $producer->country : '' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M16 20a4 4 0 0 0-8 0" />
                            </svg>
                        </div>
                        <div>No hay productores todavía.</div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h2 class="section-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M7 3h10" />
                        <path d="M9 3v6a3 3 0 0 0 6 0V3" />
                        <path d="M12 12v8" />
                    </svg>
                    Últimos vinos
                </h2>
                <a class="btn-secondary" href="{{ route('wines.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                    Ver todos
                </a>
            </div>
            <div class="list-stack">
                @forelse($latestWines as $wine)
                    <div class="list-item">
                        <div class="list-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                <path d="M7 3h10" />
                                <path d="M9 3v6a3 3 0 0 0 6 0V3" />
                                <path d="M12 12v8" />
                            </svg>
                        </div>
                        <div class="row-title">
                            <strong>{{ $wine->name }}</strong>
                            <div class="help">{{ optional($wine->producer)->name ?: 'Sin productor' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                <path d="M7 3h10" />
                                <path d="M9 3v6a3 3 0 0 0 6 0V3" />
                                <path d="M12 12v8" />
                            </svg>
                        </div>
                        <div>No hay vinos todavía.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="M6 4h9l3 3v13H6z" />
                    <path d="M15 4v4h4" />
                    <path d="M9 12h6" />
                    <path d="M9 16h6" />
                </svg>
                Últimas notas
            </h2>
            <a class="btn-secondary" href="{{ route('notes.index') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
                Ver todas
            </a>
        </div>
        <div class="list-stack">
            @forelse($latestNotes as $note)
                <div class="list-item">
                    <div class="list-item-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                            <path d="M6 4h9l3 3v13H6z" />
                            <path d="M15 4v4h4" />
                            <path d="M9 12h6" />
                            <path d="M9 16h6" />
                        </svg>
                    </div>
                    <div class="row-title">
                        <strong>{{ $note->title }}</strong>
                        <div class="help">
                            {{ $note->is_published ? 'Publicada' : 'Borrador' }}
                            @if($note->published_at)
                                {{ ' · ' . $note->published_at->format('d/m/Y') }}
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                            <path d="M6 4h9l3 3v13H6z" />
                            <path d="M15 4v4h4" />
                        </svg>
                    </div>
                    <div>No hay notas todavía.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
