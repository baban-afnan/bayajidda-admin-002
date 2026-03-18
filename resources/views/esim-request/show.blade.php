<x-app-layout>
    <title>Bayajidda Global - eSIM Request Details</title>

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
                            <h4 class="mb-0 fw-bold text-primary">eSIM Request Details</h4>
                            <p class="text-muted mb-0 small opacity-75">Ref: <span class="fw-bold">{{ $enrollmentInfo->reference }}</span></p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('esim.index') }}" class="btn btn-light rounded-pill px-4">
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

        {{-- Alerts --}}
        @if (session('errorMessage'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-alert-circle fs-20 me-2"></i>
                    <div>
                        <strong>Error!</strong> {{ session('errorMessage') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('successMessage'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-circle-check fs-20 me-2"></i>
                    <div>
                        <strong>Success!</strong> {{ session('successMessage') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="row">
            <div class="col-lg-8">
                {{-- Enrollment Info --}}
                <x-service-card title="Request Information" icon="ti-info-circle">
                    <x-data-item label="Agent ID" :value="$enrollmentInfo->user_id" icon="ti-user" />
                    <x-data-item label="Amount Charged" :value="'₦' . number_format($enrollmentInfo->amount, 2)" icon="ti-wallet" />
                    <x-data-item label="Service Type" :value="$enrollmentInfo->service_field_name ?? $enrollmentInfo->field_name" icon="ti-sim" />
                    <x-data-item label="Date Submitted" :value="$enrollmentInfo->submission_date?->format('M j, Y • g:i A')" icon="ti-calendar" />
                    
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label text-muted small text-uppercase fw-bold opacity-75">Current Status</label>
                        <div>
                            <span class="badge bg-{{ $enrollmentInfo->status_color }}-subtle text-{{ $enrollmentInfo->status_color }} px-3 py-2 rounded-pill fw-bold text-uppercase" style="font-size: 0.75rem;">
                                {{ $enrollmentInfo->status }}
                            </span>
                        </div>
                    </div>
                </x-service-card>

                {{-- Applicant Info --}}
                <x-service-card title="Applicant Information" icon="ti-user">
                    <x-data-item label="Full Name" :value="($enrollmentInfo->last_name ?? $d['last_name'] ?? '') . ' ' . ($enrollmentInfo->first_name ?? $d['first_name'] ?? '')" />
                    <x-data-item label="Phone" :value="$enrollmentInfo->phone_number ?? $d['phone_number'] ?? ($d['phone'] ?? null)" />
                    <x-data-item label="Email" :value="$enrollmentInfo->email ?? $d['email'] ?? null" />
                    <x-data-item label="Passport Number" :value="$d['passport_number'] ?? ($d['passport_no'] ?? null)" />
                    <x-data-item label="Nationality" :value="$enrollmentInfo->country ?? $d['nationality'] ?? 'Nigerian'" />
                </x-service-card>

                {{-- Description --}}
                @if($enrollmentInfo->description || ($d['description'] ?? null))
                    <x-service-card title="Special Requests / Notes" icon="ti-notes">
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border">
                                <p class="mb-0 small text-secondary italic">"{{ $enrollmentInfo->description ?? $d['description'] }}"</p>
                            </div>
                        </div>
                    </x-service-card>
                @endif

                {{-- Documents --}}
                <x-service-card title="Documents & Uploads" icon="ti-files">
                    <div class="col-12">
                        <div class="d-flex gap-3 flex-wrap">
                            @foreach([
                                'passport_copy' => ['label' => 'International Passport', 'url' => $enrollmentInfo->passport_url ?? ($d['passport_copy'] ?? null)],
                                'nin_slip' => ['label' => 'NIN Slip', 'url' => $enrollmentInfo->nin_slip_url ?? ($d['nin_slip'] ?? null)],
                                'passport_photo' => ['label' => 'Passport Photograph', 'url' => $d['passport_photo'] ?? null],
                                'e-sim_photo' => ['label' => 'eSIM Photo', 'url' => $d['e-sim_photo'] ?? null],
                                'supporting_docs' => ['label' => 'Supporting Documents', 'url' => $d['supporting_docs'] ?? null],
                                'file_url' => ['label' => 'eSIM Copy / Result', 'url' => $enrollmentInfo->file_url ?? ($d['file_url'] ?? null)]
                            ] as $key => $doc)
                                @if($doc['url'])
                                    <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="min-width: 250px; flex: 1;">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-box bg-primary-subtle rounded-circle p-2 me-3">
                                                <i class="ti ti-file-text text-primary"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-bold small">{{ $doc['label'] }}</p>
                                                <span class="text-muted x-small">Document Uploaded</span>
                                            </div>
                                        </div>
                                        <a href="{{ Str::startsWith($doc['url'], 'http') ? $doc['url'] : asset('storage/' . ltrim($doc['url'], '/')) }}" target="_blank" class="btn btn-sm btn-white border shadow-sm rounded-circle p-2">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </x-service-card>

            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    <x-service-card title="Status History" icon="ti-history">
                        <x-status-timeline :history="$statusHistory" />
                    </x-service-card>

                    @if($enrollmentInfo->comment)
                        <div class="card glass-card border-0 animate-slide-up" style="animation-delay: 0.2s">
                            <div class="card-body">
                                <label class="form-label text-muted small text-uppercase fw-bold opacity-75">Admin Remark</label>
                                <p class="mb-0 text-secondary italic small">"{{ $enrollmentInfo->comment }}"</p>
                            </div>
                        </div>
                    @endif
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
                <form method="POST" action="{{ route('esim.update', $enrollmentInfo->id) }}" enctype="multipart/form-data">
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
                            <label class="form-label fw-bold small text-uppercase opacity-75">Attach eSIM / Document</label>
                            <div class="input-group">
                                <input type="file" class="form-control border-0 bg-light" name="file">
                            </div>
                        </div>

                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="force_refund" value="1" id="refundSwitch">
                            <label class="form-check-label text-danger fw-bold small" for="refundSwitch">Process Refund (80% credit)</label>
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

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const select = document.getElementById("status_select");
            const area = document.getElementById("comment_area");

            const msg = {
                pending: "Your eSIM Request is pending review.",
                processing: "We are currently processing your eSIM request.",
                resolved: "✅ Success! Your eSIM has been issued. Check the attachment.",
                rejected: "❌ Rejected. Please check the provided reason.",
                query: "⚠️ Action Required: Please provide more details."
            };

            select.addEventListener("change", () => {
                if(msg[select.value]) area.value = msg[select.value];
            });
        });
    </script>
    @endpush
</x-app-layout>

