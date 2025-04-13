<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>About us</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                margin: 0;
                padding: 0;
                line-height: 1.6;
            }

            /* Header */
            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
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

            /* Hero Section */
            .hero {
                height: 100vh;
                position: relative;
                overflow: hidden;
            }

            .slider {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .slide {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                transition: opacity 0.5s ease-in-out;
                background-size: cover;
                background-position: center;
            }

            .slide.active {
                opacity: 1;
            }

            .slide::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
            }

            .hero-content {
                position: relative;
                z-index: 2;
                max-width: 800px;
                padding: 0 2rem;
                margin: 0 auto;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                color: #fff;
            }

            .hero h1 {
                font-size: 3.5rem;
                margin-bottom: 1rem;
                font-weight: 700;
            }

            .hero p {
                font-size: 1.2rem;
                margin-bottom: 2rem;
            }

            /* Features Section */
            .features {
                padding: 5rem 2rem;
                background: #f8f9fa;
            }

            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                max-width: 1200px;
                margin: 0 auto;
            }

            .feature-card {
                background: #fff;
                padding: 2rem;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            .feature-card h3 {
                color: #2c3e50;
                margin: 1rem 0;
            }

            .feature-card p {
                color: #666;
            }

            /* CTA Section */
            .cta {
                padding: 5rem 2rem;
                background: #2c3e50;
                color: #fff;
                text-align: center;
            }

            .cta h2 {
                font-size: 2.5rem;
                margin-bottom: 1rem;
            }

            .cta p {
                max-width: 600px;
                margin: 0 auto 2rem;
            }

            .cta-button {
                display: inline-block;
                padding: 1rem 2rem;
                background: #ffd700;
                color: #2c3e50;
                text-decoration: none;
                border-radius: 5px;
                font-weight: 600;
                transition: background 0.3s;
            }

            .cta-button:hover {
                background: #ffed4a;
            }

            /* Footer */
            .footer {
                background: #1a252f;
                color: #fff;
                padding: 3rem 2rem;
                text-align: center;
            }

            .social-links {
                margin: 1rem 0;
            }

            .social-links a {
                color: #fff;
                margin: 0 1rem;
                text-decoration: none;
            }

            .social-links a:hover {
                color: #ffd700;
            }

            @media (max-width: 768px) {
                .hero h1 {
                    font-size: 2.5rem;
                }
                
                .nav-container {
                    padding: 1rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <header class="header">
            <a href="{{ url('/') }}" class="logo">Tourism Management System</a>
            <div class="nav-container">
                <div class="nav-links">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="{{ url('/landingpage/about') }}">About Us</a>
                    <a href="{{ url('/landingpage/contact') }}">Contact</a>
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

        <!-- About Section -->
        <section id="about" class="features">
            <h2 style="text-align: center; margin-bottom: 3rem; color: #2c3e50;">About Us</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <h3>Our Story</h3>
                    <p>Founded in 2024, we've been helping travelers explore the world's most beautiful destinations with carefully curated experiences.</p>
                </div>
                <div class="feature-card">
                    <h3>Our Mission</h3>
                    <p>To provide exceptional travel experiences that inspire, educate, and create lasting memories for our clients.</p>
                </div>
                <div class="feature-card">
                    <h3>Our Vision</h3>
                    <p>To be the leading tourism management system, making travel accessible and enjoyable for everyone.</p>
                </div>
            </div>
        </section>

        
        <!-- Footer -->
        <footer class="footer">
        <div class="social-links">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">Instagram</a>
        </div>
            <p>&copy; {{ date('Y') }} TMS. All rights reserved.</p>
          
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slides = document.querySelectorAll('.slide');
                let currentSlide = 0;

                function nextSlide() {
                    slides[currentSlide].classList.remove('active');
                    currentSlide = (currentSlide + 1) % slides.length;
                    slides[currentSlide].classList.add('active');
                }

                // Change slide every 5 seconds
                setInterval(nextSlide, 5000);
            });
        </script>
    </body>
</html>
