<x-guest-layout>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .forgot-password-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease-out;
        }

        .card-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .card-header h2 {
            font-size: 1.8rem;
            font-weight: 900;
            color: #1a202c;
            margin-bottom: 12px;
        }

        .card-description {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .info-box {
            background: #bee3f8;
            border-left: 4px solid #4299e1;
            padding: 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #2c5282;
        }

        .alert-success {
            background: #c6f6d5;
            border-left: 4px solid #48bb78;
            padding: 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #22543d;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        form {
            width: 100%;
            margin-top: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
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

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .back-link {
            color: #6366f1;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        .submit-btn {
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

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(99, 102, 241, 0.6);
        }

        .submit-btn:active {
            transform: translateY(0);
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

        /* Responsive */
        @media (max-width: 480px) {
            .forgot-password-card {
                padding: 30px 20px;
            }

            .card-header h2 {
                font-size: 1.4rem;
            }

            .card-description {
                font-size: 0.9rem;
            }

            .form-input {
                padding: 10px 12px;
                font-size: 0.9rem;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .submit-btn {
                width: 100%;
            }

            .back-link {
                text-align: center;
            }
        }
    </style>

    <div class="forgot-password-card">
        
        <!-- Header -->
        <div class="card-header">
            <div class="card-icon">🔑</div>
            <h2>{{ __('Lupa Password?') }}</h2>
            <p class="card-description">
                {{ __('Tidak masalah. Beritahu kami alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password.') }}
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert-success">
                <span>✓</span>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.email') }}">
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
                />
                @error('email')
                    <div class="form-error">
                        <span>✕</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a class="back-link" href="{{ route('login') }}">
                    ← {{ __('Kembali ke Login') }}
                </a>
                <button type="submit" class="submit-btn">
                    {{ __('Kirim Link Reset') }}
                </button>
            </div>
        </form>

    </div>

</x-guest-layout>