<x-app-layout>
    {{-- HEADER SECTION: Judul Halaman & Breadcrumb --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-body mb-1" style="letter-spacing: -0.5px;">Account Settings</h2>
                <p class="text-secondary small mb-0">Kelola informasi pribadi dan keamanan akun Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="row g-4 justify-content-center">
            
            {{-- KOLOM KIRI: SIDEBAR NAVIGASI & AVATAR (Gaya Modern) --}}
            <div class="col-lg-4 col-xl-3">
                {{-- KARTU PROFIL --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-body text-center p-4">
                        <div class="position-relative d-inline-block mb-3">
                            {{-- Avatar Placeholder dengan Inisial --}}
                            <div class="avatar-circle bg-primary bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                 style="width: 96px; height: 96px; font-size: 2.5rem; font-weight: 700;">
                                {{ substr(strtoupper($user->name), 0, 1) }}
                            </div>
                            {{-- Badge Role (Opsional) --}}
                            <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle p-2">
                                <span class="visually-hidden">Active</span>
                            </span>
                        </div>
                        <h5 class="fw-bold text-body mb-1">{{ $user->name }}</h5>
                        <p class="text-secondary small mb-3">{{ $user->email }}</p>
                        <div class="d-grid">
                            <button class="btn btn-outline-primary btn-sm rounded-pill">Upload Photo</button>
                        </div>
                    </div>
                </div>

                {{-- MENU NAVIGASI SAMPING (Supaya kayak menu setting beneran) --}}
                <div class="list-group rounded-4 shadow-sm border-0 overflow-hidden">
                    <a href="#profile-section" class="list-group-item list-group-item-action p-3 active border-0">
                        <i class="bi bi-person-bounding-box me-2"></i> Public Profile
                    </a>
                    <a href="#security-section" class="list-group-item list-group-item-action p-3 border-0">
                        <i class="bi bi-shield-check me-2"></i> Security & Password
                    </a>
                    <a href="#danger-section" class="list-group-item list-group-item-action p-3 border-0 text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i> Delete Account
                    </a>
                </div>
            </div>

            {{-- KOLOM KANAN: FORMULIR --}}
            <div class="col-lg-8 col-xl-7">

                {{-- FEEDBACK ALERT --}}
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4 border-start border-success border-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <div>
                                <strong class="d-block">Berhasil!</strong>
                                <span class="small text-muted">{{ session('status') === 'profile-updated' ? 'Profil berhasil diperbarui.' : 'Password berhasil diubah.' }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- 1. SECTION: PROFILE INFORMATION --}}
                <div id="profile-section" class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h5 class="fw-bold mb-0">Public Profile</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold text-secondary text-uppercase ls-1">Full Name</label>
                                    <input type="text" name="name" class="form-control form-control-lg bg-body-tertiary border-0" value="{{ old('name', $user->name) }}" required>
                                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold text-secondary text-uppercase ls-1">Email Address</label>
                                    <input type="email" name="email" class="form-control form-control-lg bg-body-tertiary border-0" value="{{ old('email', $user->email) }}" required>
                                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-3">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- 2. SECTION: SECURITY --}}
                <div id="security-section" class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h5 class="fw-bold mb-0">Password & Security</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Current Password</label>
                                <input type="password" name="current_password" class="form-control bg-body-tertiary border-0" placeholder="••••••••" required>
                                @error('current_password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary">New Password</label>
                                    <input type="password" name="password" class="form-control bg-body-tertiary border-0" placeholder="Min. 8 characters" required>
                                    @error('password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control bg-body-tertiary border-0" placeholder="Repeat password" required>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-dark px-4 py-2 fw-semibold rounded-3">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- 3. SECTION: DANGER ZONE (GITHUB STYLE) --}}
                <div id="danger-section" class="card border border-danger border-opacity-25 shadow-sm rounded-4 bg-danger-subtle bg-opacity-10">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                            <div>
                                <h5 class="fw-bold text-danger mb-1">Delete Account</h5>
                                <p class="text-secondary small mb-0" style="max-width: 400px;">
                                    Menghapus akun Anda akan menghapus semua data yang terkait secara permanen. Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                            <button class="btn btn-danger fw-semibold px-4" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL DELETE ACCOUNT --}}
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-danger text-white border-0 p-4">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Delete Account
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-body p-4 bg-body">
                        <p class="fw-bold text-danger mb-2">Are you absolutely sure?</p>
                        <p class="text-secondary small mb-4">
                            Tindakan ini akan menghapus akun, semua event, dan riwayat aktivitas Anda. 
                            Silakan ketik password Anda untuk konfirmasi.
                        </p>
                        
                        <div class="form-floating">
                            <input type="password" name="password" class="form-control" id="delPass" placeholder="Password" required>
                            <label for="delPass">Enter your password</label>
                            @error('password', 'userDeletion') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 bg-body-tertiary p-3">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger fw-bold">Yes, Delete Everything</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CSS TAMBAHAN AGAR LEBIH RAPI --}}
    <style>
        .ls-1 { letter-spacing: 0.5px; }
        .form-control:focus {
            box-shadow: none;
            border-color: var(--bs-primary);
            background-color: var(--bs-body-bg);
            border-width: 2px;
        }
        /* Efek Active di Sidebar */
        .list-group-item.active {
            background-color: var(--bs-primary);
            color: white;
            font-weight: 600;
        }
    </style>

</x-app-layout>