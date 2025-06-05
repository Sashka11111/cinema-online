<a href="{{ route('selection.show', $selection->slug) }}" class="selection-card">
    <div class="selection-card-image-wrapper">
        @foreach($posters as $index => $movie)
            <div class="featured-poster-item {{ 'poster-' . ($index + 1) }}">
                <img
                    src="{{ $movie->poster_url ?? asset('images/movie-placeholder.svg') }}"
                    alt="{{ $selection->name }} - Poster {{ $index + 1 }}"
                    class="featured-poster-image"
                    loading="lazy"
                    onerror="this.onerror=null; this.src='{{ asset('images/movie-placeholder.svg') }}';">
            </div>
        @endforeach
        <div class="selection-card-overlay"></div>
        <div class="selection-card-count">
            {{ $selection->movies_count ?? $selection->movies->count() }} елементів
        </div>
    </div>
    <div class="selection-card-content">
        <h3 class="selection-card-title">{{ $selection->name }}</h3>
        <p class="selection-card-description">{{ Str::limit(strip_tags($selection->description ?? ''), 100) }}</p>
        <div class="selection-card-meta">
            <div class="selection-card-date">
                <i class="far fa-calendar-alt"></i>
                {{ $selection->created_at->format('d.m.Y') }}
            </div>
            <div class="selection-card-author">
                <i class="far fa-user"></i>
                {{ $selection->user->name ?? 'Адміністратор' }}
            </div>
        </div>
    </div>
</a>
