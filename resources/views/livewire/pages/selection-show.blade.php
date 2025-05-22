<div class="selection-show">
    <livewire:components.header-component/>

    <main class="content-page__main">
        <div class="container">
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => 'Підбірки', 'route' => 'selections'],
                ['label' => $selection->name, 'active' => true]
            ]"/>

            <h1 class="content-page__title">{{ $selection->name }}</h1>

            <div class="selection-show__content">
                <div class="selection-show__image">
                    <img
                        src="{{ $selection->image ? asset('storage/' . $selection->image) : asset('images/default-selection.jpg') }}"
                        alt="{{ $selection->name }}"
                        class="selection-show__image-img">
                </div>
                <div class="selection-show__info">
                    <div class="selection-show__description">
                        {!! $selection->description !!}
                    </div>
                    <div class="selection-show__meta">
                        <div class="selection-show__date">
                            <i class="far fa-calendar-alt"></i>
                            {{ $selection->created_at->format('d.m.Y') }}
                        </div>
                        @if($selection->user)
                            <div class="selection-show__author">
                                <i class="far fa-user"></i>
                                {{ $selection->user->name }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="selection-show__movies">
                <h2 class="selection-show__movies-title">Фільми з цієї підбірки</h2>
                <div class="movie-grid">
                    @forelse($movies as $movie)
                        <livewire:components.movie-card :movie="$movie" :key="'movie-'.$movie->id"/>
                    @empty
                        <div class="movies-page__empty">
                            <p class="movies-page__empty-text">Фільми не знайдено.</p>
                        </div>
                    @endforelse
                </div>

                <div class="content-page__pagination">
                    {{ $movies->links('livewire.components.pagination') }}
                </div>
            </div>
        </div>
    </main>

    <livewire:components.main-footer-component/>
</div>
