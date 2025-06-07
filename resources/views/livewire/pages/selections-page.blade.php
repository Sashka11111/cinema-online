<div class="selections-page">
    <livewire:components.header-component/>

    <main class="content-page-main">
        <div class="container">
            <livewire:components.breadcrumbs :items="[
                    ['label' => 'Головна', 'route' => 'home'],
                    ['label' => 'Підбірки', 'active' => true]
                ]"/>

            <h1 class="content-page__title">Підбірки</h1>

            <div class="selections-page__filters">
                <div class="selections-page__top-panel">
                    <div class="selections-page__search">
                        <input type="text" wire:model.live.debounce.300ms="search"
                               placeholder="Пошук підбірок..."
                               class="selections-page__search-input">
                    </div>

                    <div class="selections-page__actions">
                        <button type="button" wire:click="resetFilters"
                                class="selections-page__reset-button"
                                @if(!$hasActiveFilters) disabled @endif>
                            Скинути фільтри
                        </button>
                        <button type="button" wire:click="applyFilters"
                                class="selections-page__apply-button">
                            Застосувати
                        </button>
                    </div>
                </div>

                <!-- Основні фільтри -->
                <div class="selections-page__filters-group">
                    <div class="selections-page__filter-item">
                        <label for="author" class="selections-page__filter-label">Автор</label>
                        <div class="selections-page__select-wrapper">
                            <select wire:model="tempAuthor" class="selections-page__select" id="author">
                                <option value="">Усі автори</option>
                                @foreach($authors as $authorId => $authorName)
                                    <option value="{{ $authorId }}">{{ $authorName }}</option>
                                @endforeach
                            </select>
                            <div class="selections-page__select-arrow"></div>
                        </div>
                    </div>
                </div>

                <div class="selections-page__sort">
                    <div class="selections-page__sort-label">
                        <span class="selections-page__sort-icon"></span>
                        Сортувати за:
                    </div>
                    <div class="selections-page__sort-buttons">
                        @foreach($sortOptions as $sortKey => $sortLabel)
                            <button
                                class="selections-page__sort-button {{ $sortField == $sortKey ? 'selections-page__sort-button--active' : '' }}"
                                wire:click="sortBy('{{ $sortKey }}')">
                                {{ $sortLabel }}
                                @if($sortField == $sortKey)
                                    <span class="selections-page__sort-direction {{ $sortDirection == 'desc' ? 'desc' : 'asc' }}"></span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="selections-grid">
                @forelse($selections as $selection)
                    <livewire:components.selection-card :selection="$selection"
                                                        :key="$selection->id"/>
                @empty
                    <div class="selections-page-empty">
                        <p class="selections-page-empty-text">Підбірки не знайдено.</p>
                    </div>
                @endforelse
            </div>

            <div class="content-page__pagination">
                {{ $selections->links('livewire.components.pagination') }}
            </div>
        </div>
    </main>

    <livewire:components.main-footer-component/>
</div>
