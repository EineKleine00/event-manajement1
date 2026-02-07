<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-body mb-0">
                {{ $event->name }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('events.index') }}" class="text-decoration-none">Events</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">

            {{-- ALERT SUKSES/ERROR --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4  border-start border-success border-4 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4  border-start border-danger border-4 shadow-sm" role="alert">
                    <ul class="mb-0 small ps-3">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                {{-- KOLOM KIRI: INFO & ANGGOTA --}}
                <div class="col-lg-4">
                    
                    {{-- 1. INFO EVENT --}}
                    <div class="card mb-4 shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-body mb-3">Deskripsi Event</h5>
                            <p class="text-secondary small mb-4" style="line-height: 1.6;">{{ $event->description }}</p>
                            
                            <div class="d-flex align-items-center p-3 rounded-3 bg-body-tertiary mb-3">
                                <i class="bi bi-calendar-check fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-uppercase text-secondary fw-bold" style="font-size: 0.7rem;">Tanggal Pelaksanaan</small>
                                    <div class="fw-bold text-body">{{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</div>
                                </div>
                            </div>

                            @if($isKetua)
                                <div class="d-grid">
                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-outline-primary fw-bold">
                                        <i class="bi bi-pencil-square me-1"></i> Edit Informasi
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. MEMBER TABEL (Khusus Ketua dan sponsor) --}}
                    @if($isKetua||$isSponsor)
                    <div class="card mb-4 shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-header bg-body border-bottom p-3">
                            <h6 class="fw-bold m-0"><i class="bi bi-people-fill me-2 text-primary"></i> Anggota Tim</h6>
                        </div>
                        
                        <div class="card-body p-0">
                            {{-- TABEL MEMBER --}}
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-body-tertiary">
                                        <tr>
                                            <th class="ps-4 small text-secondary text-uppercase">Nama</th>
                                            <th class="small text-secondary text-uppercase">Role</th>
                                            @if ($isKetua)
                                            <th class="text-end pe-4 small text-secondary text-uppercase">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0">
                                        @forelse ($event->users as $member)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-body small">{{ $member->name }}</div>
                                                <div class="text-secondary" style="font-size: 0.75rem;">{{ $member->email }}</div>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill {{ $member->pivot->role == 'petugas' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' }}">
                                                    {{ ucfirst($member->pivot->role) }}
                                                </span>
                                            </td>
                                            @if ($isKetua)
                                            <td class="text-end pe-4">
                                                <button type="button" 
                                                        class="btn btn-link text-warning p-0 btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEditMember-{{ $member->id }}"
                                                        title="Edit Role">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <form action="{{ route('events.members.destroy', [$event->id, $member->id]) }}" method="POST" onsubmit="return confirm('Keluarkan member ini?');">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-link text-danger p-0 btn-sm"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-secondary py-4 small">Belum ada anggota tim.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        {{-- FORM TAMBAH MEMBER --}}
                        @if ($isKetua)
                        
                        <div class="card-footer bg-body-tertiary p-3 border-top">
                            <p class="small fw-bold text-secondary mb-2">Undang Anggota Baru</p>
                            <form action="{{ route('events.members.store', $event->id) }}" method="POST">
                                @csrf
                                <div class="mb-2 position-relative">
                                    <input type="text" id="member-search" class="form-control form-control-sm bg-body border-0 shadow-sm" placeholder="Ketik nama user..." autocomplete="off">
                                    <input type="hidden" name="user_id" id="member-user-id" required>
                                    
                                    {{-- Hasil Search Dropdown --}}
                                    <div id="member-search-results" class="list-group position-absolute w-100 shadow rounded-3 mt-1 overflow-auto bg-body" style="z-index: 1050; display: none; max-height: 200px;"></div>
                                </div>
                                <div class="d-flex gap-2">
                                    <select name="role" class="form-select form-select-sm bg-body border-0 shadow-sm w-50" required>
                                        <option value="petugas">Petugas</option>
                                        <option value="sponsor">Sponsor</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm w-50 shadow-sm" id="btn-add-member" disabled>Tambah</button>
                                </div>
                            </form>
                        </div>
                        @endif
                        <a href="{{ route('events.report.pdf', $event->id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-printer-fill me-1"></i> Cetak Laporan
                        </a>
                    </div>
                    @endif

                </div>

                {{-- KOLOM KANAN: TASK MANAGEMENT --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-header bg-body border-bottom p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-1">Daftar Tugas</h5>
                                <p class="text-secondary small mb-0">Monitor progres pekerjaan tim.</p>
                            </div>
                            
                            @if($isKetua)
                            <button class="btn btn-primary btn-sm fw-bold rounded-3 shadow-sm" onclick="toggleAddTaskForm()">
                                <i class="bi bi-plus-lg me-1"></i> Buat Task
                            </button>
                            @endif
                        </div>

                        <div class="card-body p-0">
                            
                            {{-- FORM TAMBAH TASK (TOGGLE) --}}
                            @if($isKetua)
                            <div id="add-task-form" class="bg-body-tertiary border-bottom p-4" style="display: none;">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-layers-fill me-2"></i>Buat Tugas Baru</h6>
                                <form action="{{ route('tasks.store', $event->id) }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-5">
                                            <label class="form-label small fw-bold text-secondary">Judul Tugas</label>
                                            <input type="text" name="title" class="form-control" required placeholder="Contoh: Dokumentasi">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-secondary">Petugas</label>
                                            <select name="user_id" class="form-select" required>
                                                <option value="">Pilih...</option>
                                                @foreach($event->users as $u)
                                                    @if($u->pivot->role == 'petugas')
                                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-secondary">Deskripsi Singkat</label>
                                            <input type="text" name="description" class="form-control" required placeholder="Detail...">
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-link text-secondary text-decoration-none me-2" onclick="toggleAddTaskForm()">Batal</button>
                                            <button type="submit" class="btn btn-success px-4 fw-bold">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif

                            {{-- TABEL TASK --}}
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-body-tertiary">
                                        <tr>
                                            <th class="ps-4 py-3 small text-secondary text-uppercase w-50">Task Info</th>
                                            <th class="py-3 small text-secondary text-uppercase">Petugas</th>
                                            <th class="py-3 small text-secondary text-uppercase">Status</th>
                                            @if ($isKetua) <th class="pe-4 py-3 text-end small text-secondary text-uppercase">Aksi</th> @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tasks as $task)
                                        <tr>
                                            {{-- INFO TASK --}}
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-body">{{ $task->title }}</div>
                                                <div class="text-secondary small text-truncate" style="max-width: 250px;">{{ $task->description }}</div>
                                                @if($task->is_done && $task->completion_note)
                                                    <div class="mt-2 p-2 rounded bg-body-tertiary border text-secondary small fst-italic">
                                                        <i class="bi bi-chat-quote-fill me-1 opacity-50"></i> {{ $task->completion_note }}
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- PETUGAS --}}
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem; fw-bold">
                                                        {{ substr($task->user->name, 0, 1) }}
                                                    </div>
                                                    <span class="small fw-semibold text-body">{{ $task->user->name }}</span>
                                                </div>
                                            </td>

                                            {{-- STATUS --}}
                                            <td>
                                                @if($task->is_done)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                                        <i class="bi bi-hourglass me-1"></i> Pending
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- AKSI --}}
                                            <td class="pe-4 text-end">
                                                {{-- Tombol Edit (Ketua) --}}
                                                @if($isKetua)
                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?');" class="d-inline">
                                                        @csrf 
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0 btn-sm">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                    <button class="btn btn-icon btn-sm btn-link text-secondary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEditTask-{{ $task->id }}">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                @endif

                                                {{-- Tombol Bukti/Lapor --}}
                                                @if($task->is_done && $task->image_proof)
                                                    <a href="{{ asset('storage/' . $task->image_proof) }}" target="_blank" class="btn btn-icon btn-sm btn-primary shadow-sm ms-1" title="Lihat Bukti">
                                                        <i class="bi bi-image"></i>
                                                    </a>
                                                @elseif(!$task->is_done && Auth::id() == $task->user_id)
                                                    <button class="btn btn-sm btn-primary shadow-sm ms-1" data-bs-toggle="modal" data-bs-target="#modalFinishTask-{{ $task->id }}">
                                                        Lapor <i class="bi bi-camera-fill ms-1"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="text-secondary opacity-50 mb-2"><i class="bi bi-clipboard2-x display-4"></i></div>
                                                <p class="text-secondary small">Belum ada tugas yang dibuat.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================== MODALS (DARK MODE READY) ====================== --}}

    {{-- 1. MODAL LAPOR SELESAI --}}
    @foreach($tasks as $task)
        @if(!$task->is_done && Auth::id() == $task->user_id)
        <div class="modal fade" id="modalFinishTask-{{ $task->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"> 
                <div class="modal-content border-0 shadow-lg"> 
                    <div class="modal-header bg-primary text-white border-0">
                        <h6 class="modal-title fw-bold"><i class="bi bi-camera-fill me-2"></i>Lapor Tugas Selesai</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return konfirmasiKirim(this)">
                        @csrf @method('PATCH')
                        <div class="modal-body p-4"> 
                            <div class="mb-4 text-center">
                                <h5 class="fw-bold text-body mb-1">{{ $task->title }}</h5>
                                <p class="small text-secondary">Silakan upload bukti bahwa tugas telah selesai.</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Bukti Foto <span class="text-danger">*</span></label>
                                <input type="file" name="image_proof" class="form-control" required accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Catatan Tambahan</label>
                                <textarea name="completion_note" class="form-control" rows="3" placeholder="Kendala atau keterangan..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light text-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary fw-bold">Kirim Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    {{-- 2. MODAL EDIT TASK --}}
    @if($isKetua)
        @foreach($tasks as $task)
        <div class="modal fade" id="modalEditTask-{{ $task->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-warning border-0">
                        <h6 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2"></i>Edit Tugas</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Judul</label>
                                <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Petugas</label>
                                <select name="user_id" class="form-select" required>
                                    @foreach($event->users as $u)
                                        @if($u->pivot->role == 'petugas')
                                            <option value="{{ $u->id }}" {{ $task->user_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" required>{{ $task->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light text-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning fw-bold text-dark">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endif

    {{-- 3. MODAL EDIT MEMBER --}}
    @if($isKetua)
        @foreach($event->users as $member)
       <div class="modal fade" id="modalEditMember-{{ $member->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    
                    <div class="modal-header bg-warning border-0">
                        <h6 class="modal-title fw-bold text-dark">
                            <i class="bi bi-person-gear me-2"></i>Edit Peran Anggota
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('events.members.update', [$event->id, $member->id]) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="modal-body p-4 text-start">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Nama Anggota</label>
                                <input type="text" class="form-control bg-light" value="{{ $member->name }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Ganti Peran (Role)</label>
                                <select name="role" class="form-select" required>
                                    <option value="petugas" {{ $member->pivot->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                    <option value="sponsor" {{ $member->pivot->role == 'sponsor' ? 'selected' : '' }}>Sponsor</option>
                                </select>
                                <div class="form-text small">
                                    <ul>
                                        <li><strong>Petugas:</strong> Bisa mengerjakan tugas lapangan.</li>
                                        <li><strong>Sponsor:</strong> Hanya memantau progres & laporan.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light text-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning fw-bold text-dark">Simpan Perubahan</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endif
        
    {{-- SCRIPTS --}}
    <script>
        function konfirmasiKirim(form) {
            let inputFoto = form.querySelector('input[name="image_proof"]');
            if (inputFoto.files.length > 0) {
                let namaFile = inputFoto.files[0].name;
                let ukuranFile = (inputFoto.files[0].size / 1024).toFixed(2);
                let pesan = `Konfirmasi Upload:\n\n📂 File: ${namaFile}\n⚖️ Size: ${ukuranFile} KB\n\nLanjutkan?`;
                return confirm(pesan);
            }
            return true;
        }

        function toggleAddTaskForm() {
            const form = document.getElementById('add-task-form');
            if (form) form.style.display = (form.style.display === 'none') ? 'block' : 'none';
        }

        // LIVE SEARCH & MODAL FIX
        document.addEventListener('DOMContentLoaded', function() {
            // FIX: Pindahkan semua modal ke body agar tidak tertutup backdrop (masalah z-index)
            document.querySelectorAll('.modal').forEach(modal => {
                document.body.appendChild(modal);
            });

            const searchInput = document.getElementById('member-search');
            const resultsDiv = document.getElementById('member-search-results');
            const userIdInput = document.getElementById('member-user-id');
            const addButton = document.getElementById('btn-add-member');

            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    let query = this.value;
                    let eventId = "{{ $event->id }}";

                    if(query.length < 2) { resultsDiv.style.display = 'none'; return; }

                    fetch(`/ajax/users/search?q=${query}&event_id=${eventId}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsDiv.innerHTML = '';
                        if(data.length > 0) {
                            resultsDiv.style.display = 'block';
                            data.forEach(user => {
                                let item = document.createElement('a');
                                item.classList.add('list-group-item', 'list-group-item-action', 'border-0', 'px-3', 'py-2');
                                item.style.cursor = 'pointer';
                                item.innerHTML = `<div class="fw-bold text-body small">${user.name}</div><div class="text-secondary" style="font-size: 0.7rem;">${user.email}</div>`;
                                item.addEventListener('click', function() {
                                    searchInput.value = user.name;
                                    userIdInput.value = user.id;
                                    resultsDiv.style.display = 'none';
                                    addButton.disabled = false;
                                });
                                resultsDiv.appendChild(item);
                            });
                        } else {
                            resultsDiv.style.display = 'none';
                        }
                    });
                });

                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                        resultsDiv.style.display = 'none';
                    }
                });
            }
        });
    </script>
</x-app-layout>