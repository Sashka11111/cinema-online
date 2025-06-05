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
                        <input type="text" wire:model="tempSearch"
                               placeholder="Пошук підбірок..."
                               class="selections-page__search-input">
                    </div>
                    <div class="selections-page__actions">
                        <button type="button" wire:click="resetFilters"
                                class="selections-page__reset-button">
                            Скинути фільтри
                        </button>
                        <button type="button" wire:click="applyFilters"
                                class="selections-page__apply-button">
                            Застосувати
                        </button>
                    </div>
                </div>

                <div class="selections-page__filters-group">
                    <div class="selections-page__filter-item">
                        <label for="minItems" class="selections-page__filter-label">Кількість
                            елементів</label>
                        <div class="selections-page__select-wrapper">
                            <select wire:model="tempMinItems" class="selections-page__select"
                                    id="minItems">
                                <option value="">Будь-яка кількість елементів</option>
                                <option value="5">Більше 5</option>
                                <option value="10">Більше 10</option>
                                <option value="20">Більше 20</option>
                            </select>
                            <div class="selections-page__select-arrow"></div>
                        </div>
                    </div>

                    <div class="selections-page__filter-item">
                        <label for="author" class="selections-page__filter-label">Автор</label>
                        <div class="selections-page__select-wrapper">
                            <select wire:model="tempAuthor" class="selections-page__select"
                                    id="author">
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
                        <button
                            class="selections-page__sort-button {{ $sortField == 'created_at' ? 'selections-page__sort-button--active' : '' }}"
                            wire:click="$set('sortField', 'created_at')">
                            Датою створення
                            <span
                                class="selections-page__sort-direction {{ $sortDirection == 'desc' ? 'desc' : '' }}"></span>
                        </button>
                        <button
                            class="selections-page__sort-button {{ $sortField == 'popularity' ? 'selections-page__sort-button--active' : '' }}"
                            wire:click="$set('sortField', 'popularity')">
                            Популярністю
                            <span
                                class="selections-page__sort-direction {{ $sortDirection == 'desc' ? 'desc' : '' }}"></span>
                        </button>
                        <button
                            class="selections-page__sort-button {{ $sortField == 'items_count' ? 'selections-page__sort-button--active' : '' }}"
                            wire:click="$set('sortField', 'items_count')">
                            Кількістю елементів
                            <span
                                class="selections-page__sort-direction {{ $sortDirection == 'desc' ? 'desc' : '' }}"></span>
                        </button>
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
