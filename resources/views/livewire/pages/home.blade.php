<div class="home-page">
    <livewire:components.header-component/>

    <main class="content-page__main">
        <div class="container">
            <livewire:components.home-promo-component/>
            <livewire:components.trending-movies-component :contentType="'all'"/>

            <div class="selections-page__header">
                <h1 class="content-page__title">Останні підбірки фільмів</h1>
                <div class="selections-page__view-all">
                    <a href="{{ route('selections') }}"
                       class="selections-page__view-all-link">
                        Переглянути всі підбірки
                    </a>
                </div>
            </div>
            <div class="selections-grid">
                @forelse($latestSelections->take(3) as $selection)
                    <livewire:components.selection-card :selection="$selection"
                                                        :key="$selection->id"/>
                @empty
                    <div class="selections-page__empty">
                        <p class="selections-page__empty-text">Підбірки не знайдено.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <livewire:components.main-footer-component/>
</div>
