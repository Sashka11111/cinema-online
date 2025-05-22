@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Пагінація">
        <ul class="pagination">
            {{-- Попередня сторінка --}}
            @if ($paginator->onFirstPage())
                <li class="pagination__item">
                    <span class="pagination__link pagination__link--disabled" aria-disabled="true"
                          aria-label="Попередня">
                        <span class="pagination__arrow" aria-hidden="true">&lsaquo;</span>
                    </span>
                </li>
            @else
                <li class="pagination__item">
                    <button wire:click="previousPage('page')"
                            wire:loading.attr="disabled" rel="prev" class="pagination__link"
                            aria-label="Попередня">
                        <span class="pagination__arrow" aria-hidden="true">&lsaquo;</span>
                    </button>
                </li>
            @endif

            {{-- Номери сторінок --}}
            @foreach ($elements as $element)
                {{-- "Три крапки" розділювач --}}
                @if (is_string($element))
                    <li class="pagination__item">
                        <span class="pagination__ellipsis">{{ $element }}</span>
                    </li>
                @endif

                {{-- Масив посилань --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="pagination__item pagination__item--number {{ $page == $paginator->currentPage() ? 'pagination__item--active' : '' }}">
                            @if ($page == $paginator->currentPage())
                                <span class="pagination__link pagination__link--active"
                                      aria-current="page">{{ $page }}</span>
                            @else
                                <button wire:click="gotoPage({{ $page }}, 'page')"
                                        class="pagination__link">{{ $page }}</button>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Наступна сторінка --}}
            @if ($paginator->hasMorePages())
                <li class="pagination__item">
                    <button wire:click="nextPage('page')"
                            wire:loading.attr="disabled" rel="next" class="pagination__link"
                            aria-label="Наступна">
                        <span class="pagination__arrow" aria-hidden="true">&rsaquo;</span>
                    </button>
                </li>
            @else
                <li class="pagination__item">
                    <span class="pagination__link pagination__link--disabled" aria-disabled="true"
                          aria-label="Наступна">
                        <span class="pagination__arrow" aria-hidden="true">&rsaquo;</span>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
