<nav class="breadcrumbs" aria-label="Навігація по сайту">
    <ol class="breadcrumbs__list">
        @foreach($items as $item)
            <li class="breadcrumbs__item {{ isset($item['active']) && $item['active'] ? 'breadcrumbs__item--active' : '' }}">
                @if(isset($item['active']) && $item['active'])
                    <span aria-current="page">{{ $item['label'] }}</span>
                @else
                    <a href="{{ isset($item['params']) ? route($item['route'], $item['params']) : route($item['route']) }}" class="breadcrumbs__link">{{ $item['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
