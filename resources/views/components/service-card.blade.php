@props(['title', 'icon' => 'ti-info-circle', 'color' => 'primary'])

<div {{ $attributes->merge(['class' => 'card glass-card border-0 mb-4 animate-slide-up']) }}>
    <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0 fw-bold">
            <i class="ti {{ $icon }} me-2 text-{{ $color }}"></i>{{ $title }}
        </h5>
        @isset($action)
            {{ $action }}
        @endisset
    </div>
    <div class="card-body">
        <div class="row g-4">
            {{ $slot }}
        </div>
    </div>
</div>
