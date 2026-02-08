<x-app-layout>
    {{-- 1. BAGIAN HEADER (Judul Halaman) --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-shield-lock-fill text-warning me-2"></i>Admin Event Manager
                </h2>
                <p class="text-secondary small mb-0">
                    Kelola seluruh event dari semua user.
                </p>
            </div>
            
            {{-- Tombol Tambah --}}
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Buat Event
            </a>
        </div>
    </x-slot>

    {{-- 2. BAGIAN KONTEN UTAMA --}}
    <div class="py-5">
        <div class="container">
            
            {{-- ALERT SUKSES --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- FILTER & PENCARIAN --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.events.index') }}" method="GET" class="row g-3 align-items-end">
                        
                        {{-- Search --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Pencarian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                       placeholder="Cari event, lokasi, atau user..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Filter Status --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                                <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        {{-- Tombol Filter --}}
                        <div class="col-md-3 d-grid">
                            <button type="submit" class="btn btn-dark fw-bold">
                                <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-body-tertiary">
                            <tr>
                                <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">Event Info</th>
                                <th class="py-3 text-secondary text-uppercase small fw-bold">Ketua Panitia</th>
                                <th class="py-3 text-secondary text-uppercase small fw-bold">Jadwal</th>
                                <th class="py-3 text-secondary text-uppercase small fw-bold">Status</th>
                                <th class="pe-4 py-3 text-end text-secondary text-uppercase small fw-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr>
                                {{-- Kolom Nama --}}
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">{{ $event->name }}</div>
                                    <div class="small text-muted text-truncate" style="max-width: 250px;">
                                        {{ Str::limit($event->description ?? '-', 40) }}
                                    </div>
                                </td>

                                {{-- Kolom User (Ghost Style) --}}
                                <td>
                                    @if($event->user && $event->user->trashed())
                                        <div class="d-flex align-items-center opacity-50" title="User Dihapus">
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2 small" style="width: 30px; height: 30px;">
                                                <i class="bi bi-person-x"></i>
                                            </div>
                                            <div class="fst-italic text-decoration-line-through small">{{ $event->user->name }}</div>
                                        </div>
                                    @elseif($event->user)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold small" style="width: 30px; height: 30px;">
                                                {{ substr($event->user->name, 0, 1) }}
                                            </div>
                                            <div class="small fw-bold">{{ $event->user->name }}</div>
                                        </div>
                                    @else
                                        <span class="text-danger small fst-italic">User Unknown</span>
                                    @endif
                                </td>

                                {{-- Kolom Jadwal --}}
                                <td>
                                    <div class="small fw-bold">
                                        {{ \Carbon\Carbon::parse($event->date ?? $event->event_date)->format('d M Y') }}
                                    </div>
                                    <div class="small text-muted">
                                        <i class="bi bi-geo-alt me-1"></i> {{ $event->location }}
                                    </div>
                                </td>

                                {{-- Kolom Status --}}
                                <td>
                                    @php
                                        $evtDate = \Carbon\Carbon::parse($event->date ?? $event->event_date);
                                    @endphp
                                    @if($evtDate->isPast())
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Selesai</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Upcoming</span>
                                    @endif
                                </td>

                                {{-- Kolom Aksi --}}
                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm rounded-3" role="group">
                                        
                                        {{-- 1. DOWNLOAD PDF (Baru) --}}
                                        <a href="{{ route('events.report.pdf', $event->id) }}" target="_blank" class="btn btn-sm btn-white border hover-info" title="Download Laporan PDF">
                                            <i class="bi bi-file-earmark-pdf text-danger"></i>
                                        </a>

                                        {{-- 2. Edit --}}
                                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-white border hover-warning" title="Edit">
                                            <i class="bi bi-pencil-square text-warning"></i>
                                        </a>

                                        {{-- 3. Hapus --}}
                                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus event ini permanen (Soft Delete)?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-white border hover-danger" title="Hapus">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-2"><i class="bi bi-inbox fs-1 opacity-25"></i></div>
                                    Tidak ada data event ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                <div class="card-footer bg-white py-3 border-0">
                    <div class="d-flex justify-content-end">
                        {{ $events->withQueryString()->links() }}
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{-- CSS Tambahan Sedikit --}}
    <style>
        .btn-white { background: white; color: #333; }
        .hover-warning:hover { background: #fff3cd; border-color: #ffecb5; }
        .hover-danger:hover { background: #f8d7da; border-color: #f5c6cb; }
        .hover-info:hover { background: #e0f2fe; border-color: #bae6fd; } /* CSS untuk hover tombol PDF */
    </style>
</x-app-layout>