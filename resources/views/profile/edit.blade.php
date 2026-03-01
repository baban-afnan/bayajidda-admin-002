<x-app-layout>
    <title>Bayajidda Global - Profile Management</title>

    <div class="content fade-in">
        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card glass-card border-0 animate-slide-up">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h4 class="mb-0 fw-bold text-primary">Account Management</h4>
                            <p class="text-muted mb-0 small opacity-75">View and update your personal security settings</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-light rounded-pill px-4">
                                <i class="ti ti-smart-home me-1"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Left Column: Profile Summary --}}
            <div class="col-lg-4">
                <div class="card glass-card border-0 mb-4 text-center overflow-hidden">
                    <div class="card-header border-0 py-5 bg-primary-gradient position-relative">
                        <div class="position-absolute w-100 h-100 top-0 start-0 opacity-10" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                    </div>
                    <div class="card-body pt-0 position-relative" style="margin-top: -60px;">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="profile-photo-wrapper">
                                @php
                                    $photo = $user->photo ? asset($user->photo) : asset('assets/img/profiles/avatar-01.jpg');
                                @endphp
                                <img src="{{ $photo }}" class="rounded-circle shadow-lg border border-4 border-white bg-white" width="120" height="120" style="object-fit: cover;">
                                <button class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0 p-2 shadow" data-bs-toggle="modal" data-bs-target="#photoModal">
                                    <i class="ti ti-camera"></i>
                                </button>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                        <p class="text-muted small mb-4">{{ $user->email }}</p>

                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 border border-primary-subtle small fw-bold">
                                {{ strtoupper($user->role ?? 'Agent') }}
                            </span>
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle small fw-bold">
                                ₦{{ number_format($user->limit, 2) }} LIMIT
                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                                <span class="text-muted small fw-bold text-uppercase opacity-75">Phone</span>
                                <span class="fw-bold small">{{ $user->phone_no }}</span>
                            </div>
                            <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                                <span class="text-muted small fw-bold text-uppercase opacity-75">Business</span>
                                <span class="fw-bold small">{{ $user->business_name ?? 'Individual' }}</span>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <button class="btn btn-outline-primary w-100 rounded-pill py-2 small fw-bold hover-lift" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                    <i class="ti ti-lock me-1"></i> Password
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-danger w-100 rounded-pill py-2 small fw-bold hover-lift" data-bs-toggle="modal" data-bs-target="#pinModal">
                                    <i class="ti ti-key me-1"></i> Trans PIN
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <x-service-card title="System Access" icon="ti-settings">
                    <div class="col-12">
                        <div class="p-3 bg-primary-subtle border border-primary-subtle rounded-3 mb-3">
                            <h6 class="fw-bold text-primary mb-1 small">KYC Level 2</h6>
                            <p class="text-primary small mb-2 opacity-75">Verify your account to increase daily limits.</p>
                            <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#forceProfileModal">Verify Now</button>
                        </div>
                        <div class="p-3 bg-light border rounded-3 text-center">
                            <p class="text-muted x-small mb-0">Member since {{ $user->created_at?->format('F Y') }}</p>
                        </div>
                    </div>
                </x-service-card>
            </div>

            {{-- Right Column: Detailed Info --}}
            <div class="col-lg-8">
                <x-service-card title="Identity Details" icon="ti-user-circle">
                    <x-data-item label="First Name" :value="$user->first_name" icon="ti-id" />
                    <x-data-item label="Middle Name" :value="$user->middle_name ?? 'N/A'" icon="ti-id" />
                    <x-data-item label="Last Name" :value="$user->last_name" icon="ti-id" />
                    <x-data-item label="Email Address" :value="$user->email" icon="ti-mail" />
                    <x-data-item label="Phone Number" :value="$user->phone_no" icon="ti-phone" />
                    <x-data-item label="State" :value="$user->state ?? 'N/A'" icon="ti-map-pin" />
                    <x-data-item label="LGA" :value="$user->lga ?? 'N/A'" icon="ti-building-community" />
                    
                    <div class="col-12">
                        <hr class="opacity-10 my-2">
                    </div>

                    <div class="col-12">
                        <label class="form-label text-muted small text-uppercase fw-bold opacity-75">Office / Residential Address</label>
                        <div class="p-3 bg-light rounded-3 border">
                            <p class="mb-0 fw-medium small">{{ $user->address ?? 'No address provided in profile' }}</p>
                        </div>
                    </div>
                </x-service-card>

                <x-service-card title="Account Activity" icon="ti-activity">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-white hover-shadow transition-all">
                            <div class="d-flex align-items-center mb-1">
                                <i class="ti ti-calendar me-2 text-primary opacity-75"></i>
                                <small class="text-muted text-uppercase fw-bold x-small">Date Joined</small>
                            </div>
                            <div class="fw-bold">{{ $user->created_at?->format('d M, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-white hover-shadow transition-all">
                            <div class="d-flex align-items-center mb-1">
                                <i class="ti ti-clock me-2 text-primary opacity-75"></i>
                                <small class="text-muted text-uppercase fw-bold x-small">Last Updated</small>
                            </div>
                            <div class="fw-bold">{{ $user->updated_at?->diffForHumans() }}</div>
                        </div>
                    </div>
                </x-service-card>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    {{-- Photo Update Modal --}}
    <div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 glass-card" style="border-radius: 1.5rem;">
                <div class="modal-header bg-primary text-white py-4" style="border-radius: 1.5rem 1.5rem 0 0;">
                    <h5 class="modal-title fw-bold">
                        <i class="ti ti-camera me-2"></i>Update Profile Photo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 text-center">
                        <div class="upload-zone p-5 border-2 border-dashed rounded-4 mb-4 bg-light hover-primary-subtle cursor-pointer" onclick="document.getElementById('photoInput').click()">
                            <div id="uploadPlaceholder">
                                <i class="ti ti-cloud-upload text-primary display-4 mb-2"></i>
                                <h6 class="fw-bold">Click to Upload</h6>
                                <p class="text-muted small">PNG, JPG or WEBP (Max 2MB)</p>
                            </div>
                            <img id="photoPreview" src="#" class="d-none rounded-3 shadow-sm mx-auto" style="max-width: 151px; max-height: 151px; object-fit: cover;">
                            <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*" onchange="previewProfilePhoto(this)">
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-4" style="border-radius: 0 0 1.5rem 1.5rem;">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Save Photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Password Update Modal --}}
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 glass-card" style="border-radius: 1.5rem;">
                <div class="modal-header bg-primary text-white py-4" style="border-radius: 1.5rem 1.5rem 0 0;">
                    <h5 class="modal-title fw-bold">
                        <i class="ti ti-lock me-2"></i>Change Password
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase opacity-75">Current Password</label>
                            <input type="password" name="current_password" class="form-control border-0 bg-light p-3 rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase opacity-75">New Password</label>
                            <input type="password" name="password" class="form-control border-0 bg-light p-3 rounded-3" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-uppercase opacity-75">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control border-0 bg-light p-3 rounded-3" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-4" style="border-radius: 0 0 1.5rem 1.5rem;">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- PIN Modal --}}
    <div class="modal fade" id="pinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 glass-card" style="border-radius: 1.5rem;">
                <div class="modal-header bg-danger text-white py-4" style="border-radius: 1.5rem 1.5rem 0 0;">
                    <h5 class="modal-title fw-bold">
                        <i class="ti ti-key me-2"></i>Transaction PIN
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('profile.pin') }}">
                    @csrf
                    <div class="modal-body p-4 text-center">
                        <div class="alert bg-danger-subtle text-danger border-0 rounded-3 mb-4 small fw-bold">
                            <i class="ti ti-alert-triangle me-1"></i> Never share your 5-digit PIN with anyone!
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold small text-uppercase opacity-75">Login Password</label>
                            <input type="password" name="current_password" class="form-control border-0 bg-light p-3 rounded-3" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-6 text-start">
                                <label class="form-label fw-bold small text-uppercase opacity-75">New 5-Digit PIN</label>
                                <input type="password" name="pin" maxlength="5" class="form-control border-0 bg-light p-3 rounded-3 text-center fw-bold fs-4 tracking-widest" required>
                            </div>
                            <div class="col-6 text-start">
                                <label class="form-label fw-bold small text-uppercase opacity-75">Confirm PIN</label>
                                <input type="password" name="pin_confirmation" maxlength="5" class="form-control border-0 bg-light p-3 rounded-3 text-center fw-bold fs-4 tracking-widest" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-4" style="border-radius: 0 0 1.5rem 1.5rem;">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Securely Set PIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewProfilePhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('uploadPlaceholder').classList.add('d-none');
                    const preview = document.getElementById('photoPreview');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush

    @push('styles')
    <style>
        .bg-primary-gradient {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #1a8a9a 100%);
        }
        .upload-zone {
            transition: all 0.3s ease;
        }
        .upload-zone:hover {
            border-color: var(--bs-primary) !important;
            background-color: var(--bs-primary-bg-subtle) !important;
        }
        .hover-shadow:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-color: var(--bs-primary) !important;
        }
        .tracking-widest { letter-spacing: 0.5rem; }
        .profile-photo-wrapper {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .profile-photo-wrapper:hover {
            transform: scale(1.05);
        }
    </style>
    @endpush
</x-app-layout>


