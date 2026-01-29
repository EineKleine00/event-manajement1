<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Event Management') }}</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        
        {{-- SCRIPT TEMA (Anti-Flicker) --}}
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
            /* LANGKAH 2: PASTIKAN NAVBAR SELALU DI ATAS */
            .navbar {
                position: relative;
                z-index: 1050;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        
        <div class="min-vh-100 bg-body-tertiary">
            
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-body border-bottom mb-4 shadow-sm position-relative" style="z-index: 1000;">
                    <div class="container py-3">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="container position-relative" style="z-index: 1;">
                {{ $slot }}
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        {{-- SCRIPT INTERAKSI DARK MODE --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toggleBtn = document.getElementById('bd-theme');
                const iconLight = document.getElementById('theme-icon-active');
                const iconDark = document.getElementById('theme-icon-dark');

                // Fungsi aman update icon
                const showActiveIcon = (theme) => {
                    if(iconLight && iconDark) {
                        iconLight.style.display = (theme === 'light') ? 'inline-block' : 'none';
                        iconDark.style.display = (theme === 'dark') ? 'inline-block' : 'none';
                    }
                }

                if (toggleBtn) {
                    // Set awal
                    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                    showActiveIcon(currentTheme);

                    // Event Klik
                    toggleBtn.addEventListener('click', () => {
                        const current = document.documentElement.getAttribute('data-bs-theme');
                        const newTheme = current === 'dark' ? 'light' : 'dark';
                        localStorage.setItem('theme', newTheme);
                        document.documentElement.setAttribute('data-bs-theme', newTheme);
                        showActiveIcon(newTheme);
                    });
                }
            });
        </script>
    </body>
</html>