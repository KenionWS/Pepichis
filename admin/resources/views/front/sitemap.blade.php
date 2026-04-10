{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('front.home') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </url>
    <url>
        <loc>{{ route('front.producers.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </url>
@foreach ($producers as $producer)
    <url>
        <loc>{{ route('front.producers.show', $producer->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        <lastmod>{{ optional($producer->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
    </url>
@endforeach
@foreach ($notes as $note)
    <url>
        <loc>{{ route('front.notes.show', $note->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        <lastmod>{{ optional($note->published_at ?? $note->updated_at)->toAtomString() ?? now()->toAtomString() }}</lastmod>
    </url>
@endforeach
</urlset>
