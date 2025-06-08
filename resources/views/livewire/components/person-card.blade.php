<div class="person-card">
    <a href="{{ route('person.show', $person->slug) }}" wire:navigate class="person-card__link">
        <div class="person-card__image">
            <img src="{{ $person->image_url ?? asset('images/person-placeholder.svg') }}"
                 alt="{{ $person->name }}"
                 class="person-card__photo"
                 loading="lazy"
                 onerror="this.onerror=null; this.src='{{ asset('images/person-placeholder.svg') }}';">
        </div>

        <div class="person-card__content">
            <h3 class="person-card__name">{{ $person->name }}</h3>

            @if($showCharacter && $characterName)
                <p class="person-card__character">{{ $characterName }}</p>
            @endif

            @if($person->type)
                <p class="person-card__type">{{ $person->type->getLabel() }}</p>
            @endif

            @if($person->birthday)
                <p class="person-card__birth-date">
                    <i class="far fa-calendar-alt"></i>
                    {{ \Carbon\Carbon::parse($person->birthday)->age }} років
                </p>
            @endif

            @if($person->birthplace)
                <p class="person-card__birthplace">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $person->birthplace }}
                </p>
            @endif

            @if($person->gender)
                <p class="person-card__gender">
                    <i class="fas fa-venus-mars"></i>
                    {{ $person->gender->getLabel() }}
                </p>
            @endif
        </div>
    </a>
</div>
