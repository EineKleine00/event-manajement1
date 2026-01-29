<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ALERT SUKSES/ERROR --}}
            @if(session('success'))
                <div class="alert alert-success mb-3">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- 1. INFO EVENT --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">Deskripsi Event</h5>
                    <p class="text-muted">{{ $event->description }}</p>
                    <p><strong><i class="bi bi-calendar-event me-2"></i>Tanggal:</strong> {{ $event->date }}</p>

                    @if($isKetua)
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square me-1"></i> Edit Event
                        </a>
                    @endif
                </div>
            </div>

            {{-- 2. MEMBER MANAGEMENT (Khusus Ketua) --}}
            @if($isKetua)
            <div class="card mb-4 shadow-sm border-primary border-top-0 border-end-0 border-bottom-0 border-3">
                <div class="card-header bg-white fw-bold">
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
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
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
                    <div id="add-task-form" class="card bg-light mb-4 border-0 p-3" style="display: none;">
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
                                    <small class="text-muted" style="font-size: 10px;">*Hanya user dengan role Petugas yg muncul</small>
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
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Task</th>
                                    <th>Petugas</th>
                                    <th>Status</th>
                                    @if($isKetua) <th width="150">Aksi</th> @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    // Menggabungkan pending & completed tasks dari controller agar jadi satu list
                                    $allTasks = $pendingTasks->merge($completedTasks); 
                                @endphp

                                @forelse($allTasks as $task)
                                <tr>
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        <div class="small text-muted">{{ $task->description }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $task->user->name }}</span>
                                    </td>
                                    <td>
                                        @if($task->is_done)
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    
                                    {{-- AKSI KETUA --}}
                                    @if($isKetua)
                                    <td>
                                        {{-- Tombol Toggle Edit --}}
                                        <button class="btn btn-info btn-sm text-white py-0 px-2 mb-1" onclick="toggleEditRow({{ $task->id }})">
                                            Edit
                                        </button>

                                        {{-- Tombol Delete --}}
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus task ini?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm py-0 px-2 mb-1">Hapus</button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>

                                {{-- ROW EDIT TASK (HIDDEN) --}}
                                @if($isKetua)
                                <tr id="edit-row-{{ $task->id }}" class="bg-light" style="display: none;">
                                    <td colspan="4">
                                        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="row g-2 align-items-center">
                                                <div class="col-md-5">
                                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $task->title }}" required>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="description" class="form-control form-control-sm" value="{{ $task->description }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-success btn-sm w-100">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endif

                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada task.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPTS KHUSUS HALAMAN INI --}}
    <script>
        // 1. Logic Toggle Form
        function toggleAddTaskForm() {
            const form = document.getElementById('add-task-form');
            form.style.display = (form.style.display === 'none') ? 'block' : 'none';
        }

        function toggleEditRow(taskId) {
            const row = document.getElementById('edit-row-' + taskId);
            row.style.display = (row.style.display === 'none') ? 'table-row' : 'none';
        }

        // 2. Logic Live Search (AJAX) untuk Tambah Member
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('member-search');
            const resultsDiv = document.getElementById('member-search-results');
            const userIdInput = document.getElementById('member-user-id');
            const addButton = document.getElementById('btn-add-member');

            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    let query = this.value;
                    
                    if(query.length < 2) {
                        resultsDiv.style.display = 'none';
                        return;
                    }

                    // Panggil Route AJAX yang sudah kita buat di web.php
                    fetch(`/ajax/users/search?q=${query}`)
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
                        });
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