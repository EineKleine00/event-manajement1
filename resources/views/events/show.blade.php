<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-body-emphasis leading-tight">
            {{ $event->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ALERT SUKSES/ERROR --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- 1. INFO EVENT --}}
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold text-body-emphasis">Deskripsi Event</h5>
                    <p class="text-body-secondary">{{ $event->description }}</p>
                    <p class="text-body-secondary mb-3"><strong><i class="bi bi-calendar-event me-2"></i>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</p>

                    @if($isKetua)
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning btn-sm fw-bold">
                            <i class="bi bi-pencil-square me-1"></i> Edit Event
                        </a>
                    @endif
                </div>
            </div>

            {{-- 2. MEMBER MANAGEMENT (Khusus Ketua) --}}
            @if($isKetua)
            <div class="card mb-4 shadow-sm border-primary border-top-0 border-end-0 border-bottom-0 border-3">
                <div class="card-header bg-body-tertiary fw-bold">
                    <i class="bi bi-people-fill me-2 text-primary"></i> Kelola Anggota (Petugas & Sponsor)
                </div>
                <div class="card-body">
                    
                    {{-- TABEL MEMBER --}}
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($event->users as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td><small class="text-muted">{{ $member->email }}</small></td>
                                    <td>
                                        <span class="badge {{ $member->pivot->role == 'petugas' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ ucfirst($member->pivot->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('events.members.destroy', [$event->id, $member->id]) }}" method="POST" onsubmit="return confirm('Keluarkan member ini?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm py-0 px-2">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada anggota. Silakan tambah di bawah.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    {{-- FORM TAMBAH MEMBER --}}
                    <h6 class="fw-bold mt-3">+ Tambah Anggota Baru</h6>
                    <form action="{{ route('events.members.store', $event->id) }}" method="POST" class="row g-2 align-items-end">
                        @csrf
                        
                        {{-- LIVE SEARCH INPUT --}}
                        <div class="col-md-5 position-relative">
                            <label class="small text-muted">Cari User (Nama/Email)</label>
                            <input type="text" id="member-search" class="form-control form-control-sm" placeholder="Ketik nama user..." autocomplete="off">
                            <input type="hidden" name="user_id" id="member-user-id" required>
                            
                            {{-- Hasil Search Dropdown --}}
                            <div id="member-search-results" class="list-group position-absolute w-100 shadow" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></div>
                        </div>

                        <div class="col-md-3">
                            <label class="small text-muted">Role</label>
                            <select name="role" class="form-select form-select-sm" required>
                                <option value="petugas">Petugas</option>
                                <option value="sponsor">Sponsor</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-sm w-100" id="btn-add-member" disabled>Tambahkan</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- 3. TASK MANAGEMENT --}}
            <div class="card shadow-sm border-success border-top-0 border-end-0 border-bottom-0 border-3">
                <div class="card-header bg-body-tertiary fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-check me-2 text-success"></i> Daftar Tugas (Task)</span>
                    
                    {{-- Tombol Toggle Form Tambah Task --}}
                    @if($isKetua)
                    <button class="btn btn-sm btn-outline-success" onclick="toggleAddTaskForm()">
                        + Buat Task Baru
                    </button>
                    @endif
                </div>

                <div class="card-body">
                    
                    {{-- FORM TAMBAH TASK (HIDDEN BY DEFAULT) --}}
                    @if($isKetua)
                    <div id="add-task-form" class="card bg-body-secondary mb-4 border-0 p-3" style="display: none;">
                        <h6 class="fw-bold mb-2">Form Task Baru</h6>
                        <form action="{{ route('tasks.store', $event->id) }}" method="POST">
                            @csrf
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="small">Judul Tugas</label>
                                    <input type="text" name="title" class="form-control form-control-sm" required placeholder="Contoh: Jaga Meja Registrasi">
                                </div>
                                <div class="col-md-4">
                                    <label class="small">Pilih Petugas</label>
                                    <select name="user_id" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Anggota --</option>
                                        @foreach($event->users as $u)
                                            @if($u->pivot->role == 'petugas')
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small class="text-muted" style="font-size: 10px;">*Hanya role Petugas yg muncul</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="small">Deskripsi</label>
                                    <input type="text" name="description" class="form-control form-control-sm" required placeholder="Detail tugas...">
                                </div>
                                <div class="col-12 text-end mt-2">
                                    <button type="button" class="btn btn-secondary btn-sm me-1" onclick="toggleAddTaskForm()">Batal</button>
                                    <button type="submit" class="btn btn-success btn-sm">Simpan Task</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif

                    {{-- TABEL TASK --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Task</th>
                                    <th>Petugas</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                <tr>
                                    {{-- KOLOM 1: JUDUL & DESKRIPSI --}}
                                    <td>
                                        <div class="fw-bold text-body-emphasis">{{ $task->title }}</div>
                                        <div class="small text-body-secondary">{{ $task->description }}</div>
                                        
                                        {{-- Tampilkan Catatan Jika Ada --}}
                                        @if($task->is_done && $task->completion_note)
                                            <div class="mt-2 p-2 bg-body-tertiary border rounded small text-muted fst-italic">
                                                <i class="bi bi-sticky me-1"></i> "{{ $task->completion_note }}"
                                            </div>
                                        @endif
                                    </td>

                                    {{-- KOLOM 2: PETUGAS --}}
                                    <td>
                                        <span class="badge bg-secondary">{{ $task->user->name }}</span>
                                    </td>

                                    {{-- KOLOM 3: STATUS --}}
                                    <td>
                                        @if($task->is_done)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                                <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1">
                                                <i class="bi bi-hourglass-split me-1"></i> Pending
                                            </span>
                                        @endif
                                    </td>

                                    {{-- KOLOM 4: AKSI --}}
                                    <td>
                                        <div class="d-flex gap-1">
                                            {{-- LOGIKA 1: JIKA KETUA -> TOMBOL EDIT --}}
                                            @if($isKetua)
                                                <button type="button" 
                                                        class="btn btn-warning btn-sm text-white shadow-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEditTask-{{ $task->id }}"
                                                        title="Edit Tugas">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            @endif

                                            {{-- LOGIKA 2: TOMBOL SELESAI / LIHAT BUKTI (Kode Lama) --}}
                                            @if($task->is_done)
                                                @if($task->image_proof)
                                                    <a href="{{ asset('storage/' . $task->image_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-image"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted small ms-1">-</span>
                                                @endif
                                            @else
                                                {{-- Tombol Lapor Selesai (Hanya utk Petugas ybs) --}}
                                                @if(Auth::id() == $task->user_id)
                                                    <button type="button" 
                                                            class="btn btn-primary btn-sm shadow-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalFinishTask-{{ $task->id }}">
                                                        <i class="bi bi-camera-fill"></i>
                                                    </button>
                                                @elseif(!$isKetua)
                                                    {{-- Kalau bukan ketua & bukan petugas ybs --}}
                                                    <span class="text-muted small fst-italic">Pending</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-clipboard-x fs-1 d-block mb-2 opacity-50"></i>
                                        Tidak ada tugas ditemukan.
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

    {{-- LOOPING KHUSUS MODAL LAPOR SELESAI --}}
    @foreach($tasks as $task)
        @if(!$task->is_done && Auth::id() == $task->user_id)
        <div class="modal fade" id="modalFinishTask-{{ $task->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"> 
                <div class="modal-content shadow-lg"> 
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="modalLabel-{{ $task->id }}">
                            <i class="bi bi-camera-fill me-2"></i>Lapor Selesai
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return konfirmasiKirim(this)">
                        @csrf
                        @method('PATCH')
                        
                        <div class="modal-body bg-white text-dark"> 
                            <div class="mb-3 text-center">
                                <h6 class="fw-bold text-dark">{{ $task->title }}</h6>
                                <p class="small text-muted">Upload bukti pekerjaan Anda.</p>
                            </div>

                            <div class="alert alert-info py-2 small">
                                <i class="bi bi-info-circle me-1"></i> Maksimal ukuran foto <strong>1 MB</strong>.
                            </div>

                            {{-- Input Foto --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Bukti Foto <span class="text-danger">*</span></label>
                                <input type="file" name="image_proof" class="form-control" required accept="image/*">
                                <div class="form-text">Format: JPG/PNG. Max: 1MB.</div>
                            </div>

                            {{-- Input Catatan --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Catatan (Opsional)</label>
                                <textarea name="completion_note" class="form-control" rows="3" placeholder="Tulis keterangan disini..."></textarea>
                            </div>
                        </div>
                        
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success btn-sm fw-bold">
                                <i class="bi bi-send me-1"></i> Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    {{-- LOOPING MODAL EDIT TASK (KHUSUS KETUA) --}}
    @if($isKetua)
        @foreach($tasks as $task)
        <div class="modal fade" id="modalEditTask-{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-pencil-square me-2"></i>Edit Tugas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Wajib untuk Update --}}
                        
                        <div class="modal-body bg-white text-dark">
                            {{-- Judul --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Judul Tugas</label>
                                <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
                            </div>

                            {{-- Petugas --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Petugas</label>
                                <select name="user_id" class="form-select" required>
                                    @foreach($event->users as $u)
                                        @if($u->pivot->role == 'petugas')
                                            <option value="{{ $u->id }}" {{ $task->user_id == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" required>{{ $task->description }}</textarea>
                            </div>
                        </div>
                        
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning btn-sm fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endif

    {{-- SCRIPTS KHUSUS HALAMAN INI --}}
    <script>
        // FUNGSI KONFIRMASI SEBELUM KIRIM
        function konfirmasiKirim(form) {
            // 1. Cari input file di dalam form yang sedang disubmit
            console.log("test");
            let inputFoto = form.querySelector('input[name="image_proof"]');
            
            // 2. Cek apakah ada file yang dipilih
            if (inputFoto.files.length > 0) {
                let namaFile = inputFoto.files[0].name;
                let ukuranFile = (inputFoto.files[0].size / 1024).toFixed(2); // dalam KB

                // 3. Tampilkan Pesan Konfirmasi
                let pesan = `Konfirmasi Upload:\n\n` +
                            `📂 Nama File: ${namaFile}\n` +
                            `⚖️ Ukuran: ${ukuranFile} KB\n\n` +
                            `Apakah Anda yakin file ini sudah benar?`;
                
                // Jika user klik OK, return true (Form dikirim)
                // Jika user klik Cancel, return false (Form batal dikirim)
                return confirm(pesan);
            }

            // Jika tidak ada file (tapi harusnya dicek required di HTML), loloskan saja
            return true;
        }

        // 1. Logic Toggle Form Tambah Task
        function toggleAddTaskForm() {
            const form = document.getElementById('add-task-form');
            if (form) {
                form.style.display = (form.style.display === 'none') ? 'block' : 'none';
            }
        }

        // 2. Logic Live Search (AJAX) untuk Tambah Member
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('member-search');
            const resultsDiv = document.getElementById('member-search-results');
            const userIdInput = document.getElementById('member-user-id');
            const addButton = document.getElementById('btn-add-member');
            const modals = document.querySelectorAll('.modal');

            modals.forEach(modal => {
                document.body.appendChild(modal);
                modal.addEventListener('hidden.bs.modal', () => {
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                });
            });

            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    let query = this.value;
                    let eventId = "{{ $event->id }}";
 
                    if(query.length < 2) {
                        resultsDiv.style.display = 'none';
                        return;
                    }

                    // Panggil Route AJAX
                    fetch(`/ajax/users/search?q=${query}&event_id=${eventId}`)
                    .then(response => response.json())
                    .then(data => {
                            resultsDiv.innerHTML = '';
                            if(data.length > 0) {
                                resultsDiv.style.display = 'block';
                                data.forEach(user => {
                                    let item = document.createElement('a');
                                    item.classList.add('list-group-item', 'list-group-item-action');
                                    item.style.cursor = 'pointer';
                                    item.innerHTML = `<strong>${user.name}</strong> <br> <small class="text-muted">${user.email}</small>`;
                                    
                                    // Saat user diklik
                                    item.addEventListener('click', function() {
                                        searchInput.value = user.name;      // Isi nama di input text
                                        userIdInput.value = user.id;        // Isi ID di input hidden
                                        resultsDiv.style.display = 'none';  // Sembunyikan dropdown
                                        addButton.disabled = false;         // Aktifkan tombol tambah
                                    });

                                    resultsDiv.appendChild(item);
                                });
                            } else {
                                resultsDiv.style.display = 'none';
                            }
                        })
                        .catch(err => console.error(err));
                });

                // Sembunyikan dropdown kalau klik di luar
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                        resultsDiv.style.display = 'none';
                    }
                });
            }
        });
    </script>

</x-app-layout>