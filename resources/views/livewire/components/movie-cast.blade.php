<div class="movie-cast">
    <h2 class="movie-cast__title">Актори та знімальна група</h2>
    <div class="movie-cast__list">
        @foreach($persons as $person)
            <div class="movie-cast__item">
                <div class="movie-cast__photo-container">
                    <img 
                        src="{{ $person->photo ? asset('storage/' . $person->photo) : asset('images/no-photo.jpg') }}" 
                        alt="{{ $person->name }}" 
                        class="movie-cast__photo"
                    >
                </div>
                <div class="movie-cast__info">
                    <div class="movie-cast__name">{{ $person->name }}</div>
                    @if($person->pivot->character_name)
                        <div class="movie-cast__character">{{ $person->pivot->character_name }}</div>
                    @endif
                    <div class="movie-cast__type">{{ $person->type->name }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>