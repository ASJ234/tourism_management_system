@extends('layouts.main')

@section('styles')
<style>
    .login-page-container {
        min-height: 100vh;
        background-image: url('{{ asset('images/slider/bus1.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        max-width: 400px;
        width: 100%;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    .login-header {
        text-align: center;
        margin-bottom: 2em;
    }

    .login-header h1 {
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

    .form-group input {
        width: 90%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        font-size: 1rem;
        transition: border-color 0.3s;
        background: rgba(255, 255, 255, 0.9);
    }

    .form-group input:focus {
        outline: none;
        border-color: rgb(19, 105, 16);
        box-shadow: 0 0 0 3px rgba(19, 105, 16, 0.1);
    }

    .login-button {
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

    .login-button:hover {
        background: rgb(22, 128, 19);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(19, 105, 16, 0.2);
    }

    .register-link {
        text-align: center;
        margin-top: 1.5rem;
    }

    .register-link a, .forgot-password {
        color: rgb(19, 105, 16);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .register-link a:hover, .forgot-password:hover {
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

    .input-with-icon input {
        padding-left: 2.5rem;
    }

    .remember-me {
        display: flex;
        align-items: center;
        margin: 1rem 0;
    }

    .remember-me input[type="checkbox"] {
        width: auto;
        margin-right: 0.5rem;
    }

    .forgot-password {
        display: block;
        text-align: right;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="login-page-container">
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Sign in to continue your journey</p>
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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email/Username -->
            <div class="form-group">
                <label for="login">Email or Username</label>
                <div class="input-with-icon">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" required>
                </div>
                <a class="forgot-password" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input type="checkbox" id="remember_me" name="remember">
                <label for="remember_me">Remember me</label>
            </div>

            <button type="submit" class="login-button">
                Sign In
            </button>

            <div class="register-link">
                Don't have an account? <a href="{{ route('register') }}">Register now</a>
            </div>
        </form>
    </div>
</div>
@endsection
