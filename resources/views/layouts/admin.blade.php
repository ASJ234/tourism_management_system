<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin Dashboard - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
        
        @stack('styles')
    </head>
    <body>
        <div class="wrapper">
            <!-- Sidebar -->
            <nav id="sidebar" class="bg-dark text-white">
                <div class="sidebar-header p-3">
                    <h3>Admin Panel</h3>
                </div>

                <ul class="list-unstyled components p-3">
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.destinations') ? 'active' : '' }}">
                        <a href="{{ route('admin.destinations') }}" class="text-white text-decoration-none">
                            <i class="fas fa-map-marker-alt me-2"></i>Destinations
                        </a>
                    </li>
                   
                    <li class="{{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.bookings.index') }}" class="text-white text-decoration-none">
                            <i class="fas fa-calendar-check me-2"></i>Bookings
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.destinations.all-images') ? 'active' : '' }}">
                        <a href="{{ route('admin.destinations.all-images') }}" class="text-white text-decoration-none">
                            <i class="fas fa-images me-2"></i>Manage Images
                        </a>
                    </li>
                    
                </ul>
            </nav>

            <!-- Page Content -->
            <div id="content">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                    <div class="container-fluid">
                        <button type="button" id="sidebarCollapse" class="btn btn-dark">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="ms-auto">
                            <div class="dropdown">
                                <button class="btn btn-link dropdown-toggle text-dark text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->full_name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Main Content -->
                <div class="container-fluid py-4">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- Custom JS -->
        <script src="{{ asset('js/admin.js') }}"></script>
        
        @stack('scripts')
    </body>
</html> 