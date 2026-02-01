<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-body mb-1">Kelola User</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">User List</li>
                    </ol>
                </nav>
            </div>
            
            {{-- SEARCH BAR --}}
            <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-body-tertiary border-0 text-secondary"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-body-tertiary border-0" 
                           placeholder="Cari nama / email..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            
            {{-- ALERT NOTIFIKASI --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-start border-success border-4 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-4 me-2 text-success"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-start border-danger border-4 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-2 text-danger"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- CARD TABEL --}}
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-body border-bottom p-4">
                    <h6 class="fw-bold mb-0"><i class="bi bi-people-fill me-2 text-primary"></i>Daftar Pengguna</h6>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-body-tertiary">
                                <tr>
                                    <th class="ps-4 py-3 small text-secondary text-uppercase fw-bold">User Info</th>
                                    <th class="py-3 small text-secondary text-uppercase fw-bold">Role Sistem</th>
                                    <th class="py-3 small text-secondary text-uppercase fw-bold">Terdaftar</th>
                                    <th class="pe-4 py-3 text-end small text-secondary text-uppercase fw-bold">Aksi</th>
                                </div>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    {{-- INFO USER --}}
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            {{-- Avatar Inisial --}}
                                            <div class="avatar-circle rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold text-white shadow-sm" 
                                                 style="width: 40px; height: 40px; background: linear-gradient(45deg, #4f46e5, #818cf8);">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-body">{{ $user->name }}</div>
                                                <div class="small text-secondary">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- ROLE BADGE --}}
                                    <td>
                                        @if($user->user_role == 'admin')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                                <i class="bi bi-shield-check me-1"></i> Super Admin
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2">
                                                <i class="bi bi-person me-1"></i> User Biasa
                                            </span>
                                        @endif
                                    </td>

                                    {{-- TANGGAL JOIN --}}
                                    <td class="text-secondary small">
                                        <i class="bi bi-calendar3 me-1 opacity-50"></i>
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>

                                    {{-- TOMBOL AKSI --}}
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            {{-- Edit Button --}}
                                            <button class="btn btn-sm btn-light text-warning shadow-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}"
                                                    title="Edit User">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Delete Button (Disabled for Self) --}}
                                            @if(auth()->id() !== $user->id)
                                                <button class="btn btn-sm btn-light text-danger shadow-sm" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteUserModal-{{ $user->id }}"
                                                        title="Hapus User">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-light text-muted shadow-sm" disabled title="Tidak bisa hapus diri sendiri">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted opacity-50 mb-2"><i class="bi bi-search display-4"></i></div>
                                        <h6 class="fw-bold text-secondary">Tidak ada user ditemukan.</h6>
                                        <p class="small text-secondary">Coba kata kunci pencarian lain.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    @if($users->hasPages())
                        <div class="p-3 border-top bg-body-tertiary">
                            {{ $users->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- =================================================================== --}}
    {{-- MODALS SECTION (Looping untuk setiap user) --}}
    {{-- =================================================================== --}}
    
    @foreach($users as $user)
        
        {{-- 1. MODAL EDIT --}}
        <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-body border-0 pb-0">
                        <h5 class="fw-bold modal-title text-body">Edit Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4">
                            
                            {{-- Nama --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control bg-body-tertiary border-0" value="{{ $user->name }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Email Address</label>
                                <input type="email" name="email" class="form-control bg-body-tertiary border-0" value="{{ $user->email }}" required>
                            </div>

                            {{-- Role --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Role Sistem</label>
                                <select name="role" class="form-select bg-body-tertiary border-0">
                                    <option value="user" {{ $user->user_role == 'user' ? 'selected' : '' }}>User Biasa</option>
                                    <option value="admin" {{ $user->user_role == 'admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                                <div class="form-text small">Admin memiliki akses penuh ke sistem.</div>
                            </div>

                            {{-- Password (Opsional) --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Password Baru <span class="badge bg-secondary-subtle text-secondary fw-normal">Opsional</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary border-0"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control bg-body-tertiary border-0" placeholder="Biarkan kosong jika tetap">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light text-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 2. MODAL DELETE (Kecuali Diri Sendiri) --}}
        @if(auth()->id() !== $user->id)
        <div class="modal fade" id="deleteUserModal-{{ $user->id }}" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-body p-4 text-center">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 70px; height: 70px;">
                            <i class="bi bi-person-x-fill fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-body">Hapus Pengguna Ini?</h5>
                        <p class="text-secondary small mb-4">
                            Anda akan menghapus akun <strong>{{ $user->name }}</strong>.<br>
                            Semua data event yang dibuat user ini mungkin akan terpengaruh.
                        </p>
                        
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger fw-bold px-4">Ya, Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    @endforeach

</x-app-layout>