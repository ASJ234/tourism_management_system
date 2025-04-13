<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Add these meta tags for PWA -->
        <meta name="theme-color" content="#2c3e50">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        
        <title>{{ config('app.name', 'Tourism Management System') }}</title>

        <!-- Add manifest -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <!-- Add favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <!-- Replace CDN links with local assets -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fontawesome-all.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/tour-operator.css') }}" rel="stylesheet">

        <!-- Add local fonts -->
        <style>
            @font-face {
                font-family: 'Poppins';
                src: url('{{ asset('fonts/Poppins-Regular.woff2') }}') format('woff2');
                font-weight: 400;
                font-display: swap;
            }
            @font-face {
                font-family: 'Poppins';
                src: url('{{ asset('fonts/Poppins-Medium.woff2') }}') format('woff2');
                font-weight: 500;
                font-display: swap;
            }
            @font-face {
                font-family: 'Poppins';
                src: url('{{ asset('fonts/Poppins-SemiBold.woff2') }}') format('woff2');
                font-weight: 600;
                font-display: swap;
            }
            @font-face {
                font-family: 'Poppins';
                src: url('{{ asset('fonts/Poppins-Bold.woff2') }}') format('woff2');
                font-weight: 700;
                font-display: swap;
            }
        </style>

        <!-- Local Styles -->
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f8f9fa;
            }

            .navbar {
                background: linear-gradient(135deg, #2c3e50, #3498db);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .navbar-brand {
                font-weight: 600;
                color: white !important;
            }

            .nav-link {
                color: rgba(255, 255, 255, 0.9) !important;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .nav-link:hover {
                color: white !important;
                transform: translateY(-2px);
            }

            .dropdown-menu {
                border: none;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
            }

            .dropdown-item {
                padding: 10px 20px;
                transition: all 0.3s ease;
            }

            .dropdown-item:hover {
                background: linear-gradient(135deg, #3498db, #2980b9);
                color: white;
            }

            .container {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }

            /* Custom Animations */
            .fade-in {
                animation: fadeIn 0.5s ease-out;
            }

            /* Custom Scrollbar for the entire page */
            ::-webkit-scrollbar {
                width: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #3498db;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #2980b9;
            }
        </style>

        @yield('styles')
    </head>
    <body>
            <div id="app">
            <nav class="navbar navbar-expand-md navbar-light shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ 'Tourism Management System' }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            @auth
                                @if(auth()->user()->role === 'tour_operator')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('tour_operator.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('tour_operator.packages.index') }}">
                                            <i class="fas fa-box"></i> Packages
                                        </a>
                                    </li>
                                    
                                @elseif(auth()->user()->role === 'tourist')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('tourist.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('tourist.destinations.index') }}">
                                            <i class="fas fa-map-marked-alt"></i> Destinations
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('tourist.packages') }}">
                                            <i class="fas fa-suitcase-rolling"></i> Packages
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('tourist.bookings.index') }}">
                                            <i class="fas fa-calendar-check"></i> My Bookings
                                        </a>
                                    </li>
                                @endif
                            @endauth
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>
        </div>

        <!-- Replace CDN scripts with local versions -->
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        
        <!-- Add Service Worker registration -->
        <script>
            // Register Service Worker
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then(registration => {
                            console.log('ServiceWorker registered');
                        })
                        .catch(error => {
                            console.error('ServiceWorker registration failed:', error);
                        });
                });
            }

            // Handle online/offline status
            function updateOnlineStatus() {
                const offlineStatus = document.getElementById('offline-status');
                if (!navigator.onLine) {
                    offlineStatus.classList.remove('d-none');
                    document.body.classList.add('offline-mode');
                } else {
                    offlineStatus.classList.add('d-none');
                    document.body.classList.remove('offline-mode');
                }
            }

            window.addEventListener('online', updateOnlineStatus);
            window.addEventListener('offline', updateOnlineStatus);
            updateOnlineStatus();
        </script>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        @stack('scripts')
    </body>
</html>
