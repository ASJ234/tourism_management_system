@extends('layouts.main')

@section('styles')
<style>
    .register-page-container {
        min-height: 100vh;
        background-image: url('{{ asset('images/slider/aeroplane.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .register-container {
        max-width: 400px;
        width: 100%;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    .register-header {
        text-align: center;
        margin-bottom: 2em;
    }

    .register-header h1 {
        color: #2c3e50;
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #4a5568;
        font-weight: 500;
    }

    .form-group input,
    .form-group select {
        width: 90%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        font-size: 1rem;
        transition: border-color 0.3s;
        background: rgba(255, 255, 255, 0.9);
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: rgb(19, 105, 16);
        box-shadow: 0 0 0 3px rgba(19, 105, 16, 0.1);
    }

    .register-button {
        width: 100%;
        padding: 0.75rem;
        background: rgb(19, 105, 16);
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 1.2rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .register-button:hover {
        background: rgb(22, 128, 19);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(19, 105, 16, 0.2);
    }

    .login-link {
        text-align: center;
        margin-top: 1.5rem;
    }

    .login-link a {
        color: rgb(19, 105, 16);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .login-link a:hover {
        color: rgb(22, 128, 19);
        text-decoration: underline;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 5px;
        background: rgba(254, 226, 226, 0.9);
        color: #dc2626;
    }

    .input-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgb(19, 105, 16);
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon input,
    .input-with-icon select {
        padding-left: 2.5rem;
    }
</style>
@endsection

@section('content')
<div class="register-page-container">
    <div class="register-container">
        <div class="register-header">
            <h1>Create an Account</h1>
            <p>Join us and start your journey today</p>
        </div>

        @if ($errors->any())
            <div class="alert">
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-with-icon">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <!-- Full Name -->
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <div class="input-with-icon">
                    <i class="fas fa-user-circle input-icon"></i>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>
            </div>

            <!-- Contact Number -->
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <div class="input-with-icon">
                    <i class="fas fa-phone input-icon"></i>
                    <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}">
                </div>
            </div>

            <!-- Role -->
            <div class="form-group">
                <label for="role">Register as</label>
                <div class="input-with-icon">
                    <i class="fas fa-user-tag input-icon"></i>
                    <select id="role" name="role" required>
                        <option value="tourist">Tourist</option>
                    </select>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <button type="submit" class="register-button">
                Create Account
            </button>

            <div class="login-link">
                Already have an account? <a href="{{ route('login') }}">Log in</a>
            </div>
        </form>
    </div>
</div>
@endsection
