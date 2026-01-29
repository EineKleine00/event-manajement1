<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Event Management') }}</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- SCRIPT TEMA (COPY-PASTE YG DARI NAVIGATION TADI AGAR SAMA) --}}
        <script>
            const getStoredTheme = () => localStorage.getItem('theme')
            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme()
                if (storedTheme) return storedTheme
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
            }
            const setTheme = theme => {
                if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.setAttribute('data-bs-theme', 'dark')
                } else {
                    document.documentElement.setAttribute('data-bs-theme', theme)
                }
            }
            setTheme(getPreferredTheme())
        </script>
    </head>
    <body class="font-sans antialiased bg-body-tertiary text-body">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-5">
            <div class="mb-4">
                <a href="/" class="text-decoration-none d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-event-fill fs-1 text-primary"></i>
                    <span class="fs-2 fw-bold text-body-emphasis">EventApp</span>
                </a>
            </div>

            <div class="w-100 p-4 card shadow-sm border-0" style="max-width: 420px;">
                <div class="card-body">
                    {{ $slot }}
                </div>
            </div>
            
            <div class="mt-4 text-body-secondary small">
                &copy; {{ date('Y') }} Event Management System
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>