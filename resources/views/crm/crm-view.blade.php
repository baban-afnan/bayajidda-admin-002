<x-app-layout>
    <title>Bayajidda Global - CRM Service Details</title>

    @php
        $d = $enrollmentInfo->parsed_data;
    @endphp

    <div class="content fade-in">
        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card glass-card border-0 animate-slide-up">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h4 class="mb-0 fw-bold text-primary">CRM Service Details</h4>
                            <p class="text-muted mb-0 small opacity-75">Ref: <span class="fw-bold">{{ $enrollmentInfo->reference }}</span></p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('crm.index') }}" class="btn btn-light rounded-pill px-4">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>
                            <button type="button" class="btn btn-primary rounded-pill px-4 hover-lift" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="ti ti-edit me-1"></i> Update Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="row">
            <div class="col-lg-8">
                {{-- Request Info --}}
                <x-service-card title="Request Information" icon="ti-info-circle">
                    <x-data-item label="Agent ID" :value="$enrollmentInfo->user_id" icon="ti-user" />
                    <x-data-item label="Amount Charged" :value="'₦' . number_format($enrollmentInfo->amount, 2)" icon="ti-wallet" />
                    <x-data-item label="Ticket ID" :value="$enrollmentInfo->ticket_id ?? 'N/A'" icon="ti-ticket" />
                    <x-data-item label="Batch ID" :value="$enrollmentInfo->batch_id ?? 'N/A'" icon="ti-hash" />
                    <x-data-item label="Date Created" :value="$enrollmentInfo->submission_date?->format('M j, Y • g:i A')" icon="ti-calendar" />
                    
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label text-muted small text-uppercase fw-bold opacity-75">Current Status</label>
                        <div>
                            <span class="badge bg-{{ $enrollmentInfo->status_color }}-subtle text-{{ $enrollmentInfo->status_color }} px-3 py-2 rounded-pill fw-bold text-uppercase" style="font-size: 0.75rem;">
                                {{ $enrollmentInfo->status }}
                            </span>
                        </div>
                    </div>
                </x-service-card>

                {{-- Agent Details Section --}}
                @if(!empty($user))
                    <x-service-card title="Agent Details" icon="ti-user-circle">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3 border">
                                @php
                                    $profileImage = !empty($user->profile_photo_url) 
                                        ? (filter_var($user->profile_photo_url, FILTER_VALIDATE_URL) ? $user->profile_photo_url : asset('storage/' . $user->profile_photo_url))
                                        : asset('assets/img/users/user-01.jpg');
                                @endphp
                                <img src="{{ $profileImage }}" class="rounded-circle shadow-sm me-3 border border-2 border-white" width="60" height="60" style="object-fit: cover;">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                    <p class="mb-0 text-muted small">{{ $user->email }}</p>
                                    <span class="badge bg-primary-subtle text-primary x-small mt-1">{{ ucfirst($user->role ?? 'Agent') }}</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-white border ms-auto rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#agentInfoModal">
                                    <i class="ti ti-eye me-1"></i> View Full Profile
                                </button>
                            </div>
                        </div>
                    </x-service-card>
                @endif

                {{-- Latest Comment --}}
                <x-service-card title="Admin Feedback" icon="ti-message-dots">
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 border">
                            <p class="mb-0 small text-secondary italic">"{{ $enrollmentInfo->comment ?? 'No admin feedback provided yet.' }}"</p>
                        </div>
                    </div>
                </x-service-card>

                {{-- Documents --}}
                @if($enrollmentInfo->file_url)
                    <x-service-card title="Attached Documents" icon="ti-files">
                        <div class="col-12">
                            <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-primary-subtle rounded-circle p-2 me-3">
                                        <i class="ti ti-file-description text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold small">Service Document</p>
                                        <span class="text-muted x-small">Uploaded by Admin</span>
                                    </div>
                                </div>
                                <a href="{{ $enrollmentInfo->file_url }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-4">
                                    <i class="ti ti-download me-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </x-service-card>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    <x-service-card title="Service History" icon="ti-history">
                        <x-status-timeline :history="$statusHistory" />
                    </x-service-card>
                </div>
            </div>
        </div>
    </div>

    {{-- Update Status Modal --}}
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 glass-card" style="border-radius: 1.5rem;">
                <div class="modal-header bg-primary text-white py-4" style="border-radius: 1.5rem 1.5rem 0 0;">
                    <h5 class="modal-title fw-bold">
                        <i class="ti ti-edit me-2"></i>Update Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('crm.update', $enrollmentInfo->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase opacity-75">Target Status</label>
                            <select class="form-select border-0 bg-light p-3 rounded-3" name="status" id="status_select">
                                @foreach(['pending', 'processing', 'in-progress', 'resolved', 'successful', 'query', 'rejected', 'failed', 'remark'] as $s)
                                    <option value="{{ $s }}" {{ $enrollmentInfo->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase opacity-75">Comment / Feedback</label>
                            <textarea class="form-control border-0 bg-light p-3 rounded-3" name="comment" id="comment_area" rows="4">{{ $enrollmentInfo->comment }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase opacity-75">Attach Document</label>
                            <div class="input-group">
                                <input type="file" class="form-control border-0 bg-light" name="file">
                            </div>
                        </div>

                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="force_refund" value="1" id="refundSwitch">
                            <label class="form-check-label text-danger fw-bold small" for="refundSwitch" title="Check this ONLY if you need to credit the user again manually.">Force Refund Process</label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-4" style="border-radius: 0 0 1.5rem 1.5rem;">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Agent Info Modal --}}
    @if(!empty($user))
        <div class="modal fade" id="agentInfoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 glass-card" style="border-radius: 1.5rem;">
                    <div class="modal-header bg-primary text-white py-4" style="border-radius: 1.5rem 1.5rem 0 0;">
                        <h5 class="modal-title fw-bold">
                            <i class="ti ti-user-circle me-2"></i>Agent Profile
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <img src="{{ $profileImage }}" class="rounded-circle shadow border border-4 border-white mb-3" width="120" height="120" style="object-fit: cover;">
                        <h4 class="fw-bold text-dark mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                        <p class="text-muted mb-4">{{ $user->email }}</p>

                        <div class="text-start space-y-3">
                            <div class="p-3 bg-light rounded-3">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Phone Number</label>
                                <span class="fw-bold">{{ $user->phone_no ?? 'N/A' }}</span>
                            </div>
                            <div class="p-3 bg-light rounded-3">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Registration Date</label>
                                <span class="fw-bold">{{ $user->created_at?->format('F j, Y') ?? 'N/A' }}</span>
                            </div>
                            <div class="p-3 bg-light rounded-3">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Office Address</label>
                                <span class="fw-bold">{{ $user->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 py-4 justify-content-center">
                        <button type="button" class="btn btn-light rounded-pill px-5" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const select = document.getElementById("status_select");
            const area = document.getElementById("comment_area");

            const msg = {
                pending: "Your request is pending review by our CRM team.",
                processing: "We are currently processing your CRM service request.",
                resolved: "✅ Success! Your request has been successfully treated. Check any attached documents.",
                rejected: "❌ Rejected. We could not fulfill this request based on the information provided.",
                query: "⚠️ Action Required: Additional clarification is needed for your request."
            };

            select.addEventListener("change", () => {
                if(msg[select.value]) area.value = msg[select.value];
            });
        });
    </script>
    @endpush
</x-app-layout>
