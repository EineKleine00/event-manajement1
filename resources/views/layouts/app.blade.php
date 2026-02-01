<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

       <title>{{ \App\Models\Setting::where('key', 'site_name')->value('value') ?? 'EventApp' }}</title>

        {{-- CSS & FONTS --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- JS BOOTSTRAP (Pindahkan ke Head + Defer agar tidak double load saat navigasi) --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- SCRIPT 1: APPLY TEMA SECEPAT KILAT (ANTI-FLICKER) --}}
        <script>
            (function() {
                try {
                    const storedTheme = localStorage.getItem('theme');
                    const getPreferredTheme = () => {
                        if (storedTheme) return storedTheme;
                        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    };
                    document.documentElement.setAttribute('data-bs-theme', getPreferredTheme());
                } catch (e) {}
            })();
        </script>

        <style>
            .navbar { position: relative; z-index: 1040; }
            .modal { z-index: 1055; }
        </style>
    </head>
    <body class="font-sans antialiased">
        
        <div class="min-vh-100 bg-body-tertiary">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-body border-bottom mb-4 shadow-sm">
                    <div class="container py-3">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="container py-4" style="position: relative; z-index: 1;">
                {{ $slot }}
            </main>
        </div>

        {{-- SCRIPT 2: LOGIC TOMBOL & SYNC ICON --}}
        <script>
            // Jalankan langsung (karena sudah di bawah body, elemen pasti ada)
            (function() {
                const toggleBtn = document.getElementById('dark-mode-toggle');
                const icon = document.getElementById('theme-icon');
                const html = document.documentElement;

                // Fungsi Update Ikon
                function updateIcon() {
                    if (!icon) return;
                    const currentTheme = html.getAttribute('data-bs-theme');
                    if (currentTheme === 'dark') {
                        icon.classList.remove('bi-sun-fill');
                        icon.classList.add('bi-moon-stars-fill');
                    } else {
                        icon.classList.remove('bi-moon-stars-fill');
                        icon.classList.add('bi-sun-fill');
                    }
                }

                // 1. Jalankan saat halaman dimuat
                updateIcon();

                // 2. Event Listener Klik
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function() {
                        const newTheme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                        localStorage.setItem('theme', newTheme);
                        html.setAttribute('data-bs-theme', newTheme);
                        updateIcon();
                    });
                }
            })();
        </script>
    </body>
</html>