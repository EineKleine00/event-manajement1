<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
    <div class="container">
        
        {{-- ========================================================= --}}
        {{-- 1. LOGO & BRAND (Dinamis dari Database Settings)          --}}
        {{-- ========================================================= --}}
        <a class="navbar-brand fw-bold text-primary d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <i class="bi bi-calendar-event-fill fs-4"></i>
            {{-- Mengambil nama dari DB, kalau gagal default ke 'EventApp' --}}
            <span>{{ \App\Models\Setting::where('key', 'site_name')->value('value') ?? 'EventApp' }}</span>
        </a>

        {{-- HAMBURGER MENU (MOBILE TOGGLER) --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- ISI NAVBAR --}}
        <div class="collapse navbar-collapse" id="navbarContent">
            
            {{-- ========================================================= --}}
            {{-- 2. MENU UTAMA (KIRI) - DIPISAH BERDASARKAN ROLE           --}}
            {{-- ========================================================= --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                
                {{-- A. JIKA YANG LOGIN ADALAH SUPER ADMIN --}}
                @if(Auth::user()?->user_role === 'admin')
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-bold text-primary' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                           <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active fw-bold text-primary' : '' }}" 
                           href="{{ route('users.index') }}">
                           <i class="bi bi-people me-1"></i> Users
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active fw-bold text-primary' : '' }}" 
                           href="{{ route('admin.settings.index') }}">
                           <i class="bi bi-sliders me-1"></i> Konfigurasi
                        </a>
                    </li>

                {{-- B. JIKA YANG LOGIN ADALAH USER BIASA --}}
                @else
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold text-primary' : '' }}" 
                           href="{{ route('dashboard') }}">
                           <i class="bi bi-grid me-1"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('events.*') && !request()->routeIs('events.report.*') ? 'active fw-bold text-primary' : '' }}" 
                           href="{{ route('events.index') }}">
                           <i class="bi bi-calendar-week me-1"></i> My Events
                        </a>
                    </li>

                    {{-- Dropdown Portal --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('portal.*') ? 'active fw-bold text-primary' : '' }}" 
                           href="#" role="button" data-bs-toggle="dropdown">
                           <i class="bi bi-clipboard-check me-1"></i> Portal Tugas
                        </a>
                        <ul class="dropdown-menu border-0 shadow-sm rounded-3">
                            <li>
                                <a class="dropdown-item" href="{{ route('portal.petugas') }}">
                                    <i class="bi bi-person-badge me-2 text-info"></i>Sebagai Petugas
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('portal.sponsor') }}">
                                    <i class="bi bi-currency-dollar me-2 text-warning"></i>Sebagai Sponsor
                                </a>
                            </li>
                        </ul>
                    </li>

                @endif
            </ul>

            {{-- ========================================================= --}}
            {{-- 3. TOOLS & PROFILE (KANAN)                                --}}
            {{-- ========================================================= --}}
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-2">
                
                {{-- TOMBOL TOGGLE THEME (DARK MODE) --}}
                <li class="nav-item">
                    <button class="btn btn-link nav-link px-2 text-secondary transition-hover" id="bd-theme-toggle" onclick="toggleTheme()" title="Ganti Tema">
                        {{-- Icon Bulan (Muncul pas mode Terang) --}}
                        <i class="bi bi-moon-stars-fill" id="theme-icon-moon"></i>
                        {{-- Icon Matahari (Muncul pas mode Gelap) --}}
                        <i class="bi bi-sun-fill d-none" id="theme-icon-sun"></i>
                    </button>
                </li>

                {{-- BADGE ROLE --}}
                <li class="nav-item d-none d-lg-block">
                    @if(Auth::user()?->user_role === 'admin')
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                            <i class="bi bi-shield-lock-fill me-1"></i> SUPER ADMIN
                        </span>
                    @else
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">
                            <i class="bi bi-person-fill me-1"></i> MEMBER
                        </span>
                    @endif
                </li>

                {{-- DROPDOWN USER PROFILE --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="text-end d-none d-lg-block" style="line-height: 1.2;">
                            <div class="fw-bold small text-body">{{ Auth::user()->name }}</div>
                            <div class="text-secondary" style="font-size: 0.7rem;">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="avatar-circle bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2" style="min-width: 200px;">
                        
                        {{-- Header untuk Tampilan Mobile --}}
                        <li class="d-lg-none text-center pb-2 border-bottom mb-2">
                            <div class="fw-bold">{{ Auth::user()->name }}</div>
                            <small class="text-secondary text-uppercase">{{ Auth::user()->user_role }}</small>
                        </li>

                        <li>
                            <a class="dropdown-item rounded-3 py-2" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-gear me-2 opacity-50"></i> Profile Saya
                            </a>
                        </li>
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 text-danger fw-bold">
                                    <i class="bi bi-box-arrow-right me-2"></i> Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- ========================================================= --}}
{{-- 4. JAVASCRIPT & CSS UTAMA UNTUK TEMA GELAP/TERANG         --}}
{{-- ========================================================= --}}
<script>
    // 1. Ambil tema dari penyimpanan lokal atau preferensi sistem
    const storedTheme = localStorage.getItem('theme');
    const getPreferredTheme = () => {
        if (storedTheme) {
            return storedTheme;
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    // 2. Fungsi Menerapkan Tema ke Dokumen HTML
    const setTheme = function (theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        
        // Logika Ganti Icon Bulan/Matahari
        const iconMoon = document.getElementById('theme-icon-moon');
        const iconSun = document.getElementById('theme-icon-sun');

        if(theme === 'dark'){
            // Mode Gelap: Tampilkan Matahari
            iconMoon.classList.add('d-none');
            iconSun.classList.remove('d-none');
            iconSun.classList.add('text-warning'); 
        } else {
            // Mode Terang: Tampilkan Bulan
            iconSun.classList.add('d-none');
            iconMoon.classList.remove('d-none');
            iconMoon.classList.remove('text-warning');
        }
    }

    // 3. Eksekusi saat halaman dimuat
    setTheme(getPreferredTheme());

    // 4. Fungsi yang dipanggil saat tombol diklik
    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        localStorage.setItem('theme', newTheme); // Simpan pilihan user
        setTheme(newTheme);
    }
</script>

<style>
    /* Transisi Halus saat ganti warna */
    html, body, .navbar, .card, .dropdown-menu {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }
    
    /* Override Bootstrap: Paksa Navbar Putih jadi Gelap saat Dark Mode */
    [data-bs-theme="dark"] .bg-white {
        background-color: #212529 !important; /* Warna Dark Bootstrap */
        color: #f8f9fa !important;
        border-bottom-color: #373b3e !important;
    }
    
    /* Override warna text di mode gelap */
    [data-bs-theme="dark"] .text-dark {
        color: #f8f9fa !important;
    }

    /* Efek Hover di Navbar Link */
    .transition-hover:hover {
        transform: scale(1.1);
    }
</style>