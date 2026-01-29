<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Event Saya') }}
            </h2>
            <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">+ Buat Event Baru</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                
                @if($events->count() > 0)
                    <div class="row">
                        @foreach($events as $event)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title fw-bold">{{ $event->name }}</h5>
                                        <span class="badge bg-secondary">{{ $event->date }}</span>
                                    </div>
                                    <p class="card-text text-muted small mt-2">{{ Str::limit($event->description, 100) }}</p>
                                    
                                    <div class="mt-3 d-flex gap-2">
                                        <span class="badge bg-info text-dark">{{ $event->users_count }} Anggota</span>
                                        <span class="badge bg-warning text-dark">{{ $event->tasks_count }} Task</span>
                                    </div>

                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary btn-sm">Kelola Detail</a>
                                        
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus event ini?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted py-5">Belum ada event yang dibuat.</p>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>