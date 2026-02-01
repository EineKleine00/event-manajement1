<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance - {{ config('app.name') }}</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* 1. PALET WARNA KHUSUS (EYE COMFORT) */
        :root {
            --bg-color: #0f172a;        /* Slate 900 (Gelap tapi lembut) */
            --card-bg: #1e293b;         /* Slate 800 */
            --text-main: #f1f5f9;       /* Slate 100 (Putih lembut) */
            --text-muted: #94a3b8;      /* Slate 400 (Abu kebiruan) */
            --accent: #38bdf8;          /* Sky Blue (Aksen segar) */
        }

        body, html {
            height: 100%;
            margin: 0;
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            overflow: hidden; /* Mencegah scroll yang tidak perlu */
        }

        /* 2. EFEK GLASSMORPHISM (Kaca Buram) */
        .maintenance-card {
            background-color: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05); /* Garis tepi tipis */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); /* Bayangan lembut */
            border-radius: 1.5rem;
            position: relative;
            z-index: 10;
        }

        /* 3. HIASAN BACKGROUND (GLOW EFFECT) */
        .glow {
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--accent);
            filter: blur(150px);
            opacity: 0.15;
            border-radius: 50%;
            z-index: 1;
        }
        .glow-1 { top: -10%; left: -10%; }
        .glow-2 { bottom: -10%; right: -10%; }

        /* 4. TEXT STYLING */
        h1 {
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        p {
            color: var(--text-muted);
            line-height: 1.6;
        }
        
        /* 5. LOGOUT BOX */
        .user-box {
            background: rgba(15, 23, 42, 0.5); /* Lebih gelap dari card */
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center position-relative">

    {{-- Efek Cahaya Latar Belakang --}}
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>

    <div class="container text-center position-relative" style="z-index: 10;">
        
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                {{-- KARTU UTAMA --}}
                <div class="maintenance-card p-5">
                    
                    {{-- Ikon --}}
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary" style="width: 80px; height: 80px;">
                            <i class="bi bi-tools" style="font-size: 2.5rem; color: var(--accent);"></i>
                        </div>
                    </div>

                    {{-- Judul & Deskripsi --}}
                    <h1 class="mb-3">Sistem Dalam Pemeliharaan</h1>
                    <p class="mb-5 lead fs-6">
                        Mohon maaf atas ketidaknyamanan ini. Kami sedang melakukan pembaruan sistem untuk meningkatkan kinerja dan keamanan. Silakan kembali lagi nanti.
                    </p>

                    {{-- Tombol Logout --}}
                    @auth
                        <div class="user-box p-3 d-inline-block w-100">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-start">
                                    <small class="d-block text-secondary" style="font-size: 0.75rem;">Login sebagai:</small>
                                    <div class="fw-bold text-white">{{ Auth::user()->name }}</div>
                                </div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm rounded-3 fw-bold px-3 py-2 shadow-sm">
                                        Logout <i class="bi bi-box-arrow-right ms-1"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    <div class="mt-4 pt-3 border-top border-secondary border-opacity-10">
                        <small style="color: var(--text-muted); font-size: 0.75rem;">
                            &copy; {{ date('Y') }} {{ \App\Models\Setting::where('key', 'site_name')->value('value') ?? 'EventApp' }}. All rights reserved.
                        </small>
                    </div>

                </div>

            </div>
        </div>

    </div>

</body>
</html>