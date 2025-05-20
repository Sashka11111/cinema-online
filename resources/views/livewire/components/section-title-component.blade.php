<div class="section-title">
    <div class="section-title__content">
        <h2 class="section-title__heading">{{ $title }}</h2>
        @if($subtitle)
            <p class="section-title__subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if($showAllLink)
        <a href="{{ $showAllLink }}" class="section-title__link">
            Дивитися всі <i class="fas fa-chevron-right"></i>
        </a>
    @endif
</div>