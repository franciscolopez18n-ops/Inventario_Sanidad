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
                @if(Cookie::get('TYPE') === 'admin')
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
                                <a href="{{ route('materials.index') }}"
                                class="{{ request()->routeIs('materials.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-minus"></i>
                                    <span class="link-text">Gestionar materiales</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('storages.updateView') }}"
                                class="{{ request()->routeIs('storages.updateView') ? 'active' : '' }}">
                                    <i class="fa-solid fa-box-archive"></i>
                                    <span class="link-text">Gestionar almacenamiento</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('materials.index2') }}"
                                class="{{ request()->routeIs('materials.index2') ? 'active' : '' }}">
                                    <i class="fa-solid fa-box-archive"></i>
                                    <span class="link-text">Gestionar material [Prueba]</span>
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
                                <a href="{{ route('historical.type', ['type' => 'use']) }}"
                                class="{{ request()->fullUrlIs(route('historical.type', ['type' => 'use'])) ? 'active' : '' }}">
                                    <i class="fa-solid fa-book-open"></i>
                                    <span class="link-text">Materiales en uso</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('historical.type', ['type' => 'reserve']) }}"
                                class="{{ request()->fullUrlIs(route('historical.type', ['type' => 'reserve'])) ? 'active' : '' }}">
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
                @endif

                <!-- Menú para Estudiantes -->
                @if(Cookie::get('TYPE') === 'student')

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
                        <a href="{{ route('historical.type', ['type' => 'use']) }}"
                        class="{{ request()->fullUrlIs(route('historical.type', ['type' => 'use'])) ? 'active' : '' }}">
                            <i class="fa-solid fa-book-open"></i>
                            <span class="link-text">Materiales en uso</span>
                        </a>
                    </li>
                @endif

                <!-- Menú para Docentes -->
                @if(Cookie::get('TYPE') === 'teacher')
                    <li>
                        <a href="{{ route('storages.updateView') }}"
                        class="{{ request()->routeIs('storages.updateView') ? 'active' : '' }}">
                            <i class="fa-solid fa-box-archive"></i>
                            <span class="link-text">Gestionar almacenamiento</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('historical.type', ['type' => 'use']) }}"
                        class="{{ request()->fullUrlIs(route('historical.type', ['type' => 'use'])) ? 'active' : '' }}">
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
                    use App\Models\User;
                    use App\Models\Storage;
                    use Illuminate\Support\Facades\Cookie;

                    $user = User::where('user_id', Cookie::get('USERPASS'))->first();
                    
                    $notifications = collect();

                    if ($user && $user->user_type === 'admin') {
                        $notifications = Storage::join('materials', 'storages.material_id', '=', 'materials.material_id')
                            ->select('materials.name','storage', 'storages.units', 'storage_type')
                            ->whereColumn('storages.units', '<', 'storages.min_units')
                            ->orderBy('storage', "desc")
                            ->get();
                    }
                @endphp

                <!-- Notificaciones -->

                @if(Cookie::get('TYPE') === 'admin')
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
                                <p>- ({{$warning->storage == "CAE" ? "CAE" : "ODONTOLOGÍA"}})  <strong>{{$warning->name}}</strong> tiene solo {{$warning->units}} unidad/es en {{$warning->storage_type ==  "use" ? "uso" : "reserva"}}.</p>
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
                        {{-- <span>{{ Cookie::get('NAME') }}</span> --}}
                    </div>

                    <!-- Logout oculto por defecto -->
                    <div class="logout fade-in" id="logout-section" style="display: none;">
                        <div class="user-details">
                            <p class="user-name">{{ Cookie::get('NAME') }}</p>
                            <p class="user-email">{{ Cookie::get('EMAIL') }}</p>
                            <p class="user-role">{{ Cookie::get('TYPE') }}</p>
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
                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
