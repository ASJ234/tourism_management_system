<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Tourism Management System') }}</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        
        <!-- Styles -->
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                margin: 0;
                padding: 0;
                line-height: 1.6;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* Header */
            .header {
                background: rgba(0, 0, 0, 0.7);
                padding: 1rem 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                z-index: 1000;
            }

            .logo {
                color: #fff;
                font-size: 1.5rem;
                font-weight: 700;
                text-decoration: none;
            }

            .logo:hover {
                color: #ffd700;
            }

            /* Navigation */
            .nav-container {
                display: flex;
                align-items: center;
            }

            .nav-links {
                display: flex;
                align-items: center;
            }

            .nav-links a {
                color: #fff;
                text-decoration: none;
                margin-left: 1.5rem;
                font-weight: 500;
                transition: color 0.3s;
            }

            .nav-links a:hover {
                color: #ffd700;
            }

            /* Main Content */
            .main-content {
                flex: 1;
                padding: 2rem 0;
            }

            /* Footer */
            .footer {
                background: #1a252f;
                color: #fff;
                padding: 4rem 2rem 2rem;
                text-align: center;
                margin-top: auto;
            }

            .footer-content {
                max-width: 1200px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2rem;
                text-align: left;
                margin-bottom: 3rem;
            }

            .footer-section {
                padding: 0 1rem;
            }

            .footer-section h3 {
                color: #ffd700;
                margin-bottom: 1.5rem;
                font-size: 1.2rem;
                font-weight: 600;
            }

            .footer-section p {
                color: #a0aec0;
                margin-bottom: 1rem;
                font-size: 0.95rem;
                line-height: 1.6;
            }

            .footer-links {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .footer-links li {
                margin-bottom: 0.8rem;
            }

            .footer-links a {
                color: #a0aec0;
                text-decoration: none;
                transition: color 0.3s;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .footer-links a:hover {
                color: #ffd700;
            }

            .social-links {
                display: flex;
                gap: 1.5rem;
                justify-content: center;
                margin-bottom: 2rem;
            }

            .social-links a {
                color: #fff;
                text-decoration: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s;
            }

            .social-links a:hover {
                background: #ffd700;
                color: #1a252f;
                transform: translateY(-3px);
            }

            .footer-bottom {
                padding-top: 2rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                text-align: center;
                color: #a0aec0;
                font-size: 0.9rem;
            }

            @media (max-width: 768px) {
                .footer-content {
                    grid-template-columns: 1fr;
                    text-align: center;
                }

                .footer-links a {
                    justify-content: center;
                }
            }
        </style>

        @yield('styles')
    </head>
    <body>
        <!-- Header -->
        <header class="header">
            <a href="{{ url('/') }}" class="logo">SkyPins Tours & Travels</a>
            <div class="nav-container">
                <div class="nav-links">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="{{ url('/#about') }}">About</a>
                    @auth
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About TMS</h3>
                    <p>Your trusted partner in creating unforgettable travel experiences. We specialize in curating the perfect holiday packages for adventure seekers and leisure travelers alike.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="{{ url('/#about') }}"><i class="fas fa-info-circle"></i> About Us</a></li>
                        <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        <li><a href="{{ route('register') }}"><i class="fas fa-sign-in-alt"></i> Register</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul class="footer-links">
                        <li><a href="tel:+256706062559"><i class="fas fa-phone"></i> +256 706062559</a></li>
                        <li><a href="mailto:arthurvardy27@gmail.com"><i class="fas fa-envelope"></i> group@gmail.com</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> Kampala, Uganda</a></li>
                        <li><a href="#"><i class="fas fa-clock"></i> Mon - Fri: 9:00 AM - 5:00 PM</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Newsletter</h3>
                    <p>Subscribe to our newsletter for the latest updates and travel deals.</p>
                    <form style="display: flex; gap: 0.5rem;">
                        <input type="email" placeholder="Your Email" style="padding: 0.8rem; border: none; border-radius: 5px; flex: 1;">
                        <button type="submit" class="cta-button" style="padding: 0.8rem 1.2rem;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="social-links">
                <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} TMS. All rights reserved. | Designed by Group-P</p>
            </div>
        </footer>

        @yield('scripts')
    </body>
</html> 