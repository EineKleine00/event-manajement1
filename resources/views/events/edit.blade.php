<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-body-emphasis leading-tight">
                {{ __('Edit Event') }}
            </h2>
            <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-body-tertiary fw-bold py-3">
                    <i class="bi bi-pencil-square me-2 text-warning"></i> Form Perubahan Data
                </div>
                
                <div class="card-body p-4">

                    {{-- Menampilkan Error Validasi (Jika ada) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('events.update', $event->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Wajib untuk Update --}}

                        {{-- 1. Nama Event --}}
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-body-secondary">Nama Event</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $event->name) }}" 
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 2. Tanggal & Waktu --}}
                        <div class="mb-4">
                            <label for="date" class="form-label fw-bold text-body-secondary">Tanggal Pelaksanaan</label>
                            <input type="date" 
                                   name="date" 
                                   id="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date', $event->date) }}" 
                                   required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 3. Deskripsi --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-body-secondary">Deskripsi Lengkap</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="5" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      required>{{ old('description', $event->description) }}</textarea>
                            <div class="form-text text-body-secondary">
                                Jelaskan detail acara, lokasi, dan informasi penting lainnya.
                            </div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4 border-secondary-subtle">

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-secondary px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Info Tambahan --}}
            <div class="mt-3 text-center text-body-secondary small">
                Event ID: {{ $event->id }} &bull; Dibuat pada: {{ $event->created_at->format('d M Y') }}
            </div>

        </div>
    </div>
</x-app-layout>