<div class="movie-player">
    <h2 class="movie-player__title">Дивитися фільм {{ $movie->name }}</h2>
    <div class="movie-player__container">
        @if(isset($movie->attachments) && count($movie->attachments) > 0)
            @php
                $videoAttachment = collect($movie->attachments)
                    ->firstWhere('type', 'video');
            @endphp
            
            @if($videoAttachment)
                <iframe 
                    src="{{ $videoAttachment['src'] }}" 
                    class="movie-player__iframe" 
                    allowfullscreen
                ></iframe>
            @else
                <div class="movie-player__placeholder">
                    <p>Відео недоступне для перегляду</p>
                </div>
            @endif
        @else
            <div class="movie-player__placeholder">
                <p>Відео недоступне для перегляду</p>
            </div>
        @endif
    </div>
</div>