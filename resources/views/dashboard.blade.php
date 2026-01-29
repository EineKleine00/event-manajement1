<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            {{-- Gunakan text-body-emphasis agar tebal & adaptif --}}
            <h2 class="font-semibold text-xl text-body-emphasis leading-tight">
                {{ __('Overview') }}
            </h2>
            <span class="text-body-secondary small">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            
            {{-- WELCOME BANNER (Tetap Primary karena bagus di kedua mode) --}}
            <div class="card border-0 shadow-sm bg-primary text-white mb-4">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-1">Halo, {{ Auth::user()->name }}! 👋</h4>
                        <p class="mb-0 opacity-75">Selamat datang kembali. Tema tampilan menyesuaikan preferensi Anda.</p>
                    </div>
                    <div class="d-none d-md-block opacity-50">
                        <i class="bi bi-laptop fs-1"></i>
                    </div>
                </div>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    {{-- Hapus bg-white/bg-dark. Biarkan default 'card' yang menangani --}}
                    <div class="card shadow-sm h-100 border-start border-4 border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-25 p-3 rounded-circle me-3">
                                    <i class="bi bi-person-workspace text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-body-secondary mb-0 small">Event Dikelola</h6>
                                    <h3 class="fw-bold mb-0 text-body">{{ $ketuaCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 border-start border-4 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-25 p-3 rounded-circle me-3">
                                    <i class="bi bi-clipboard-check text-success fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-body-secondary mb-0 small">Tugas Pending</h6>
                                    <h3 class="fw-bold mb-0 text-body">{{ $petugasTaskCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 border-start border-4 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-25 p-3 rounded-circle me-3">
                                    <i class="bi bi-graph-up-arrow text-warning fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-body-secondary mb-0 small">Event Disponsori</h6>
                                    <h3 class="fw-bold mb-0 text-body">{{ $sponsorCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- KOLOM KIRI: AGENDA --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm h-100">
                        {{-- Gunakan bg-body-tertiary untuk header yang sedikit beda warna --}}
                        <div class="card-header bg-body-tertiary fw-bold py-3 d-flex justify-content-between align-items-center">
                            <span class="text-body-emphasis"><i class="bi bi-calendar-week me-2 text-primary"></i> Agenda Mendatang</span>
                            <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                        <div class="list-group list-group-flush">
                            @forelse($upcomingEvents as $event)
                                <div class="list-group-item list-group-item-action px-4 py-3">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold text-body">{{ $event->name }}</h6>
                                            <small class="text-body-secondary">
                                                <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                            </small>
                                        </div>
                                        
                                        @if($event->created_by == Auth::id())
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">Ketua</span>
                                        @elseif($event->users->contains(Auth::id()))
                                            @php $role = $event->users->find(Auth::id())->pivot->role; @endphp
                                            <span class="badge bg-{{ $role == 'petugas' ? 'success' : 'warning' }} bg-opacity-10 text-body-emphasis border">
                                                {{ ucfirst($role) }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mb-1 text-body-secondary small mt-2">{{ Str::limit($event->description, 80) }}</p>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="bi bi-calendar-x text-body-tertiary fs-1 mb-3"></i>
                                    <p class="text-body-secondary small">Tidak ada agenda event dalam waktu dekat.</p>
                                    <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">Buat Event Sekarang</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: PRIORITAS TUGAS --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-body-tertiary fw-bold py-3">
                            <i class="bi bi-exclamation-circle me-2 text-danger"></i> Prioritas Tugas
                        </div>
                        <div class="card-body p-0">
                            @forelse($priorityTasks as $task)
                                <div class="p-3 border-bottom d-flex align-items-start hover-effect">
                                    <div class="me-3 mt-1">
                                        <div class="form-check">
                                            <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <input class="form-check-input" type="checkbox" onchange="this.form.submit()" style="cursor: pointer;" title="Tandai Selesai">
                                            </form>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-body" style="font-size: 0.95rem;">{{ $task->title }}</h6>
                                        <span class="badge bg-body-secondary text-body-secondary border small mb-1">{{ $task->event->name }}</span>
                                        <p class="text-body-secondary small mb-0">{{ Str::limit($task->description, 50) }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 px-3">
                                    <i class="bi bi-check-circle-fill text-success fs-1 mb-2 opacity-50"></i>
                                    <h6 class="fw-bold text-success">Kerja Bagus!</h6>
                                    <p class="text-body-secondary small">Tidak ada tugas pending saat ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>