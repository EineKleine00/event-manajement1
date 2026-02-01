<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-body mb-1">Konfigurasi Sistem</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            
            {{-- Alert Sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-start border-success border-4 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                {{-- Form Setting --}}
                <div class="col-md-8">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="card-header bg-body border-bottom p-4">
                            <h6 class="fw-bold mb-0"><i class="bi bi-sliders me-2 text-primary"></i>Pengaturan Global</h6>
                        </div>
                        <div class="card-body p-4">
                            
                            <form action="{{ route('admin.settings.update') }}" method="POST">
                                @csrf @method('PUT')

                                @foreach($settings as $setting)
                                    <div class="mb-4 row align-items-center">
                                        <label class="col-sm-4 col-form-label fw-bold text-secondary small text-uppercase">
                                            {{ $setting->label }}
                                        </label>
                                        <div class="col-sm-8">
                                            
                                            {{-- Tipe 1: Text / Number --}}
                                            @if($setting->type == 'text' || $setting->type == 'number')
                                                <input type="{{ $setting->type }}" name="{{ $setting->key }}" 
                                                       class="form-control bg-body-tertiary border-0" 
                                                       value="{{ $setting->value }}" required>
                                            
                                            {{-- Tipe 2: Switch (Boolean) --}}
                                            @elseif($setting->type == 'boolean')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" 
                                                           name="{{ $setting->key }}" value="1" 
                                                           {{ $setting->value == '1' ? 'checked' : '' }}
                                                           style="width: 3em; height: 1.5em; cursor: pointer;">
                                                </div>
                                                <div class="form-text small mt-1">
                                                    Status: <strong>{{ $setting->value == '1' ? 'AKTIF' : 'NON-AKTIF' }}</strong>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-end pt-3 border-top">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light text-secondary me-2">Kembali</a>
                                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3 shadow-sm">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                
                {{-- Panel Informasi (Kanan) --}}
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-primary text-white mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Informasi</h5>
                            <p class="small opacity-75 mt-3" style="line-height: 1.6;">
                                Pengaturan ini bersifat global. Perubahan akan langsung berdampak pada seluruh pengguna aplikasi.
                            </p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Panduan Singkat</h6>
                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item px-0 border-0">
                                    <strong>Site Name:</strong> Mengubah nama di tab browser.
                                </li>
                                <li class="list-group-item px-0 border-0">
                                    <strong>Maintenance Mode:</strong> Menutup akses login user biasa.
                                </li>
                                <li class="list-group-item px-0 border-0">
                                    <strong>Registrasi:</strong> Membuka/tutup pendaftaran member baru.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>