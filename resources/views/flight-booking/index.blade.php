<x-app-layout>
    <title>Bayajidda Global - Flight Booking Service</title>
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <h3 class="mb-0 fw-bold">Flight Booking Service</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.1s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--primary-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Pending</p>
                            <h3 class="stats-value mb-0">{{ $statusCounts['pending'] ?? 0 }}</h3>
                            <small class="text-white-50 fs-12 fw-medium">Work on this request, it's Urgent!</small>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-hourglass-empty fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--info-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Processing</p>
                            <h3 class="stats-value mb-0">{{ $statusCounts['processing'] ?? 0 }}</h3>
                            <small class="text-white-50 fs-12 fw-medium">Check and confirm the status</small>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-settings fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.3s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--success-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Resolved</p>
                            <h3 class="stats-value mb-0">{{ $statusCounts['resolved'] ?? 0 }}</h3>
                            <small class="text-white-50 fs-12 fw-medium">You have done a great job</small>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-circle-check fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.4s;">
                <div class="financial-card shadow-sm h-100 p-4" style="background: var(--danger-gradient);">
                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                        <div>
                            <p class="stats-label mb-1" style="color: white;">Rejected</p>
                            <h3 class="stats-value mb-0">{{ $statusCounts['rejected'] ?? 0 }}</h3>
                            <small class="text-white-50 fs-12 fw-medium">Don’t give up — Keep accepting requests</small>
                        </div>
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-3">
                            <i class="ti ti-circle-x fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            :root {
                --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
                --success-gradient: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
                --info-gradient: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
                --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f43f5e 100%);
            }
            .financial-card { position: relative; overflow: hidden; border: none; border-radius: 1rem; color: white; }
            .financial-card::before { content: ''; position: absolute; top: 0; right: 0; width: 150px; height: 150px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; transform: translate(30%, -30%); }
            .financial-card::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; transform: translate(-30%, 30%); }
            .stats-label { font-size: 0.875rem; font-weight: 500; opacity: 0.9; }
            .stats-value { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.025em; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
            .fade-in-up { animation: fadeIn 0.5s ease-out forwards; }
            .avatar-lg { width: 3rem; height: 3rem; display: flex; align-items: center; justify-content: center; }
            .avatar-sm { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; }
            .table > :not(caption) > * > * { padding: 1rem 0.75rem; }
        </style>

        {{-- Search and Filter Form --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 1.25rem;">
                    <div class="card-body p-4 bg-white">
                        <form method="GET" action="{{ route('flight.index') }}">
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted px-3"><i class="ti ti-search fs-18"></i></span>
                                        <input type="text" name="search" class="form-control border-start-0 bg-light py-2" placeholder="Search Reference, Ref, Agent..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <select name="status" class="form-select bg-light py-2" onchange="this.form.submit()">
                                        <option value="">All Statuses</option>
                                        @foreach(['pending', 'processing', 'in-progress', 'resolved', 'successful', 'rejected', 'failed', 'query', 'remark'] as $s)
                                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1 py-2">Filter</button>
                                    @if(request('status') || request('search'))
                                        <a href="{{ route('flight.index') }}" class="btn btn-outline-danger py-2">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="card border-0 shadow-sm" style="border-radius: 1.25rem;">
            <div class="card-header bg-white py-4 border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                    <i class="ti ti-plane-departure me-2 text-primary fs-22"></i>
                    Flight Booking Requests
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase fw-bold">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Agent</th>
                                <th>Reference</th>
                                <th class="text-center">Status</th>
                                <th>Date</th>
                                <th class="pe-4 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                <tr>
                                    <td class="ps-4 text-muted">#{{ $loop->iteration + ($enrollments->currentPage() - 1) * $enrollments->perPage() }}</td>
                                    <td>
                                        <div class="small fw-semibold">{{ $enrollment->performed_by }}</div>
                                        <div class="text-muted small">{{ $enrollment->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $enrollment->reference }}</span></td>
                                    <td class="text-center">
                                        @php
                                            $color = match($enrollment->status) {
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'resolved', 'successful' => 'success',
                                                'rejected', 'failed' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $color }}-subtle text-{{ $color }} px-3 py-2 rounded-pill fw-bold">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ $enrollment->created_at->format('M j, Y') }}</div>
                                        <div class="text-muted small">{{ $enrollment->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('flight.show', $enrollment->id) }}" class="btn btn-icon btn-light btn-sm rounded-circle"><i class="ti ti-eye text-primary"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center py-5 text-muted">No flight bookings found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($enrollments->hasPages())
                <div class="card-footer bg-white py-3">{{ $enrollments->links('vendor.pagination.custom') }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
