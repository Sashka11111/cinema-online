<div class="movies-page__filters" x-data="{ showAdvanced: false }">
    <!-- Верхня панель: пошук та кнопки дій -->
    <div class="filters__top-panel">
        <!-- Пошук -->
        <div class="filters__search">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Пошук ..."
                   class="filters__search-input">
        </div>

        <!-- Кнопки дій -->
        <div class="filters__actions">
            <button type="button" wire:click="resetFilters" class="filters__reset-button">
                Скинути фільтри
            </button>
            <button type="button" wire:click="applyFilters" class="filters__apply-button">
                Застосувати
            </button>
        </div>
    </div>

    <!-- Основні фільтри -->
    <div class="filters__main">
        <!-- Статус -->
        <div class="filters__item">
            <div class="filters__select-wrapper">
                <select wire:model.live="status" class="filters__select">
                    <option value="">Всі статуси</option>
                    @foreach($statuses as $statusOption)
                        <option
                            value="{{ $statusOption->value }}">{{ $statusOption->getLabel() }}</option>
                    @endforeach
                </select>
                <div class="filters__select-arrow"></div>
            </div>
        </div>

        <!-- Період -->
        <div class="filters__item">
            <div class="filters__select-wrapper">
                <select wire:model.live="period" class="filters__select">
                    <option value="">Всі періоди</option>
                    @foreach($periods as $periodOption)
                        <option
                            value="{{ $periodOption->value }}">{{ $periodOption->getLabel() }}</option>
                    @endforeach
                </select>
                <div class="filters__select-arrow"></div>
            </div>
        </div>

        <!-- Студія -->
        <div class="filters__item">
            <div class="filters__select-wrapper">
                <select wire:model.live="studio" class="filters__select">
                    <option value="">Всі студії</option>
                    @foreach($studios as $studioOption)
                        <option
                            value="{{ $studioOption->id }}">{{ $studioOption->name }}</option>
                    @endforeach
                </select>
                <div class="filters__select-arrow"></div>
            </div>
        </div>

        <!-- Рік -->
        <div class="filters__item">
            <div class="filters__select-wrapper">
                <select wire:model.live="year" class="filters__select">
                    <option value="">Всі роки</option>
                    @foreach($years as $yearOption)
                        <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                    @endforeach
                </select>
                <div class="filters__select-arrow"></div>
            </div>
        </div>
    </div>

    <!-- Розширені фільтри -->
    <div class="filters__advanced-toggle">
        <button type="button" class="filters__advanced-button"
                @click="showAdvanced = !showAdvanced">
            <span>Розширені фільтри</span>
            <div class="filters__advanced-icon"
                 :class="{'filters__advanced-icon--active': showAdvanced}"></div>
        </button>
    </div>

    <div x-show="showAdvanced" x-transition class="filters__advanced">
        <div class="filters__group">

            <div class="filters__select-wrapper">
                <select wire:model.live="rating" class="filters__select">
                    <option value="">Усі рейтинги</option>
                    @foreach($ratings as $ratingOption)
                        <option
                            value="{{ $ratingOption->value }}">{{ $ratingOption->getLabel() }}</option>
                    @endforeach
                </select>
                <div class="filters__select-arrow"></div>
            </div>

            <div class="filters__select-wrapper">
                <select wire:model.live="duration" class="filters__select">
                    <option value="">Будь-яка тривалість</option>
                    <option value="short">До 90 хв</option>
                    <option value="medium">90-120 хв</option>
                    <option value="long">Більше 120 хв</option>
                </select>
                <div class="filters__select-arrow"></div>
            </div>

            <div class="filters__select-wrapper">
                <select wire:model.live="country" class="filters__select">
                    <option value="">Усі країни</option>
                    @foreach($countries as $countryOption)
                        <option
                            value="{{ $countryOption->value }}">
                            {{  __("country.{$countryOption->value}.name") }}
                        </option>
                    @endforeach
                </select>
                <div class="filters__select-arrow"></div>
            </div>

            <div class="filters__select-wrapper">
                <select wire:model.live="source" class="filters__select">
                    <option value="">Усі джерела</option>
                    @foreach($sources as $sourceOption)
                        <option
                            value="{{ $sourceOption->value }}">{{ $sourceOption->getLabel() }}</option>
                    @endforeach
                </select>
                <div class="filters__select-arrow"></div>
            </div>

            <div class="filters__select-wrapper">
                <select wire:model.live="imdbScoreMin" class="filters__select">
                    <option value="">Будь-який рейтинг IMDB</option>
                    @foreach(range(1, 10) as $score)
                        <option value="{{ $score }}">{{ $score }}</option>
                    @endforeach
                </select>
                <div class="filters__select-arrow"></div>
            </div>
        </div>
    </div>

    <div class="filters__sort">
        <span class="filters__sort-label">
            <div class="filters__sort-icon"></div>
            Сортувати за:
        </span>
        <div class="filters__sort-buttons">
            <button
                wire:click="sortBy('name')"
                class="filters__sort-button {{ $sortField === 'name' ? 'filters__sort-button--active' : '' }}"
            >
                Назвою
                @if($sortField === 'name')
                    <span
                        class="filters__sort-direction {{ $sortDirection === 'desc' ? 'desc' : '' }}"></span>
                @endif
            </button>
            <button
                wire:click="sortBy('first_air_date')"
                class="filters__sort-button {{ $sortField === 'first_air_date' ? 'filters__sort-button--active' : '' }}"
            >
                Датою виходу
                @if($sortField === 'first_air_date')
                    <span
                        class="filters__sort-direction {{ $sortDirection === 'desc' ? 'desc' : '' }}"></span>
                @endif
            </button>
            <button
                wire:click="sortBy('imdb_score')"
                class="filters__sort-button {{ $sortField === 'imdb_score' ? 'filters__sort-button--active' : '' }}"
            >
                Рейтингом IMDB
                @if($sortField === 'imdb_score')
                    <span
                        class="filters__sort-direction {{ $sortDirection === 'desc' ? 'desc' : '' }}"></span>
                @endif
            </button>
            @if(in_array($contentType, ['series', 'cartoon_series', 'anime']))
                <button
                    wire:click="sortBy('episodes_count')"
                    class="filters__sort-button {{ $sortField === 'episodes_count' ? 'filters__sort-button--active' : '' }}"
                >
                    Кількістю епізодів
                    @if($sortField === 'episodes_count')
                        <span
                            class="filters__sort-direction {{ $sortDirection === 'desc' ? 'desc' : '' }}"></span>
                    @endif
                </button>
            @endif
            <button
                wire:click="sortBy('duration')"
                class="filters__sort-button {{ $sortField === 'duration' ? 'filters__sort-button--active' : '' }}"
            >
                Тривалістю
                @if($sortField === 'duration')
                    <span
                        class="filters__sort-direction {{ $sortDirection === 'desc' ? 'desc' : '' }}"></span>
                @endif
            </button>
        </div>
    </div>
</div>
