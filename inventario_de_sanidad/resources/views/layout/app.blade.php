<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'Sanidad')</title>

    <!-- Estilos globales -->
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout/layout.css') }}">

    <!-- Libreria de iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Sección para archivos CSS adicionales por página -->
    @stack('styles')

    <script src="{{ asset('js/darkmode.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
<div class="layout">
    <!-- Menú lateral (estático) -->
    <aside class="sidebar">
        <!-- Logo -->
        <div class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="logo">
        </div>

        <!-- Menú  -->
        <nav>
            <ul>
                <!-- Menú para Administrador -->
                @if(Auth::user()->user_type === 'admin')
                    <li class="has-submenu">
                        <a href="">
                            <i class="fa-solid fa-user"></i>
                            <span class="link-text">Gestión de usuarios</span>
                            <i class="fa-solid fa-chevron-down arrow-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('users.createUser') }}"
                                class="{{ request()->routeIs('users.createUser') ? 'active' : '' }}">
                                    <i class="fa-solid fa-user-plus"></i>
                                    <span class="link-text">Alta de usuario</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('users.management') }}"
                                class="{{ request()->routeIs('users.management') ? 'active' : '' }}">
                                    <i class="fa-solid fa-users-gear"></i>
                                    <span class="link-text">Gestión de usuarios</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="">
                            <i class="fa-solid fa-clipboard-list"></i>
                            <span class="link-text">Gestión de materiales</span>
                            <i class="fa-solid fa-chevron-down arrow-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('materials.create') }}"
                                class="{{ request()->routeIs('materials.create') ? 'active' : '' }}">
                                    <i class="fa-solid fa-plus"></i>
                                    <span class="link-text">Alta de material</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('materials.update.index') }}"
                                class="{{ request()->routeIs('materials.update.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-box-archive"></i>
                                    <span class="link-text">Gestionar material</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="">
                            <i class="fa-solid fa-book-bookmark"></i>
                            <span class="link-text">Reservas de materiales</span>
                            <i class="fa-solid fa-chevron-down arrow-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('historical.use') }}"
                                class="{{ request()->fullUrlIs(route('historical.use')) ? 'active' : '' }}">
                                    <i class="fa-solid fa-book-open"></i>
                                    <span class="link-text">Materiales en uso</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('historical.reserve') }}"
                                class="{{ request()->fullUrlIs(route('historical.reserve')) ? 'active' : '' }}">
                                    <i class="fa-solid fa-boxes-packing"></i>
                                    <span class="link-text">Materiales en reserva</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('historical.modificationsHistorical') }}"
                                class="{{ request()->routeIs('historical.modificationsHistorical') ? 'active' : '' }}">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                    <span class="link-text">Historial de modificaciones</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('qrcodes.index') }}"
                        class="{{ request()->routeIs('qrcodes.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-qrcode"></i>
                            <span class="link-text">Códigos QR</span>
                        </a>
                    </li>
                @endif

                <!-- Menú para Estudiantes -->
                @if(Auth::user()->user_type === 'student')

                    <li>
                        <a href="{{ route('activities.create') }}"
                        class="{{ request()->routeIs('activities.create') ? 'active' : '' }}">
                            <i class="fa-solid fa-pen"></i>
                            <span class="link-text">Registrar actividad</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('activities.history') }}"
                        class="{{ request()->routeIs('activities.history') ? 'active' : '' }}">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span class="link-text">Historial de actividades</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('historical.use') }}"
                        class="{{ request()->fullUrlIs(route('historical.use')) ? 'active' : '' }}">
                            <i class="fa-solid fa-book-open"></i>
                            <span class="link-text">Materiales en uso</span>
                        </a>
                    </li>
                @endif

                <!-- Menú para Docentes -->
                @if(Auth::user()->user_type === 'teacher')
                    <li>
                        <a href="{{ route('storages.updateView') }}"
                        class="{{ request()->routeIs('storages.updateView') ? 'active' : '' }}">
                            <i class="fa-solid fa-box-archive"></i>
                            <span class="link-text">Gestionar almacenamiento</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('historical.use') }}"
                        class="{{ request()->fullUrlIs(route('historical.use')) ? 'active' : '' }}">
                            <i class="fa-solid fa-book-open"></i>
                            <span class="link-text">Materiales en uso</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('activities.history') }}"
                        class="{{ request()->routeIs('activities.history') ? 'active' : '' }}">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span class="link-text">Actividades del alumnado</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </aside>

    
    <div class="main-area">
        <!-- Header  -->
        <header class="header">
            <div class="header-right">
                <!-- DarkMode Toggle -->
                <button class="btn btn-primary btn-notifications" id="theme-switch" type="button">
                    <i class="fa-solid fa-moon"></i>
                    <i class="fa-solid fa-sun"></i>
                </button>

                <!-- Notificaciones de alerta -->
                @php
                    use Illuminate\Support\Facades\Auth;
                    use Illuminate\Support\Facades\DB;

                    if (Auth::user()->user_type === 'admin') {
                        $use = DB::table('storage_use as su')
                            ->join('materials as m', 'm.material_id', '=', 'su.material_id')
                            ->join('storages as st', function ($join) {
                                $join->on('st.material_id', '=', 'su.material_id')
                                    ->on('st.storage', '=', 'su.storage');
                            })
                            ->whereColumn('su.units', '<', 'su.min_units')
                            ->select([
                                'm.name',
                                'su.material_id',
                                'su.storage',
                                DB::raw("'use' as type"),
                                'su.units',
                                'su.min_units'
                            ]);

                        $reserve = DB::table('storage_reserve as sr')
                            ->join('materials as m', 'm.material_id', '=', 'sr.material_id')
                            ->join('storages as st', function ($join) {
                                $join->on('st.material_id', '=', 'sr.material_id')
                                    ->on('st.storage', '=', 'sr.storage');
                            })
                            ->whereColumn('sr.units', '<', 'sr.min_units')
                            ->select([
                                'm.name',
                                'sr.material_id',
                                'sr.storage',
                                DB::raw("'reserve' as type"),
                                'sr.units',
                                'sr.min_units'
                            ]);

                        $notifications = DB::query()
                            ->fromSub($use->unionAll($reserve), 'notifications')
                            ->orderBy('material_id')
                            ->orderBy('storage')
                            ->orderBy('type')
                            ->get();
                    }
                @endphp

                <!-- Notificaciones -->

                @if(Auth::user()->user_type === 'admin')
                <div>
                    <div class="notifications-alert">
                        <button id="btn-notifications" class="btn btn-primary btn-notifications">
                            <i class="fa-solid fa-bell"></i>
                            @if($notifications->isNotEmpty())
                                <span id="notification-count" class="notification-count">{{ $notifications->count() }}</span>
                            @endif
                        </button>
                    </div>

                    <div id="notifications-list" class="notifications-list fade-in">
                        @if($notifications->isNotEmpty())
                            <h3>Notificaciones</h3>
                            <hr>

                            @foreach ($notifications as $warning)
                                <p>
                                    - ({{ $warning->storage == "CAE" ? "CAE" : "ODONTOLOGÍA" }})
                                    <strong>{{ $warning->name }}</strong>
                                    tiene solo {{ $warning->units }} unidad/es en
                                    {{ $warning->type == "use" ? "uso" : "reserva" }}.
                                </p>
                            @endforeach

                        @else
                            <p>No hay notificaciones</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Contenedor del usuario -->
                <div class="user-dropdown">
                    <!-- Info del usuario -->
                    <div class="user-info btn btn-notifications" id="user-info-toggle">
                        <i class="fa-solid fa-user"></i>
                        {{-- Auth::user()->full_name --}}
                    </div>

                    <!-- Logout oculto por defecto -->
                    <div class="logout fade-in" id="logout-section" style="display: none;">
                        <div class="user-details">
                            <p class="user-name">{{ Auth::user()->full_name }}</p>
                            <p class="user-email">{{ Auth::user()->email }}</p>
                            <p class="user-role">{{ Auth::user()->user_type }}</p>
                        </div>

                        <hr>

                        <div class="logout-button">
                            <a href="{{ route('logout') }}" class="btn btn-danger">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Contenido principal (cambia según la ruta) -->
        <main class="main-content">
            <div class="container">
                {{-- Alertas flash --}}
                <x-alerts />

                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
