<a href="{{ route('selection.show', $selection->slug) }}" class="selection-card">
    <div class="selection-card__image-wrapper">
        @foreach($posters as $index => $movie)
            <div class="selection-card__poster {{ 'selection-card__poster--' . ($index + 1) }}">
                <img
                    src="{{ $movie->poster_url ?? asset('images/movie-placeholder.svg') }}"
                    alt="{{ $selection->name }} - Poster {{ $index + 1 }}"
                    class="selection-card__poster-image"
                    loading="lazy"
                    onerror="this.onerror=null; this.src='{{ asset('images/movie-placeholder.svg') }}';">
            </div>
        @endforeach
        <div class="selection-card__overlay"></div>
        <div class="selection-card__count">
            @php
                $totalItems = data_get($selection, 'movies_count', 0) +
                              data_get($selection, 'persons_count', 0) +
                              data_get($selection, 'episodes_count', 0);
            @endphp

            {{ $totalItems }}
            @if($totalItems == 1)
                елемент
            @elseif($totalItems >= 2 && $totalItems <= 4)
                елементи
            @else
                елементів
            @endif
        </div>

    </div>
    <div class="selection-card__content">
        <h3 class="selection-card__title">{{ $selection->name }}</h3>
        <p class="selection-card__description">{{ Str::limit(strip_tags($selection->description ?? ''), 100) }}</p>
        <div class="selection-card__meta">
            <div class="selection-card__date">
                <i class="far fa-calendar-alt"></i>
                {{ $selection->created_at->format('d.m.Y') }}
            </div>
            <div class="selection-card__author">
                <i class="far fa-user"></i>
                {{ $selection->user->name ?? 'Адміністратор' }}
            </div>
        </div>
    </div>
</a>
