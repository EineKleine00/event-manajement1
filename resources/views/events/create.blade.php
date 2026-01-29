<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Event Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                
                <form action="{{ route('events.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Event</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Pensi Sekolah 2026">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Pelaksanaan</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Event</label>
                        <textarea name="description" class="form-control" rows="4" required placeholder="Jelaskan detail acara..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan & Buat Event</button>
                        <a href="{{ route('events.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>