<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <span style="font-size: 2rem;">👤</span>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #1a202c; margin: 0;">
                {{ __('Profil Saya') }}
            </h2>
        </div>
    </x-slot>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .profile-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }

        /* Tab Navigation */
        .profile-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            animation: slideDown 0.6s ease-out;
        }

        .tab-btn {
            padding: 12px 24px;
            border: none;
            background: #f7fafc;
            color: #4a5568;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            background: #edf2f7;
            transform: translateY(-2px);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Section Card */
        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            animation: slideUp 0.6s ease-out;
            display: none;
        }

        .profile-card.active {
            display: block;
            animation: slideUp 0.6s ease-out;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }

        .card-icon {
            font-size: 2rem;
        }

        .card-header h3 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1a202c;
        }

        .card-header p {
            font-size: 0.9rem;
            color: #718096;
            margin-top: 5px;
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-help {
            font-size: 0.85rem;
            color: #718096;
            margin-top: 6px;
        }

        /* Buttons */
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f7fafc;
            color: #4a5568;
            border: 1px solid #cbd5e0;
        }

        .btn-secondary:hover {
            background: #edf2f7;
            border-color: #a0aec0;
        }

        .btn-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(250, 112, 154, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(250, 112, 154, 0.4);
        }

        /* Alert Box */
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border-left: 4px solid #48bb78;
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

        /* Profile Info */
        .profile-info {
            background: #f7fafc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #4a5568;
        }

        .info-value {
            color: #2d3748;
            font-weight: 500;
        }

        /* Animations */
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

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-card {
                padding: 25px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile-tabs {
                flex-direction: column;
            }

            .tab-btn {
                width: 100%;
                justify-content: center;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .profile-wrapper {
                padding: 20px 10px;
            }

            .profile-card {
                padding: 20px;
            }

            .card-header h3 {
                font-size: 1.2rem;
            }

            .form-label {
                font-size: 0.9rem;
            }

            .form-input,
            .form-textarea {
                font-size: 0.9rem;
                padding: 10px 12px;
            }
        }
    </style>

    <div class="profile-wrapper">
        <div class="profile-container">

            <!-- Tab Navigation -->
            <div class="profile-tabs">
                <button class="tab-btn active" onclick="switchTab('profile')">
                    👤 Informasi Profil
                </button>
                <button class="tab-btn" onclick="switchTab('password')">
                    🔒 Ubah Password
                </button>
                <button class="tab-btn" onclick="switchTab('delete')">
                    🗑️ Hapus Akun
                </button>
            </div>

            <!-- Profile Card - Update Profile Information -->
            <div class="profile-card active" id="profile">
                <div class="card-header">
                    <div class="card-icon">👤</div>
                    <div>
                        <h3>Informasi Profil</h3>
                        <p>Kelola informasi pribadi Anda</p>
                    </div>
                </div>

                @if(session('status') === 'profile-updated')
                    <div class="alert alert-success">
                        <span>✓</span>
                        <span>Profil berhasil diperbarui.</span>
                    </div>
                @endif

                <div class="profile-info">
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Role:</span>
                        <span class="info-value">
                            @if(auth()->user()->role === 'student')
                                <span style="background: #bee3f8; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem;">Siswa</span>
                            @elseif(auth()->user()->role === 'teacher')
                                <span style="background: #feebc8; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem;">Guru</span>
                            @else
                                <span style="background: #fed7d7; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem;">Admin</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bergabung:</span>
                        <span class="info-value">{{ auth()->user()->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Profile Card - Update Password -->
            <div class="profile-card" id="password">
                <div class="card-header">
                    <div class="card-icon">🔒</div>
                    <div>
                        <h3>Ubah Password</h3>
                        <p>Perbarui password akun Anda</p>
                    </div>
                </div>

                @if(session('status') === 'password-updated')
                    <div class="alert alert-success">
                        <span>✓</span>
                        <span>Password berhasil diperbarui.</span>
                    </div>
                @endif

                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Profile Card - Delete Account -->
            <div class="profile-card" id="delete">
                <div class="card-header">
                    <div class="card-icon">🗑️</div>
                    <div>
                        <h3>Hapus Akun</h3>
                        <p>Permanenten hapus akun Anda dan semua data</p>
                    </div>
                </div>

                <div class="alert alert-error">
                    <span>⚠️</span>
                    <span>Tindakan ini tidak dapat dibatalkan. Pastikan Anda memahami konsekuensinya sebelum melanjutkan.</span>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.profile-card').forEach(card => {
                card.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');

            // Smooth scroll to top
            document.querySelector('.profile-wrapper').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</x-app-layout>