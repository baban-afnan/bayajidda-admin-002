@props(['history'])

<div class="timeline ps-3">
    @forelse ($history as $item)
        @php
            $status = $item['status'] ?? 'pending';
            $color = match($status) {
                'pending' => 'warning',
                'processing' => 'info',
                'in-progress' => 'primary',
                'resolved', 'successful' => 'success',
                'rejected', 'failed' => 'danger',
                'query' => 'warning',
                'remark' => 'secondary',
                default => 'secondary'
            };
        @endphp
        <div class="timeline-item pb-4 border-start ps-4 position-relative">
            <span class="position-absolute top-0 start-0 translate-middle p-2 bg-{{ $color }} border border-3 border-white rounded-circle shadow-sm" style="margin-left: -1px; width: 14px; height: 14px;"></span>
            
            <div class="mb-1 animate-slide-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <span class="badge bg-{{ $color }}-subtle text-{{ $color }} mb-1 rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 0.7rem;">
                    {{ $status }}
                </span>
                <span class="text-muted small d-block opacity-75">
                    <i class="ti ti-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($item['submission_date'])->format('M j, Y • g:i A') }}
                </span>
            </div>
            
            @if (!empty($item['comment']))
                <div class="bg-light p-3 rounded-3 small text-secondary mt-2 border-0 shadow-sm italic" style="border-left: 3px solid var(--bs-{{ $color }}) !important;">
                    "{{ $item['comment'] }}"
                </div>
            @endif
        </div>
    @empty
        <div class="text-center py-5 text-muted opacity-50">
            <i class="ti ti-clock-off fs-1 mb-2 d-block"></i>
            No history recorded
        </div>
    @endforelse
</div>
