<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-body-emphasis leading-tight">
            {{ __('Portal Sponsor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Info Banner --}}
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Halo, Sponsor!</strong>
                    <div class="small">Berikut adalah daftar event yang Anda sponsori. Anda dapat memantau progress dan mencetak laporan pertanggungjawaban.</div>
                </div>
            </div>

            <div class="row g-4">
                @forelse($events as $event)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-warning border-top-0 border-end-0 border-bottom-0 border-3 hover-effect">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold text-body-emphasis mb-0">{{ $event->name }}</h5>
                                    <span class="badge bg-warning text-dark border border-warning-subtle">Sponsor</span>
                                </div>
                                
                                <div class="text-body-secondary small mb-3">
                                    <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                </div>

                                <p class="text-body-secondary small mb-4 flex-grow-1">
                                    {{ Str::limit($event->description, 100) }}
                                </p>
                                
                                {{-- AREA TOMBOL AKSI --}}
                                <div class="d-grid gap-2 mt-auto">
                                    {{-- 1. Tombol Lihat Progress (Detail) --}}
                                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-warning fw-bold shadow-sm">
                                        <i class="bi bi-eye-fill me-1"></i> Lihat Progress
                                    </a>

                                    {{-- 2. Tombol Cetak Laporan (BARU) --}}
                                    <a href="{{ route('events.report.pdf', $event->id) }}" target="_blank" class="btn btn-outline-secondary fw-bold">
                                        <i class="bi bi-printer-fill me-1"></i> Cetak Laporan
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 opacity-50">
                            <i class="bi bi-inbox fs-1 text-body-secondary"></i>
                        </div>
                        <h5 class="text-body-emphasis">Belum Ada Event</h5>
                        <p class="text-body-secondary">Anda belum terdaftar sebagai sponsor di event manapun.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>