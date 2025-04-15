<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tourism Management System</title>
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
                background:rgb(108, 122, 136);
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
                color:#ffd700;
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

            /* Reviews Section */
            .reviews {
                padding: 5rem 2rem;
                background:rgb(83, 98, 112);
                text-align: center;
            }

            .reviews h2 {
                font-size: 2.5rem;
                color: #fff;
                margin-bottom: 2rem;
            }

            .reviews-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                max-width: 1200px;
                margin: 0 auto;
            }

            .review-card {
                background: #f8f9fa;
                padding: 2rem;
                border-radius: 15px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                text-align: left;
                transition: transform 0.3s ease;
            }

            .review-card:hover {
                transform: translateY(-5px);
            }

            .review-header {
                display: flex;
                align-items: center;
                margin-bottom: 1rem;
            }

            .reviewer-avatar {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                margin-right: 1rem;
                object-fit: cover;
            }

            .reviewer-info {
                flex-grow: 1;
            }

            .reviewer-name {
                font-weight: 600;
                color: #2c3e50;
                margin: 0;
            }

            .review-date {
                font-size: 0.9rem;
                color: #666;
            }

            .review-rating {
                color: #ffd700;
                margin-bottom: 0.5rem;
                font-size: 1.2rem;
            }

            .review-content {
                color: #4a5568;
                font-size: 1rem;
                line-height: 1.6;
                margin-bottom: 1rem;
            }

            .review-package {
                margin-top: 1rem;
                font-size: 0.9rem;
                color: #2c3e50;
                font-weight: 500;
            }

            /* Footer */
            .footer {
                background: #1a252f;
                color: #fff;
                padding: 4rem 2rem 2rem;
                text-align: center;
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
        </style>
        <!-- Add Font Awesome in the head section -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
    <body>
        <!-- Header -->
        <header class="header">
            <a href="{{ url('/') }}" class="logo">The Travel Junkie</a>
            <div class="nav-container">
                <div class="nav-links">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="{{ url('/navbar/about') }}">About Us</a>
                    <a href="{{ url('/navbar/contact') }}">Contact</a>
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
            <h2 style="text-align: center; margin-bottom: 3rem; color: #fff;">About Us</h2>
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
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About TMS</h3>
                    <p>Your trusted partner in creating unforgettable travel moments. We specialize in curating the best tour packages for adventure seekers and leisure travelers alike.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="{{ url('/navbar/about') }}"><i class="fas fa-info-circle"></i> About Us</a></li>
                        <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        <li><a href="{{ route('register') }}"><i class="fas fa-sign-in-alt"></i> Register</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul class="footer-links">
                        <li><a href="tel:+256706062559"><i class="fas fa-phone"></i> +256 706062559</a></li>
                        <li><a href="mailto:arthurvardy27@gmail.com"><i class="fas fa-envelope"></i>group@gmail.com</a></li>
                        <li><a href=""><i class="fas fa-map-marker-alt"></i>Kampala, Uganda</a></li>
                        <li><a href=""><i class="fas fa-clock"></i> Mon - Fri: 9:00 AM - 5:00 PM</a></li>
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


