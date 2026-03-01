<x-app-layout>
    <title>Bayajidda Global - {{ $title ?? 'Dashboard' }}</title>

    <!-- Announcement Banner -->
    @if(isset($announcement) && $announcement)
    <div class="notification-container mt-3 mb-2">
        <div class="scrolling-text-container bg-primary text-white shadow-sm rounded-3 py-2">
            <div class="scrolling-text">
                <span class="fw-bold me-3"><i class="fas fa-bullhorn"></i> ANNOUNCEMENT:</span>
                {{ $announcement->message }}
            </div>
        </div>
    </div>
    @endif

    <div class="mt-4 fade-in">
        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Wallet Balance -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 glass-card animate-slide-up stat-card-primary rounded-4" style="animation-delay: 0.1s">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-white small text-uppercase fw-bold opacity-75 mb-1" style="letter-spacing: 1px;">Wallet Balance</p>
                                <div class="d-flex align-items-center">
                                    <h3 class="fw-bold mb-0 text-white" id="wallet-balance">₦{{ number_format($totalWalletBalance, 0) }}</h3>
                                    <button class="btn btn-sm p-0 ms-2 text-white opacity-75" id="toggle-balance">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                <i class="ti ti-wallet fs-1 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Credit -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 glass-card animate-slide-up rounded-4" style="animation-delay: 0.2s">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small text-uppercase fw-bold opacity-75 mb-1" style="letter-spacing: 1px;">Today's Credit</p>
                                <h3 class="fw-bold mb-0 text-success">₦{{ number_format($dailyCredit, 0) }}</h3>
                            </div>
                            <div class="bg-success-subtle rounded-circle p-2">
                                <i class="ti ti-arrow-down-left fs-2 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Debit -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 glass-card animate-slide-up rounded-4" style="animation-delay: 0.3s">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small text-uppercase fw-bold opacity-75 mb-1" style="letter-spacing: 1px;">Today's Debit</p>
                                <h3 class="fw-bold mb-0 text-danger">₦{{ number_format($dailyDebit, 0) }}</h3>
                            </div>
                            <div class="bg-danger-subtle rounded-circle p-2">
                                <i class="ti ti-arrow-up-right fs-2 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Total Users -->
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card border-0 glass-card animate-slide-up rounded-4" style="animation-delay: 0.4s">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small text-uppercase fw-bold opacity-75 mb-1" style="letter-spacing: 1px;">Total Users</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalUsers) }}</h3>
                            </div>
                            <div class="bg-primary-subtle rounded-circle p-2">
                                <i class="ti ti-users fs-2 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
             <!-- Alerts -->
             <div class="col-12">
                @include('pages.alart')
            </div>
        </div>

        <!-- Quick Services Section -->
        <div class="card border-0 glass-card animate-slide-up mb-4 overflow-hidden" style="animation-delay: 0.5s">
            <div class="card-header bg-transparent py-4 border-0">
                <h5 class="mb-0 fw-bold text-dark"><i class="ti ti-apps me-2 text-primary"></i>Quick Services</h5>
            </div>
            <div class="card-body pt-0">
                <div class="row g-4 service-grid">
                    @foreach([
                        ['url' => route('bvnmod.index'), 'icon' => 'ti-id', 'label' => 'BVN Mod', 'color' => '#6c5ce7'],
                        ['url' => route('ninmod.index'), 'icon' => 'ti-fingerprint', 'label' => 'NIN Mod', 'color' => '#00b894'],
                        ['url' => route('cac.index'), 'icon' => 'ti-briefcase', 'label' => 'CAC Reg', 'color' => '#0984e3'],
                        ['url' => route('crm.index'), 'icon' => 'ti-headset', 'label' => 'CRM', 'color' => '#00cec9'],
                        ['url' => route('validation.index'), 'icon' => 'ti-shield-check', 'label' => 'Validation', 'color' => '#e17055'],
                        ['url' => route('wallet'), 'icon' => 'ti-wallet', 'label' => 'Wallet', 'color' => '#6c5ce7'],
                    ] as $svc)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                        <a href="{{ $svc['url'] }}" class="service-item text-center text-decoration-none d-block p-3 rounded-4 hover-lift">
                            <div class="service-icon-wrap mb-2 mx-auto shadow-sm rounded-circle d-flex align-items-center justify-content-center" 
                                 style="background: {{ $svc['color'] }}15; color: {{ $svc['color'] }}; width: 60px; height: 60px;">
                                <i class="ti {{ $svc['icon'] }} fs-1"></i>
                            </div>
                            <span class="text-dark fw-bold small">{{ $svc['label'] }}</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Transactions & Statistics Row -->
        <div class="row g-4 mb-5">
            <!-- Recent Transactions -->
            <div class="col-xxl-8 col-xl-7 d-flex">
                <div class="card flex-fill border-0 glass-card animate-slide-up overflow-hidden" style="animation-delay: 0.6s">
                    <div class="card-header bg-transparent py-4 d-flex align-items-center justify-content-between flex-wrap border-0">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="ti ti-receipt-2 me-2 text-primary"></i>
                            @if($isFiltered)
                                Transaction History ({{ $startDate->format('d M') }} - {{ $endDate->format('d M') }})
                            @else
                                Today's Transactions
                            @endif
                        </h5>
                        <a href="{{ route('transactions') }}" class="btn btn-sm btn-primary rounded-pill px-4">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">  
                            <table class="table table-hover table-nowrap mb-0 align-middle">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="ps-4 text-uppercase fs-11 fw-bold text-secondary">#</th>
                                        <th class="text-uppercase fs-11 fw-bold text-secondary">Ref ID</th>
                                        <th class="text-uppercase fs-11 fw-bold text-secondary">Type</th>
                                        <th class="text-uppercase fs-11 fw-bold text-secondary">Amount</th>
                                        <th class="text-uppercase fs-11 fw-bold text-secondary">Date & Time</th>
                                        <th class="pe-4 text-end text-uppercase fs-11 fw-bold text-secondary">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions as $transaction)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="text-muted small">{{ $loop->iteration }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">#{{ substr($transaction->transaction_ref, -8) }}</span>
                                        </td>
                                        <td>
                                            @if($transaction->type == 'credit')
                                                <span class="badge bg-success-subtle text-success border-0 rounded-pill px-3 py-1">
                                                    <i class="ti ti-arrow-down-left me-1"></i>Credit
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border-0 rounded-pill px-3 py-1">
                                                    <i class="ti ti-arrow-up-right me-1"></i>Debit
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction->type == 'credit' ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted small fw-medium">{{ $transaction->created_at->format('d M Y, h:i A') }}</span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            @php
                                                $tStatus = strtolower($transaction->status);
                                                $tColor = match($tStatus) {
                                                    'completed', 'successful', 'success' => 'success',
                                                    'pending' => 'warning',
                                                    default => 'danger'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $tColor }} text-white rounded-pill px-3 fw-bold">{{ ucfirst($tStatus) }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center opacity-50">
                                                <i class="ti ti-receipt-off fs-1 text-muted mb-2"></i>
                                                <p class="text-muted mb-0">No transactions recorded today</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Statistics -->
            <div class="col-xxl-4 col-xl-5 d-none d-xl-flex">
                <div class="card flex-fill border-0 glass-card animate-slide-up overflow-hidden" style="animation-delay: 0.7s">
                    <div class="card-header bg-transparent py-4 border-0">
                        <h5 class="mb-0 fw-bold text-dark">Today's Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-5 d-flex justify-content-center">
                            <div style="height: 220px; width: 220px;">
                                <canvas id="transactionChart" 
                                        data-completed="{{ $completedTransactions }}"
                                        data-pending="{{ $pendingTransactions }}"
                                        data-failed="{{ $failedTransactions }}"></canvas>
                            </div>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <p class="small text-muted mb-0 fw-bold text-uppercase">Total</p>
                                <h2 class="fw-bold text-dark mb-0 font-outfit">{{ $totalTransactions }}</h2>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-4">
                                <div class="p-3 rounded-4 bg-success-subtle text-center border-0">
                                    <h6 class="fw-bold text-success mb-1">{{ $completedPercentage }}%</h6>
                                    <span class="fs-10 text-muted text-uppercase fw-bold">Success</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded-4 bg-warning-subtle text-center border-0">
                                    <h6 class="fw-bold text-warning mb-1">{{ $pendingPercentage }}%</h6>
                                    <span class="fs-10 text-muted text-uppercase fw-bold">Pending</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded-4 bg-danger-subtle text-center border-0">
                                    <h6 class="fw-bold text-danger mb-1">{{ $failedPercentage }}%</h6>
                                    <span class="fs-10 text-muted text-uppercase fw-bold">Failed</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-4 bg-primary bg-opacity-10 rounded-4 d-flex align-items-center justify-content-between border border-primary border-opacity-10">
                            <div>
                                <h4 class="fw-bold text-primary mb-1">₦{{ number_format($totalTransactionAmount, 2) }}</h4>
                                <p class="small text-muted mb-0 fw-bold text-uppercase">Total Volume</p>
                            </div>
                            <a href="{{ route('transactions') }}" class="btn btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  

    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    @endpush
</x-app-layout>
