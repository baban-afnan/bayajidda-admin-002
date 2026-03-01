@props(['status', 'label', 'value', 'icon' => null])

<div {{ $attributes->merge(['class' => 'col-md-6 col-lg-4']) }}>
    <label class="form-label text-muted small text-uppercase fw-bold opacity-75" style="letter-spacing: 0.5px;">{{ $label }}</label>
    <div class="d-flex align-items-center">
        @if($icon)
            <i class="ti {{ $icon }} text-primary me-2 fs-18"></i>
        @endif
        <p class="fw-semibold text-dark mb-0 fs-15">{{ $value ?: 'N/A' }}</p>
    </div>
</div>
