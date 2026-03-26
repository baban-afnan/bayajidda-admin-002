<x-app-layout>
    <title>Bayajidda Global - Educational Pins</title>

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
                            <h3 class="page-title text-primary mb-1 fw-bold">Educational Pins</h3>
                            <ul class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item text-muted">Other Services</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center mt-2 mt-md-0 d-flex gap-2 justify-content-end align-items-center">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addPinModal">
                        <i class="ti ti-plus me-1"></i>Add New Pin
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.educational-pin.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Exam Name</label>
                        <select name="exam_name" class="form-select">
                            <option value="">All Exams</option>
                            @foreach($examNames as $exam)
                                <option value="{{ $exam }}" {{ request('exam_name') == $exam ? 'selected' : '' }}>{{ $exam }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Transaction Ref</label>
                        <input type="text" name="transaction_ref" class="form-control" placeholder="Ref Number..." value="{{ request('transaction_ref') }}">
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
                        <a href="{{ route('admin.educational-pin.index') }}" class="btn btn-light flex-fill">
                            <i class="ti ti-rotate me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pins Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="ti ti-list-details me-2 text-primary"></i>Available Pins</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-soft">
                            <tr>
                                <th class="ps-4 border-0">S/N</th>
                                <th class="border-0">Transaction Ref</th>
                                <th class="border-0">Exam Name</th>
                                <th class="border-0">Quantity</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Status</th>
                                <th class="text-end pe-4 border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pins as $pin)
                                <tr class="border-bottom-soft">
                                    <td class="ps-4">
                                        <span class="fw-semibold text-muted">{{ $pins->firstItem() + $loop->index }}</span>
                                    </td>
                                    <td>
                                        <code class="text-info bg-soft-info px-2 py-1 rounded">{{ $pin->transaction_ref }}</code>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-soft-primary text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-book fs-16"></i>
                                            </div>
                                            <span class="fw-bold text-dark">{{ $pin->exam_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-dark text-dark border-0 px-3 py-2">
                                            <i class="ti ti-numbers me-1"></i>{{ $pin->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-success fs-15">₦{{ number_format($pin->amount, 2) }}</span>
                                            <small class="text-muted" style="font-size: 10px;">Amount</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($pin->status === 'enabled')
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
                                            <button class="btn btn-icon btn-sm btn-soft-info rounded-circle edit-pin-btn shadow-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editPinModal"
                                                    data-id="{{ $pin->id }}"
                                                    data-transaction_ref="{{ $pin->transaction_ref }}"
                                                    data-exam_name="{{ $pin->exam_name }}"
                                                    data-quantity="{{ $pin->quantity }}"
                                                    data-pins="{{ $pin->pins }}"
                                                    data-amount="{{ $pin->amount }}"
                                                    data-status="{{ $pin->status }}"
                                                    title="Edit Pin">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.educational-pin.destroy', $pin->id) }}" method="POST" class="delete-pin-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-icon btn-sm btn-soft-danger rounded-circle delete-pin-btn shadow-sm" title="Delete Pin">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="ti ti-package-off fs-1 mb-2 text-muted"></i>
                                            <p class="text-muted fw-medium">No educational pins found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($pins->hasPages())
                <div class="card-footer bg-white border-top-0 py-4">
                    {{ $pins->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Pin Modal -->
    <div class="modal fade" id="addPinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Add New Educational Pin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.educational-pin.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Transaction Reference</label>
                            <input type="text" name="transaction_ref" class="form-control" required placeholder="e.g. TXN123456">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Exam Name</label>
                            <input type="text" name="exam_name" class="form-control" required placeholder="e.g. WAEC, NECO, JAMB">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Quantity</label>
                            <input type="number" name="quantity" class="form-control" required min="1" placeholder="Number of pins">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pins (Comma Separated)</label>
                            <textarea name="pins" class="form-control" rows="3" placeholder="PIN1, PIN2, PIN3..."></textarea>
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

    <!-- Edit Pin Modal -->
    <div class="modal fade" id="editPinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Edit Educational Pin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPinForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Transaction Reference</label>
                            <input type="text" name="transaction_ref" id="edit_transaction_ref" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Exam Name</label>
                            <input type="text" name="exam_name" id="edit_exam_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Quantity</label>
                            <input type="number" name="quantity" id="edit_quantity" class="form-control" required min="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pins (Comma Separated)</label>
                            <textarea name="pins" id="edit_pins" class="form-control" rows="3"></textarea>
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
            const editButtons = document.querySelectorAll('.edit-pin-btn');
            const editForm = document.getElementById('editPinForm');
            
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    editForm.action = `/admin/educational-pin/${id}/update`;
                    
                    document.getElementById('edit_transaction_ref').value = this.dataset.transaction_ref;
                    document.getElementById('edit_exam_name').value = this.dataset.exam_name;
                    document.getElementById('edit_quantity').value = this.dataset.quantity;
                    document.getElementById('edit_pins').value = this.dataset.pins;
                    document.getElementById('edit_amount').value = this.dataset.amount;
                    document.getElementById('edit_status').checked = this.dataset.status === 'enabled';
                });
            });

            // Confirmation for Delete
            const deleteButtons = document.querySelectorAll('.delete-pin-btn');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.closest('.delete-pin-form');
                    Swal.fire({
                        title: 'Delete Pin?',
                        text: "Are you sure you want to delete this educational pin? This action cannot be undone.",
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
                        title: 'Update Pin?',
                        text: "Are you sure you want to update this educational pin?",
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
