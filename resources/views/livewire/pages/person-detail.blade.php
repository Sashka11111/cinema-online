<div>
    <livewire:components.header-component/>

    <main class="content-page__main">
        <div class="container">
            <!-- Breadcrumbs -->
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => $person->name, 'route' => 'person.show', 'params' => ['person' => $person->slug], 'active' => true],
            ]"/>

            <!-- Person Details -->
            <div class="person-detail">
                <!-- Header Section -->
                <div class="person-detail__header">
                    <div class="person-detail__image">
                        <img
                            src="{{ $person->image_url ?? asset('images/person-placeholder.svg') }}"
                            alt="{{ $person->name }}"
                            class="person-detail__photo"
                            loading="lazy"
                            onerror="this.onerror=null; this.src='{{ asset('images/person-placeholder.svg') }}';">
                    </div>
                    <div class="person-detail__info">
                        <h1 class="person-detail__title">{{ $pageTitle }}</h1>

                        @if($person->original_name)
                            <div class="person-detail__meta-item">
                                <span class="person-detail__meta-label">Оригінальне ім'я:</span>
                                <span class="person-detail__meta-value">{{ $person->original_name }}</span>
                            </div>
                        @endif

                        @if($person->type)
                            <div class="person-detail__meta-item">
                                <span class="person-detail__meta-label">Тип:</span>
                                <span class="person-detail__meta-value">{{ $person->type->getLabel() }}</span>
                            </div>
                        @endif

                        @if($person->gender)
                            <div class="person-detail__meta-item">
                                <i class="fas fa-venus-mars person-detail__meta-icon"></i>
                                <span class="person-detail__meta-label">Стать:</span>
                                <span class="person-detail__meta-value">{{ $person->gender->getLabel() }}</span>
                            </div>
                        @endif

                        @if($person->birthday)
                            <div class="person-detail__meta-item">
                                <i class="far fa-calendar-alt person-detail__meta-icon"></i>
                                <span class="person-detail__meta-label">Дата народження:</span>
                                <span class="person-detail__meta-value">
                                    {{ \Carbon\Carbon::parse($person->birthday)->format('d.m.Y') }}
                                    ({{ \Carbon\Carbon::parse($person->birthday)->age }} років)
                                </span>
                            </div>
                        @endif

                        @if($person->birthplace)
                            <div class="person-detail__meta-item">
                                <i class="fas fa-map-marker-alt person-detail__meta-icon"></i>
                                <span class="person-detail__meta-label">Місце народження:</span>
                                <span class="person-detail__meta-value">{{ $person->birthplace }}</span>
                            </div>
                        @endif

                        @if($person->description)
                            <div class="person-detail__biography">
                                <h3 class="person-detail__biography-title">Біографія</h3>
                                <div class="person-detail__biography-text">
                                    {!! nl2br(e($person->description)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Content Section -->
                <div class="person-detail__content">
                    <section class="person-detail__movies">
                        <h2 class="person-detail__section-title">Медіа</h2>
                        @if($movies && $movies->isNotEmpty())
                            <div class="person-detail__movies-grid">
                                @foreach($movies as $movie)
                                    <livewire:components.movie-card :movie="$movie"
                                                                    :key="'movie-'.$movie->id"/>
                                @endforeach
                            </div>
                        @else
                            <div class="person-detail__empty">
                                <div class="person-detail__empty-content">
                                    <p class="person-detail__empty-text">Наразі персона не належить жодному медіа.</p>
                                </div>
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </main>

    <livewire:components.main-footer-component/>
</div>
