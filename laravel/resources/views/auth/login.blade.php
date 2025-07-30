<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Event Hoki Talas89</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Keyframe animations for background */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }

        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 1; transform: scale(1); }
        }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Body styling matching home page */
        body {
            background: linear-gradient(-45deg, #1c7d36, #2d8f47, #4CAF50, #66BB6A, #1c7d36);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            font-family: Arial, sans-serif;
            text-align: center;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
            margin: 0;
            padding: 0;
        }

        /* Animated background overlay */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
        }

        /* Floating particles */
        .floating-particle {
            position: absolute;
            width: 6px; height: 6px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            pointer-events: none;
            animation: sparkle 4s ease-in-out infinite;
        }

        .floating-particle:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-particle:nth-child(2) { top: 60%; left: 20%; animation-delay: 1s; }
        .floating-particle:nth-child(3) { top: 30%; left: 70%; animation-delay: 2s; }
        .floating-particle:nth-child(4) { top: 80%; left: 80%; animation-delay: 3s; }
        .floating-particle:nth-child(5) { top: 15%; left: 50%; animation-delay: 0.5s; }
        .floating-particle:nth-child(6) { top: 70%; left: 60%; animation-delay: 1.5s; }

        /* Login container */
        .login-container {
            position: relative; z-index: 2;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px; padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 1s ease-out;
            width: 100%; max-width: 400px;
        }

        /* Form styling */
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-label { display: block; margin-bottom: 8px; color: white; font-weight: 600; font-size: 14px; }
        .form-input {
            width: 100%; padding: 15px; font-size: 16px; border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(255, 255, 255, 0.9);
            box-sizing: border-box; height: 50px; color: #333;
        }
        .form-input:focus { outline: none; border-color: #4CAF50; box-shadow: 0 0 10px rgba(76, 175, 80, 0.3); }

        /* Button styling */
        .btn {
            width: 100%; padding: 15px; font-size: 16px; margin: 10px 0; border-radius: 5px; border: none;
            box-sizing: border-box; height: 50px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: background-color 0.3s ease;
        }
        .btn-primary { background-color: #4CAF50; color: white; }
        .btn-primary:hover { background-color: #45a049; }

        /* Header styling */
        .login-header { margin-bottom: 30px; }
        .login-header h1 { font-size: 24px; margin-bottom: 10px; color: white; }

        /* Checkbox styling */
        .checkbox-group { display: flex; align-items: center; margin: 15px 0; }
        .checkbox-group input[type="checkbox"] { margin-right: 10px; }
        .checkbox-group label { color: white; font-size: 14px; }

        /* Links styling */
        .form-links { margin-top: 20px; text-align: center; }
        .form-links a { color: rgba(255, 255, 255, 0.8); text-decoration: none; font-size: 14px; }
        .form-links a:hover { color: white; text-decoration: underline; }

        /* Error messages */
        .error-messages {
            background: rgba(255, 0, 0, 0.1); border: 1px solid rgba(255, 0, 0, 0.3);
            border-radius: 5px; padding: 10px; margin-bottom: 20px; color: #ff6b6b;
        }
        .error-messages ul { margin: 0; padding-left: 20px; }

        /* Success messages */
        .success-message {
            background: rgba(0, 255, 0, 0.1); border: 1px solid rgba(0, 255, 0, 0.3);
            border-radius: 5px; padding: 10px; margin-bottom: 20px; color: #4CAF50;
        }
    </style>
</head>
<body>
    <!-- Floating particles for animation -->
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>

    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-sign-in-alt"></i> Login</h1>
            <p>Event Hoki Talas89</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="success-message">{{ session('status') }}</div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Username -->
            <div class="form-group">
                <label class="form-label" for="username">
                    <i class="fas fa-user"></i> Username
                </label>
                <input id="username" class="form-input" type="text" name="username" 
                       value="{{ old('username') }}" required autofocus 
                       placeholder="Masukkan username Anda" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label" for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input id="password" class="form-input" type="password" name="password"
                       required autocomplete="current-password"
                       placeholder="Masukkan password Anda" />
            </div>

            <!-- Remember Me -->
            <div class="checkbox-group">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">{{ __('Remember me') }}</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> {{ __('Log in') }}
            </button>

            <!-- Links -->
            <div class="form-links">
                <a href="/"><i class="fas fa-arrow-left"></i> Kembali ke Home</a>
            </div>
        </form>
    </div>
</body>
</html>