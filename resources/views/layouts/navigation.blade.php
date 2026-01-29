<nav class="navbar navbar-expand-lg border-bottom shadow-sm bg-body-tertiary" style="z-index: 1055;">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">
            <i class="bi bi-calendar-event-fill me-2"></i>EventApp
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold text-primary' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                
                @auth
                <li class="nav-item dropdown ms-lg-2">
                    <a class="nav-link dropdown-toggle" href="#" id="portalDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-grid-fill me-1"></i> Akses Portal
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark border-secondary shadow" aria-labelledby="portalDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('events.index') }}">
                                <i class="bi bi-person-workspace text-primary me-2"></i> 
                                <div>
                                    <span class="d-block fw-bold">Ketua Acara</span>
                                    <small class="text-secondary" style="font-size: 10px;">Kelola Event</small>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('portal.petugas') }}">
                                <i class="bi bi-clipboard-check text-success me-2"></i>
                                <div>
                                    <span class="d-block fw-bold">Petugas</span>
                                    <small class="text-secondary" style="font-size: 10px;">Tugas Saya</small>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('portal.sponsor') }}">
                                <i class="bi bi-graph-up-arrow text-warning me-2"></i>
                                <div>
                                    <span class="d-block fw-bold">Sponsor</span>
                                    <small class="text-secondary" style="font-size: 10px;">Monitoring</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                
                <li class="nav-item">
                    <button class="btn btn-link nav-link py-2 px-0 px-lg-2" id="bd-theme" type="button">
                        <i class="bi bi-sun-fill" id="theme-icon-active" style="display: none;"></i>
                        <i class="bi bi-moon-stars-fill" id="theme-icon-dark" style="display: none;"></i>
                    </button>
                </li>

                @auth
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end border-0 shadow">
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </div>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Log in</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

