<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold text-body mb-1">Admin Dashboard</h2>
                <p class="text-body-secondary small mb-0">Overview aktivitas dan statistik sistem.</p>
            </div>
            <div class="d-flex gap-2">
                {{-- Status System Badge --}}
                <div class="px-3 py-2 rounded-3 bg-body-tertiary border d-flex align-items-center gap-2">
                    <span class="position-relative d-flex h-2 w-2">
                      <span class="position-absolute top-0 start-0 translate-middle p-1 bg-success border border-light rounded-circle"></span>
                    </span>
                    <span class="small fw-bold text-body">System Online</span>
                </div>
                {{-- Tanggal --}}
                <div class="px-3 py-2 rounded-3 bg-primary bg-opacity-10 text-primary border border-primary-subtle fw-bold small">
                    <i class="bi bi-calendar-event me-1"></i> {{ now()->format('d M Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            
            {{-- BAGIAN 1: STATISTICS CARDS --}}
            {{-- Menggunakan Grid yang responsif --}}
            <div class="row g-4 mb-5">
                
                {{-- Card 1: Total Users --}}
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden stat-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 icon-box">
                                    <i class="bi bi-people-fill fs-4"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-body-secondary no-arrow p-0" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                        <li><a class="dropdown-item small" href="{{ route('users.index') }}">Lihat Detail</a></li>
                                    </ul>
                                </div>
                            </div>
                            <h5 class="text-body-secondary text-uppercase small fw-bold mb-1">Total Pengguna</h5>
                            <div class="d-flex align-items-end gap-2">
                                <h2 class="fw-bold text-body mb-0">{{ $stats['total_users'] }}</h2>
                                <span class="badge bg-success-subtle text-success rounded-pill mb-1">
                                    <i class="bi bi-arrow-up-short"></i> Active
                                </span>
                            </div>
                        </div>
                        {{-- Hiasan Garis Bawah --}}
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: 70%"></div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Super Admins --}}
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden stat-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3 icon-box">
                                    <i class="bi bi-shield-lock-fill fs-4"></i>
                                </div>
                            </div>
                            <h5 class="text-body-secondary text-uppercase small fw-bold mb-1">Super Admins</h5>
                            <div class="d-flex align-items-end gap-2">
                                <h2 class="fw-bold text-body mb-0">{{ $stats['total_admins'] }}</h2>
                            </div>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-danger" style="width: 40%"></div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Total Events --}}
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden stat-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 icon-box">
                                    <i class="bi bi-calendar-check-fill fs-4"></i>
                                </div>
                            </div>
                            <h5 class="text-body-secondary text-uppercase small fw-bold mb-1">Total Event</h5>
                            <div class="d-flex align-items-end gap-2">
                                <h2 class="fw-bold text-body mb-0">{{ $stats['total_events'] }}</h2>
                            </div>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-info" style="width: 85%"></div>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Upcoming --}}
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden stat-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 icon-box">
                                    <i class="bi bi-hourglass-split fs-4"></i>
                                </div>
                            </div>
                            <h5 class="text-body-secondary text-uppercase small fw-bold mb-1">Akan Datang</h5>
                            <div class="d-flex align-items-end gap-2">
                                <h2 class="fw-bold text-body mb-0">{{ $stats['upcoming_events'] }}</h2>
                                <span class="badge bg-warning-subtle text-warning rounded-pill mb-1">Soon</span>
                            </div>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-warning" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                
                {{-- BAGIAN 2: DAFTAR USER TERBARU (KIRI) --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-body border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0 text-body">Pengguna Baru</h6>
                                <small class="text-body-secondary">5 Pendaftar terakhir</small>
                            </div>
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
                        </div>
                        <div class="card-body p-0 mt-3">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-body-tertiary">
                                        <tr>
                                            <th class="ps-4 py-3 small text-secondary text-uppercase fw-bold border-0">User</th>
                                            <th class="py-3 small text-secondary text-uppercase fw-bold border-0">Role</th>
                                            <th class="pe-4 py-3 text-end small text-secondary text-uppercase fw-bold border-0">Terdaftar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($latestUsers as $user)
                                        <tr class="border-bottom border-body-tertiary">
                                            <td class="ps-4 py-3 border-0">
                                                <div class="d-flex align-items-center">
                                                    {{-- Avatar dengan inisial --}}
                                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 40px; height: 40px; font-size: 0.9rem;">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-body">{{ $user->name }}</div>
                                                        <div class="small text-body-secondary">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="border-0">
                                                @if($user->user_role == 'admin')
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Admin</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">User</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4 text-body-secondary small border-0">
                                                {{ $user->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">Belum ada data user.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BAGIAN 3: QUICK ACTIONS & EVENT (KANAN) --}}
                <div class="col-lg-4">
                    
                    {{-- Quick Actions Card --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white position-relative overflow-hidden">
                        {{-- Hiasan Background --}}
                        <div class="position-absolute top-0 end-0 p-3 opacity-25">
                            <i class="bi bi-grid-1x2-fill" style="font-size: 8rem; transform: rotate(-15deg);"></i>
                        </div>
                        
                        <div class="card-body p-4 position-relative z-1">
                            <h5 class="fw-bold mb-1">Quick Actions</h5>
                            <p class="small opacity-75 mb-4">Akses cepat menu pengelolaan.</p>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('users.index') }}" class="btn btn-light text-primary fw-bold border-0 shadow-sm text-start p-3 d-flex align-items-center justify-content-between transition-hover">
                                    <span><i class="bi bi-person-plus-fill me-2"></i> Kelola Users</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                
                                <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-light fw-bold border-2 text-start p-3 d-flex align-items-center justify-content-between transition-hover">
                                    <span><i class="bi bi-gear-fill me-2"></i> Konfigurasi Sistem</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Events List --}}
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-body border-0 p-4 pb-2">
                            <h6 class="fw-bold mb-0 text-body">Event Terbaru</h6>
                        </div>
                        <div class="card-body p-4 pt-2">
                            @forelse($latestEvents as $event)
                                <div class="d-flex align-items-center py-3 border-bottom border-dashed last-no-border">
                                    <div class="bg-body-tertiary rounded-3 d-flex flex-column align-items-center justify-content-center me-3 border" style="width: 50px; height: 50px;">
                                        <span class="fw-bold h5 mb-0 text-body" style="line-height: 1;">{{ $event->created_at->format('d') }}</span>
                                        <span class="small text-secondary text-uppercase" style="font-size: 0.6rem;">{{ $event->created_at->format('M') }}</span>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="text-body fw-bold mb-0 text-truncate">{{ $event->name }}</h6>
                                        <small class="text-body-secondary text-truncate d-block">{{ Str::limit($event->description, 35) }}</small>
                                    </div>
                                    <div class="ms-2">
                                         <i class="bi bi-chevron-right text-body-tertiary"></i>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted small py-3">Belum ada event.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- CUSTOM CSS UNTUK DARK MODE & ANIMASI --}}
    <style>
        /* Transisi Halus */
        .card, .btn, .badge {
            transition: all 0.2s ease-in-out;
        }

        /* Hover Effects */
        .transition-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* Helper Border Dashed */
        .border-dashed { border-bottom-style: dashed !important; border-bottom-color: var(--bs-border-color) !important; }
        .last-no-border:last-child { border-bottom: none !important; }

        /* DARK MODE TWEAKS */
        [data-bs-theme="dark"] .bg-body-tertiary {
            background-color: #2b3035 !important;
        }
        
        [data-bs-theme="dark"] .card {
            background-color: #212529; /* Warna Card Dark Mode */
            border: 1px solid #373b3e !important; /* Tambah border tipis biar card terlihat */
        }

        [data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: #2c3034;
        }
        
        [data-bs-theme="dark"] .text-body-secondary {
            color: #adb5bd !important;
        }
    </style>
</x-app-layout>