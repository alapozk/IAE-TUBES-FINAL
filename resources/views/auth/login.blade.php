<x-guest-layout>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .login-card {
            background: white;
            border-radius: 30px;
            padding: 50px 40px;
            width: 90%;
            max-width: 1200px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease-out;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            overflow: hidden;
        }

        .login-banner-side {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-banner-side::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .login-banner-side::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -50%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .banner-content {
            position: relative;
            z-index: 1;
            color: white;
        }

        .banner-icon {
            font-size: 3.5rem;
            margin-bottom: 25px;
        }

        .banner-title {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .banner-description {
            font-size: 1rem;
            opacity: 0.95;
            line-height: 1.8;
        }

        .login-form-side {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .card-header h2 {
            font-size: 2rem;
            font-weight: 900;
            color: #1a202c;
            margin-bottom: 12px;
        }

        .card-header p {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder {
            color: #cbd5e0;
        }

        .form-error {
            color: #e53e3e;
            font-size: 0.85rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-input {
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e0;
            border-radius: 5px;
            cursor: pointer;
            accent-color: #6366f1;
        }

        .checkbox-label {
            margin-left: 10px;
            color: #4a5568;
            font-size: 0.95rem;
            cursor: pointer;
            user-select: none;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 25px;
        }

        .forgot-password {
            color: #6366f1;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(99, 102, 241, 0.6);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #718096;
            font-size: 0.95rem;
        }

        .signup-link a {
            color: #6366f1;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .signup-link a:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        .alert-box {
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            gap: 10px;
            align-items: flex-start;
            animation: slideDown 0.4s ease-out;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border-left: 4px solid #f56565;
        }

        .alert-info {
            background: #bee3f8;
            color: #2c5282;
            border-left: 4px solid #4299e1;
        }

        /* Animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .login-card {
                grid-template-columns: 1fr;
            }

            .login-banner-side {
                padding: 40px 35px;
                min-height: 250px;
            }

            .banner-title {
                font-size: 1.6rem;
            }

            .login-form-side {
                padding: 40px 35px;
            }
        }

        @media (max-width: 768px) {
            .login-card {
                grid-template-columns: 1fr;
                width: 95%;
            }

            .login-banner-side {
                padding: 40px 30px;
            }

            .login-form-side {
                padding: 40px 30px;
            }

            .card-header h2 {
                font-size: 1.5rem;
            }
        }
            .login-wrapper {
                padding: 15px;
            }

            .login-card {
                padding: 35px 25px;
                border-radius: 20px;
            }

            .card-header h2 {
                font-size: 1.6rem;
            }

            .card-header p {
                font-size: 0.9rem;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-label {
                font-size: 0.9rem;
                margin-bottom: 8px;
            }

            .form-input {
                padding: 10px 12px;
                font-size: 0.9rem;
            }

            .login-btn {
                padding: 11px;
                font-size: 0.95rem;
            }
        }
    </style>

    <div class="login-wrapper">
        <div class="login-card">
            
            <!-- Left: Banner -->
            <div class="login-banner-side">
                <div class="banner-content">
                    <div class="banner-icon">🚀</div>
                    <h2 class="banner-title">Selamat Datang Kembali</h2>
                    <p class="banner-description">Masuk ke akun Anda dan lanjutkan perjalanan belajar Anda. Akses ribuan kursus berkualitas tinggi dan tingkatkan kemampuan Anda.</p>
                </div>
            </div>

            <!-- Right: Form -->
            <div class="login-form-side">
            <!-- Header -->
            <div class="card-header">
                <h2>{{ __('Masuk') }}</h2>
                <p>Gunakan email dan password Anda untuk masuk</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert-box alert-info">
                    <span>ℹ️</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        class="form-input"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        required 
                        autofocus 
                        autocomplete="username" 
                    />
                    @error('email')
                        <div class="form-error">
                            <span>✕</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        class="form-input"
                        placeholder="••••••••"
                        required 
                        autocomplete="current-password" 
                    />
                    @error('password')
                        <div class="form-error">
                            <span>✕</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="checkbox-group">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        class="checkbox-input"
                        name="remember"
                    />
                    <label for="remember_me" class="checkbox-label">
                        {{ __('Ingat saya') }}
                    </label>
                </div>

                <!-- Forgot Password Link -->
                @if (Route::has('password.request'))
                    <div class="form-footer">
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            {{ __('Lupa password?') }}
                        </a>
                    </div>
                @endif

                <!-- Login Button -->
                <button type="submit" class="login-btn">
                    {{ __('Masuk') }}
                </button>
            </form>

            <!-- Sign Up Link -->
            @if (Route::has('register'))
                <div class="signup-link">
                    Belum punya akun? 
                    <a href="{{ route('register') }}">
                        {{ __('Daftar di sini') }}
                    </a>
                </div>
            @endif

            </div>
        </div>
    </div>

</x-guest-layout>