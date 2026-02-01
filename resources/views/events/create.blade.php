<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-body mb-1" style="letter-spacing: -0.5px;">Buat Event Baru</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('events.index') }}" class="text-decoration-none">Events</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    
                    {{-- CARD FORMULIR --}}
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        {{-- Hiasan Garis Atas --}}
                        <div class="card-header bg-primary p-1 border-0"></div>
                        
                        <div class="card-body p-5">
                            
                            <h4 class="fw-bold mb-4">Detail Acara</h4>

                            <form action="{{ route('events.store') }}" method="POST">
                                @csrf

                                {{-- 1. NAMA EVENT --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-secondary text-uppercase ls-1">
                                        Nama Event <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-body-tertiary border-0 text-secondary ps-3">
                                            <i class="bi bi-fonts"></i>
                                        </span>
                                        <input type="text" name="name" 
                                               class="form-control form-control-lg bg-body-tertiary border-0 @error('name') is-invalid @enderror" 
                                               placeholder="Contoh: Pensi Sekolah 2026"
                                               value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- 2. TANGGAL PELAKSANAAN --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-secondary text-uppercase ls-1">
                                        Tanggal Pelaksanaan <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-body-tertiary border-0 text-secondary ps-3">
                                            <i class="bi bi-calendar-event"></i>
                                        </span>
                                        <input type="date" name="date" 
                                               class="form-control form-control-lg bg-body-tertiary border-0 @error('date') is-invalid @enderror" 
                                               value="{{ old('date') }}" required>
                                        @error('date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text text-muted small ms-1">
                                        Pilih tanggal utama kapan acara dimulai.
                                    </div>
                                </div>

                                {{-- 3. DESKRIPSI --}}
                                <div class="mb-5">
                                    <label class="form-label fw-bold small text-secondary text-uppercase ls-1">
                                        Deskripsi & Detail <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description" rows="5" 
                                              class="form-control bg-body-tertiary border-0 p-3 @error('description') is-invalid @enderror" 
                                              placeholder="Jelaskan tujuan acara, target audiens, dan detail lainnya..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- TOMBOL AKSI --}}
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-3 border-top">
                                    <a href="{{ route('events.index') }}" class="btn btn-light btn-lg rounded-3 fw-bold px-4 text-secondary">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold px-4 shadow-sm">
                                        <i class="bi bi-check-lg me-1"></i> Simpan Event
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- CSS CUSTOM --}}
    <style>
        .ls-1 { letter-spacing: 0.5px; }
        
        /* Style Input Group biar nyatu & borderless */
        .input-group-text {
            background-color: var(--bs-body-bg-tertiary);
        }
        
        /* Efek Fokus Input */
        .form-control:focus {
            box-shadow: none;
            background-color: var(--bs-body-bg); 
            border: 2px solid var(--bs-primary) !important;
        }
        /* Fix focus di input group */
        .form-control:focus + .input-group-text, 
        .input-group-text:has(+ .form-control:focus) {
            border-color: var(--bs-primary);
        }
    </style>
</x-app-layout>