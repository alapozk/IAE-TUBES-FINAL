<x-guest-layout>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .register-card {
            background: white;
            border-radius: 30px;
            width: 90%;
            max-width: 1200px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease-out;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            overflow: hidden;
        }

        .register-banner-side {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .register-banner-side::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .register-banner-side::after {
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

        .register-form-side {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .card-header h2 {
            font-size: 1.8rem;
            font-weight: 900;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .card-header p {
            color: #718096;
            font-size: 0.95rem;
        }

        form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
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
            font-size: 0.8rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .login-link {
            color: #6366f1;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        .register-btn {
            padding: 12px 28px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(99, 102, 241, 0.6);
        }

        .register-btn:active {
            transform: translateY(0);
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
            .register-card {
                grid-template-columns: 1fr;
            }

            .register-banner-side {
                padding: 40px 35px;
                min-height: 250px;
            }

            .banner-title {
                font-size: 1.6rem;
            }

            .register-form-side {
                padding: 40px 35px;
            }
        }

        @media (max-width: 768px) {
            .register-wrapper {
                padding: 20px 15px;
            }

            .register-card {
                grid-template-columns: 1fr;
                width: 95%;
            }

            .register-banner-side {
                padding: 35px 25px;
                min-height: 200px;
            }

            .register-form-side {
                padding: 35px 25px;
            }

            .card-header h2 {
                font-size: 1.4rem;
            }

            .card-header p {
                font-size: 0.9rem;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-label {
                font-size: 0.85rem;
            }

            .form-input {
                padding: 10px 12px;
                font-size: 0.85rem;
            }

            .form-footer {
                flex-direction: column;
                gap: 12px;
            }

            .register-btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .register-wrapper {
                padding: 15px 10px;
            }

            .register-banner-side {
                padding: 25px 15px;
            }

            .register-form-side {
                padding: 25px 15px;
            }

            .banner-icon {
                font-size: 2.5rem;
            }

            .banner-title {
                font-size: 1.3rem;
                margin-bottom: 15px;
            }

            .banner-description {
                font-size: 0.9rem;
            }

            .card-header h2 {
                font-size: 1.2rem;
            }

            .form-label {
                font-size: 0.8rem;
            }

            .form-input {
                padding: 9px 11px;
                font-size: 0.8rem;
            }

            .register-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }
    </style>

    <div class="register-wrapper">
        <div class="register-card">
            
            <!-- Left: Banner -->
            <div class="register-banner-side">
                <div class="banner-content">
                    <div class="banner-icon">📚</div>
                    <h2 class="banner-title">Bergabunglah dengan Kami</h2>
                    <p class="banner-description">Daftarkan akun baru dan mulai perjalanan belajar Anda. Akses ribuan kursus berkualitas dan tingkatkan kemampuan Anda bersama kami.</p>
                </div>
            </div>

            <!-- Right: Form -->
            <div class="register-form-side">
                <div class="card-header">
                    <h2>{{ __('Daftar') }}</h2>
                    <p>Buat akun baru untuk memulai</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Nama Lengkap') }}</label>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            class="form-input"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap Anda"
                            required 
                            autofocus 
                            autocomplete="name" 
                        />
                        @error('name')
                            <div class="form-error">
                                <span>✕</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

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
                            autocomplete="new-password" 
                        />
                        @error('password')
                            <div class="form-error">
                                <span>✕</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Password') }}</label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            class="form-input"
                            placeholder="••••••••"
                            required 
                            autocomplete="new-password" 
                        />
                        @error('password_confirmation')
                            <div class="form-error">
                                <span>✕</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Footer Actions -->
                    <div class="form-footer">
                        <a class="login-link" href="{{ route('login') }}">
                            {{ __('Sudah punya akun? Masuk') }}
                        </a>
                        <button type="submit" class="register-btn">
                            {{ __('Daftar') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</x-guest-layout>