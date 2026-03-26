<x-app-layout>
    <title>Bayajidda Global - Cable Subscription</title>

    <div class="content">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-icon btn-sm btn-light rounded-circle me-3">
                            <i class="ti ti-arrow-left"></i>
                        </a>
                        <div>
                            <h3 class="page-title text-primary mb-1 fw-bold">Cable Subscriptions</h3>
                            <ul class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item text-muted">Other Services</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center mt-2 mt-md-0 d-flex gap-2 justify-content-end align-items-center">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addSubscriptionModal">
                        <i class="ti ti-plus me-1"></i>Add New Subscription
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.cable-subscription.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Cable Name</label>
                        <select name="cablename" class="form-select">
                            <option value="">All Cables</option>
                            @foreach($cablenames as $cable)
                                <option value="{{ $cable }}" {{ request('cablename') == $cable ? 'selected' : '' }}>{{ $cable }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Smart Card</label>
                        <input type="text" name="smart_card_number" class="form-control" placeholder="Card Number..." value="{{ request('smart_card_number') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="enabled" {{ request('status') == 'enabled' ? 'selected' : '' }}>Active</option>
                            <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ti ti-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.cable-subscription.index') }}" class="btn btn-light flex-fill">
                            <i class="ti ti-rotate me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Subscriptions Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="ti ti-list-details me-2 text-primary"></i>Cable Subscriptions</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-soft">
                            <tr>
                                <th class="ps-4 border-0">S/N</th>
                                <th class="border-0">Transaction Ref</th>
                                <th class="border-0">Cable Name</th>
                                <th class="border-0">Smart Card</th>
                                <th class="border-0">Plan</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Status</th>
                                <th class="text-end pe-4 border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $subscription)
                                <tr class="border-bottom-soft">
                                    <td class="ps-4">
                                        <span class="fw-semibold text-muted">{{ $subscriptions->firstItem() + $loop->index }}</span>
                                    </td>
                                    <td>
                                        <code class="text-info bg-soft-info px-2 py-1 rounded">{{ $subscription->transaction_ref }}</code>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-soft-primary text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-tv fs-16"></i>
                                            </div>
                                            <span class="fw-bold text-dark">{{ $subscription->cablename }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-dark text-dark border-0 px-3 py-2">
                                            <i class="ti ti-credit-card me-1"></i>{{ $subscription->smart_card_number }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted"><i class="ti ti-files me-1"></i>{{ $subscription->cableplan }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-success fs-15">₦{{ number_format($subscription->amount, 2) }}</span>
                                            <small class="text-muted" style="font-size: 10px;">Amount</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subscription->status === 'enabled')
                                            <span class="badge bg-soft-success text-success border-0 rounded-pill px-3">
                                                <i class="ti ti-circle-check-filled me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger border-0 rounded-pill px-3">
                                                <i class="ti ti-circle-x-filled me-1"></i>Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-icon btn-sm btn-soft-info rounded-circle edit-subscription-btn shadow-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editSubscriptionModal"
                                                    data-id="{{ $subscription->id }}"
                                                    data-transaction_ref="{{ $subscription->transaction_ref }}"
                                                    data-cablename="{{ $subscription->cablename }}"
                                                    data-cableplan="{{ $subscription->cableplan }}"
                                                    data-smart_card_number="{{ $subscription->smart_card_number }}"
                                                    data-amount="{{ $subscription->amount }}"
                                                    data-status="{{ $subscription->status }}"
                                                    title="Edit Subscription">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.cable-subscription.destroy', $subscription->id) }}" method="POST" class="delete-subscription-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-icon btn-sm btn-soft-danger rounded-circle delete-subscription-btn shadow-sm" title="Delete Subscription">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="ti ti-package-off fs-1 mb-2 text-muted"></i>
                                            <p class="text-muted fw-medium">No cable subscriptions found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($subscriptions->hasPages())
                <div class="card-footer bg-white border-top-0 py-4">
                    {{ $subscriptions->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Subscription Modal -->
    <div class="modal fade" id="addSubscriptionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Add New Cable Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.cable-subscription.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Transaction Reference</label>
                            <input type="text" name="transaction_ref" class="form-control" required placeholder="e.g. TXN123456">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cable Name</label>
                            <input type="text" name="cablename" class="form-control" required placeholder="e.g. DStv, StarTimes">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cable Plan</label>
                            <input type="text" name="cableplan" class="form-control" required placeholder="e.g. Premium, Basic">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Smart Card Number</label>
                            <input type="text" name="smart_card_number" class="form-control" required placeholder="Card Number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount (₦)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required placeholder="0.00">
                        </div>
                        <div class="mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="addStatus" checked>
                                <label class="form-check-label" for="addStatus">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Subscription Modal -->
    <div class="modal fade" id="editSubscriptionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Edit Cable Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editSubscriptionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Transaction Reference</label>
                            <input type="text" name="transaction_ref" id="edit_transaction_ref" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cable Name</label>
                            <input type="text" name="cablename" id="edit_cablename" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cable Plan</label>
                            <input type="text" name="cableplan" id="edit_cableplan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Smart Card Number</label>
                            <input type="text" name="smart_card_number" id="edit_smart_card_number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount (₦)</label>
                            <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control" required>
                        </div>
                        <div class="mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="edit_status">
                                <label class="form-check-label" for="edit_status">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SweetAlert Session Handlers --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: true,
                    confirmButtonColor: '#0d5c3e',
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    showConfirmButton: true,
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-subscription-btn');
            const editForm = document.getElementById('editSubscriptionForm');
            
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    editForm.action = `/admin/cable-subscription/${id}/update`;
                    
                    document.getElementById('edit_transaction_ref').value = this.dataset.transaction_ref;
                    document.getElementById('edit_cablename').value = this.dataset.cablename;
                    document.getElementById('edit_cableplan').value = this.dataset.cableplan;
                    document.getElementById('edit_smart_card_number').value = this.dataset.smart_card_number;
                    document.getElementById('edit_amount').value = this.dataset.amount;
                    document.getElementById('edit_status').checked = this.dataset.status === 'enabled';
                });
            });

            // Confirmation for Delete
            const deleteButtons = document.querySelectorAll('.delete-subscription-btn');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.closest('.delete-subscription-form');
                    Swal.fire({
                        title: 'Delete Subscription?',
                        text: "Are you sure you want to delete this cable subscription? This action cannot be undone.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Confirmation for Edit
            if(editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Update Subscription?',
                        text: "Are you sure you want to update this cable subscription?",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#0d5c3e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, update!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            editForm.submit();
                        }
                    });
                });
            }
        });
    </script>

    <style>
        .bg-light-soft { background-color: #f8f9fa; }
        .border-bottom-soft { border-bottom: 1px solid rgba(0,0,0,0.05); }
        .bg-soft-primary { background-color: rgba(99, 102, 241, 0.1); }
        .bg-soft-success { background-color: rgba(34, 197, 94, 0.1); }
        .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1); }
        .bg-soft-info { background-color: rgba(9, 180, 214, 0.1); }
        .bg-soft-dark { background-color: rgba(33, 37, 41, 0.1); }
        .fs-15 { font-size: 15px; }
        .fs-16 { font-size: 16px; }
    </style>
</x-app-layout>
