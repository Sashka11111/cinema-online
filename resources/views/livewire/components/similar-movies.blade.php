<section class="similar-movies">
    <h2 class="similar-movies__title">Схожі фільми</h2>

    @if($movies->isNotEmpty())
        <div class="similar-movies__list">
            @foreach($movies as $movie)
                <livewire:components.movie-card :movie="$movie" :key="$movie->id"/>
            @endforeach
        </div>
    @else
        <div class="similar-movies__empty">
            На жаль, поки немає схожих медіа.
        </div>
    @endif
</section>
