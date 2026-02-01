<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- 1. JUDUL BROWSER DINAMIS (Ambil dari DB) --}}
        <title>{{ \App\Models\Setting::where('key', 'site_name')->value('value') ?? config('app.name', 'Event Management') }}</title>

        {{-- CSS & FONTS --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- VITE --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- SCRIPT JS BOOTSTRAP --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

        {{-- STYLE KHUSUS LOGIN/REGISTER --}}
        <style>
            /* Sedikit CSS tambahan biar backgroundnya makin mantap */
            body {
                background: #0f1012; /* Warna hitam elegan (bukan hitam mati) */
                background-image: radial-gradient(circle at top right, #1f2937 0%, #0f1012 100%);
            }
            /* Card Login biar agak transparan dikit */
            .card {
                background-color: rgba(33, 37, 41, 0.8) !important;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255,255,255,0.1) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-body">
        
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-5">
            
            {{-- LOGO & JUDUL --}}
            <div class="mb-4 text-center">
                <a href="/" class="text-decoration-none d-flex flex-column align-items-center gap-2">
                    {{-- Logo Icon Besar --}}
                    <div class="bg-primary bg-gradient p-3 rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                        <i class="bi bi-calendar-event-fill fs-2 text-white"></i>
                    </div>
                    
                    {{-- 2. NAMA APLIKASI DINAMIS (Ambil dari DB) --}}
                    <span class="fs-2 fw-bold text-white tracking-tight mt-2">
                        {{ \App\Models\Setting::where('key', 'site_name')->value('value') ?? 'EventApp' }}
                    </span>
                </a>
            </div>

            {{-- KOTAK FORM LOGIN/REGISTER --}}
            <div class="w-100 p-4 card shadow-lg rounded-4" style="max-width: 420px;">
                <div class="card-body">
                    {{ $slot }}
                </div>
            </div>
            
            {{-- 3. FOOTER DINAMIS (Ambil dari DB) --}}
            <div class="mt-5 text-secondary small opacity-50">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::where('key', 'site_name')->value('value') ?? 'EventApp' }}. Built with Laravel.
            </div>
        </div>
    </body>
</html>