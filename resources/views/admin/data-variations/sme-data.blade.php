<x-app-layout>
    <title>Bayajidda Global - Data Services</title>

    <div class="content">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.data-variations.index') }}" class="btn btn-icon btn-sm btn-light rounded-circle me-3">
                            <i class="ti ti-arrow-left"></i>
                        </a>
                        <div>
                            <h3 class="page-title text-primary mb-1 fw-bold">SME Data Plans</h3>
                            <ul class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item text-muted">Data Services</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center mt-2 mt-md-0 d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addPlanModal">
                        <i class="ti ti-plus me-1"></i>Add New Plan
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.sme-data.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Network</label>
                        <select name="network" class="form-select">
                            <option value="">All Networks</option>
                            @foreach($networks as $network)
                                <option value="{{ $network }}" {{ request('network') == $network ? 'selected' : '' }}>{{ $network }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Plan ID</label>
                        <input type="text" name="data_id" class="form-control" placeholder="Search ID..." value="{{ request('data_id') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Plan Size</label>
                        <input type="text" name="size" class="form-control" placeholder="e.g. 1.0 GB" value="{{ request('size') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Validity</label>
                        <input type="text" name="validity" class="form-control" placeholder="e.g. 30 Days" value="{{ request('validity') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ti ti-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.sme-data.index') }}" class="btn btn-light flex-fill">
                            <i class="ti ti-rotate me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Variations Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="ti ti-list-details me-2 text-primary"></i>Available SME Plans</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-soft">
                            <tr>
                                <th class="ps-4 border-0">S/N</th>
                                <th class="border-0">Network</th>
                                <th class="border-0">Plan ID</th>
                                <th class="border-0">Plan Size</th>
                                <th class="border-0">Validity</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Status</th>
                                <th class="text-end pe-4 border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($variations as $variation)
                                <tr class="border-bottom-soft">
                                    <td class="ps-4">
                                        <span class="fw-semibold text-muted">{{ $variations->firstItem() + $loop->index }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-soft-primary text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-broadcast fs-16"></i>
                                            </div>
                                            <span class="fw-bold text-dark">{{ $variation->network }}</span>
                                        </div>
                                    </td>
                                    <td><code class="text-info bg-soft-info px-2 py-1 rounded">{{ $variation->data_id }}</code></td>
                                    <td>
                                        <span class="badge bg-soft-dark text-dark border-0 px-3 py-2">
                                            <i class="ti ti-database me-1"></i>{{ $variation->size }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted"><i class="ti ti-calendar-time me-1"></i>{{ $variation->validity }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-success fs-15">₦{{ number_format($variation->amount, 2) }}</span>
                                            <small class="text-muted" style="font-size: 10px;">Base Price</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($variation->status)
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
                                            <button class="btn btn-icon btn-sm btn-soft-info rounded-circle edit-plan-btn shadow-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editPlanModal"
                                                    data-id="{{ $variation->id }}"
                                                    data-network="{{ $variation->network }}"
                                                    data-data_id="{{ $variation->data_id }}"
                                                    data-plan_type="{{ $variation->plan_type }}"
                                                    data-size="{{ $variation->size }}"
                                                    data-validity="{{ $variation->validity }}"
                                                    data-amount="{{ $variation->amount }}"
                                                    data-status="{{ $variation->status }}"
                                                    title="Edit Plan">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.sme-data.destroy', $variation->id) }}" method="POST" class="delete-plan-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-icon btn-sm btn-soft-danger rounded-circle delete-plan-btn shadow-sm" title="Delete Plan">
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
                                            <p class="text-muted fw-medium">No SME plans found. Click "Pull & Update" to fetch from API.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($variations->hasPages())
                <div class="card-footer bg-white border-top-0 py-4">
                    {{ $variations->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Plan Modal -->
    <div class="modal fade" id="addPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Add New SME Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.sme-data.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Network</label>
                                <select name="network" class="form-select" required>
                                    <option value="MTN">MTN</option>
                                    <option value="AIRTEL">AIRTEL</option>
                                    <option value="GLO">GLO</option>
                                    <option value="9MOBILE">9MOBILE</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Plan ID (External)</label>
                                <input type="text" name="data_id" class="form-control" required placeholder="e.g. 1000">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Plan Type</label>
                                <input type="text" name="plan_type" class="form-control" required value="SME">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Amount (₦)</label>
                                <input type="number" step="0.01" name="amount" class="form-control" required placeholder="0.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Size</label>
                                <input type="text" name="size" class="form-control" required placeholder="e.g. 1.0GB">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Validity</label>
                                <input type="text" name="validity" class="form-control" required placeholder="e.g. 30 Days">
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="addStatus" checked value="1">
                                <label class="form-check-label" for="addStatus">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Create Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Plan Modal -->
    <div class="modal fade" id="editPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Edit SME Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPlanForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Network</label>
                                <select name="network" id="edit_network" class="form-select" required>
                                    <option value="MTN">MTN</option>
                                    <option value="AIRTEL">AIRTEL</option>
                                    <option value="GLO">GLO</option>
                                    <option value="9MOBILE">9MOBILE</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Plan ID</label>
                                <input type="text" name="data_id" id="edit_data_id" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Plan Type</label>
                                <input type="text" name="plan_type" id="edit_plan_type" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Amount (₦)</label>
                                <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Size</label>
                                <input type="text" name="size" id="edit_size" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Validity</label>
                                <input type="text" name="validity" id="edit_validity" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="edit_status" value="1">
                                <label class="form-check-label" for="edit_status">Active Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Update Plan</button>
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
            const editButtons = document.querySelectorAll('.edit-plan-btn');
            const editForm = document.getElementById('editPlanForm');
            
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    editForm.action = `/admin/sme-data/${id}/update`;
                    
                    document.getElementById('edit_network').value = this.dataset.network;
                    document.getElementById('edit_data_id').value = this.dataset.data_id;
                    document.getElementById('edit_plan_type').value = this.dataset.plan_type;
                    document.getElementById('edit_size').value = this.dataset.size;
                    document.getElementById('edit_validity').value = this.dataset.validity;
                    document.getElementById('edit_amount').value = this.dataset.amount;
                    document.getElementById('edit_status').checked = this.dataset.status == 1;
                });
            });

            // Confirmation for Sync
            const syncForm = document.getElementById('syncForm');
            if(syncForm) {
                syncForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Sync Plans?',
                        text: "This will pull the latest SME data plans from the API and update your database. Continue?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0ea5e9',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, sync now!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Syncing...',
                                text: 'Please wait while we fetch the latest plans.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            syncForm.submit();
                        }
                    });
                });
            }

            // Confirmation for Delete
            const deleteButtons = document.querySelectorAll('.delete-plan-btn');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.closest('.delete-plan-form');
                    Swal.fire({
                        title: 'Delete Plan?',
                        text: "Are you sure you want to delete this SME plan? This action cannot be undone.",
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
                        title: 'Update Amount?',
                        text: "Are you sure you want to update the amount for this plan?",
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
