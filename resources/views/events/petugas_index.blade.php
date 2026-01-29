<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Portal Petugas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="row">
                @forelse($events as $event)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-success border-2">
                            <div class="card-body">
                                <h5 class="fw-bold">{{ $event->name }}</h5>
                                <p class="small text-muted">{{ $event->date }}</p>
                                <hr>
                                
                                @if($event->tasks_count > 0)
                                    <div class="alert alert-warning py-2 small fw-bold">
                                        ⚠ {{ $event->tasks_count }} Tugas Menunggu Anda
                                    </div>
                                @else
                                    <div class="alert alert-success py-2 small fw-bold">
                                        ✔ Semua Tugas Selesai
                                    </div>
                                @endif

                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-success w-100 mt-2">Masuk & Kerjakan</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Anda belum terdaftar sebagai petugas di event manapun.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>