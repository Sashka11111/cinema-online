<div class="genres-grid">
    @foreach($genres as $genre)
        <a href="{{ route('movies', ['genre' => [$genre->id]]) }}" wire:navigate class="genre-card">
            <div class="genre-card__overlay"></div>
            <h3 class="genre-card__title">{{ $genre->name }}</h3>
            <span class="genre-card__count">{{ $genre->movies_count }} фільмів</span>
        </a>
    @endforeach
</div>