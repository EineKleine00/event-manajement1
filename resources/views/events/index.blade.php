<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-body mb-1" style="letter-spacing: -0.5px;">My Events</h2>
                <p class="text-secondary small mb-0">Kelola semua acara, panitia, dan tugas dalam satu tempat.</p>
            </div>
            <a href="{{ route('events.create') }}" class="btn btn-primary fw-bold shadow-sm rounded-3">
                <i class="bi bi-plus-lg me-1"></i> Buat Event Baru
            </a>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            
            {{-- ALERT SUKSES --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-start border-success border-4 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                        <div>
                            <strong>Berhasil!</strong> {{ session('success') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($events->count() > 0)
                <div class="row g-4">
                    @foreach($events as $event)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative card-hover transition-all">
                            
                            {{-- Status Bar Warna --}}
                            <div class="position-absolute top-0 start-0 bottom-0 bg-primary" style="width: 4px;"></div>

                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                    </div>
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary p-0 no-arrow" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical fs-5"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3">
                                            <li><a class="dropdown-item" href="{{ route('events.edit', $event->id) }}"><i class="bi bi-pencil me-2 text-warning"></i> Edit Info</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                {{-- PERHATIKAN ONCLICK DI SINI --}}
                                                <button type="button" class="dropdown-item text-danger" 
                                                        onclick="openDeleteModal('{{ $event->id }}', '{{ $event->name }}')">
                                                    <i class="bi bi-trash me-2"></i> Hapus Event
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <h5 class="card-title fw-bold text-body mb-2 text-truncate" title="{{ $event->name }}">
                                    {{ $event->name }}
                                </h5>
                                <p class="card-text text-secondary small mb-4 flex-grow-1" style="line-height: 1.6;">
                                    {{ Str::limit($event->description, 120, '...') }}
                                </p>

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="d-flex align-items-center text-secondary small">
                                        <i class="bi bi-people-fill me-2 fs-5 text-info"></i>
                                        <span class="fw-bold text-body">{{ $event->users_count }}</span>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="d-flex align-items-center text-secondary small">
                                        <i class="bi bi-list-check me-2 fs-5 text-warning"></i>
                                        <span class="fw-bold text-body">{{ $event->tasks_count }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary w-100 rounded-3 fw-semibold py-2">
                                    Kelola Event <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <div class="bg-body-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="bi bi-calendar-x text-secondary" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-body">Belum ada Event</h4>
                    <p class="text-secondary mb-4" style="max-width: 400px; margin: 0 auto;">
                        Mulai kelola kepanitiaan Anda dengan membuat event pertama sekarang.
                    </p>
                    <a href="{{ route('events.create') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Buat Event Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- 
        =========================================================
        SOLUSI UTAMA: MODAL DIPINDAH KE SINI (LUAR CONTAINER UTAMA)
        =========================================================
        Dengan menaruh modal di paling bawah (sebelum closing tag),
        dia tidak akan tertutup oleh overflow/z-index elemen lain.
    --}}
    
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2 text-body">Hapus Event?</h5>
                    <p class="text-secondary small mb-4">
                        Anda akan menghapus event <strong id="deleteEventName" class="text-danger"></strong>.<br>
                        Semua data tugas dan panitia terkait akan ikut terhapus permanen.
                    </p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                        
                        <form id="deleteForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger fw-bold px-4">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS & JS --}}
    <style>
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
        .no-arrow::after { display: none; }
    </style>

    <script>
        // FIX: Pindahkan modal ke body agar tidak tertutup backdrop (masalah z-index parent)
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                document.body.appendChild(deleteModal);
            }
        });

        // Fungsi JS yang lebih aman
        function openDeleteModal(id, name) {
            // 1. Update Text
            document.getElementById('deleteEventName').innerText = '"' + name + '"';
            
            // 2. Update URL Action
            let url = "{{ route('events.destroy', ':id') }}";
            url = url.replace(':id', id);
            document.getElementById('deleteForm').action = url;
            
            // 3. Tampilkan Modal (Cara Bootstrap 5 yang benar)
            // Menggunakan getOrCreateInstance agar tidak error tumpuk-tumpuk
            const myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal'));
            myModal.show();
        }
    </script>
</x-app-layout>