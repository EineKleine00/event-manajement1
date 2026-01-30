<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-body-emphasis leading-tight">
            {{ __('Portal Petugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Info Banner --}}
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                <i class="bi bi-person-badge-fill fs-4 me-3"></i>
                <div>
                    <strong>Halo, Petugas!</strong>
                    <div class="small">Pilih event di bawah ini untuk mulai mengerjakan tugas Anda.</div>
                </div>
            </div>

            <div class="row g-4">
                {{-- LOOPING DATA EVENTS --}}
                @forelse($events as $event)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-success border-top-0 border-end-0 border-bottom-0 border-3 hover-effect">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold text-body-emphasis mb-0">{{ $event->name }}</h5>
                                </div>
                                
                                <div class="text-body-secondary small mb-3">
                                    <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                </div>

                                <p class="text-body-secondary small mb-3 flex-grow-1">
                                    {{ Str::limit($event->description, 80) }}
                                </p>
                                
                                <hr class="border-secondary-subtle">

                                {{-- Status Tugas (Dari withCount di Controller) --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-body-secondary">Tugas Pending:</small>
                                    @if($event->tasks_count > 0)
                                        <span class="badge bg-danger rounded-pill">{{ $event->tasks_count }} Tugas</span>
                                    @else
                                        <span class="badge bg-success rounded-pill">Selesai Semua</span>
                                    @endif
                                </div>

                                {{-- Tombol Aksi --}}
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-success fw-bold w-100 shadow-sm mt-auto">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk & Kerjakan
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 opacity-50">
                            <i class="bi bi-clipboard-x fs-1 text-body-secondary"></i>
                        </div>
                        <h5 class="text-body-emphasis">Belum Ada Tugas</h5>
                        <p class="text-body-secondary">Anda belum terdaftar sebagai petugas di event manapun saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>