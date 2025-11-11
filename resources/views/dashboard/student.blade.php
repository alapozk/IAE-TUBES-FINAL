<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 1.8rem;">👨‍🎓</span>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #1a202c; margin: 0;">
                {{ __('Dashboard Siswa') }}
            </h2>
        </div>
    </x-slot>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .dashboard-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: calc(100vh - 80px);
            padding: 40px 20px;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Welcome Section */
        .welcome-section {
            background: white;
            border-radius: 15px;
            padding: 35px;
            margin-bottom: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            animation: slideDown 0.6s ease-out;
        }

        .welcome-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .welcome-text h3 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 10px;
        }

        .welcome-text p {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .welcome-icon {
            font-size: 3rem;
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .menu-card {
            background: white;
            border-radius: 15px;
            padding: 35px 25px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            animation: slideUp 0.7s ease-out;
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .menu-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), transparent);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .menu-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .menu-icon {
            font-size: 2.8rem;
            margin-bottom: 15px;
            z-index: 1;
        }

        .menu-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 10px;
            z-index: 1;
        }

        .menu-description {
            font-size: 0.9rem;
            color: #718096;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
            z-index: 1;
        }

        .menu-link {
            display: inline-flex;
            align-items: center;
            padding: 11px 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            width: fit-content;
            z-index: 1;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .menu-link:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .menu-link svg {
            width: 16px;
            height: 16px;
            margin-left: 6px;
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
            .dashboard-wrapper {
                padding: 30px 15px;
            }

            .welcome-content {
                flex-direction: column;
                text-align: center;
            }

            .welcome-icon {
                font-size: 2.5rem;
            }

            .menu-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .menu-card {
                padding: 25px 20px;
            }

            .menu-title {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-wrapper {
                padding: 20px 10px;
            }

            .welcome-section {
                padding: 20px;
            }

            .welcome-text h3 {
                font-size: 1.2rem;
            }

            .welcome-icon {
                font-size: 2rem;
            }

            .menu-card {
                padding: 20px 15px;
            }

            .menu-icon {
                font-size: 2.2rem;
            }

            .menu-title {
                font-size: 1rem;
            }
        }
    </style>

    <div class="dashboard-wrapper">
        <div class="dashboard-container">

            <!-- Welcome Section -->
            <div class="welcome-section">
                <div class="welcome-content">
                    <div class="welcome-text">
                        <h3>Halo, {{ auth()->user()->name }}! 👋</h3>
                        <p>Selamat datang kembali. Kelola pembelajaran Anda dan jelajahi kursus baru.</p>
                    </div>
                    <div class="welcome-icon">📚</div>
                </div>
            </div>

            <!-- Main Menu -->
            <div class="menu-grid">
                <a href="{{ route('courses.catalog') }}" class="menu-card">
                    <div class="menu-icon">🔍</div>
                    <h3 class="menu-title">Katalog Kursus</h3>
                    <p class="menu-description">Temukan dan jelajahi berbagai kursus yang tersedia di platform kami.</p>
                    <div class="menu-link">
                        Buka Katalog
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('student.mycourses') }}" class="menu-card">
                    <div class="menu-icon">📖</div>
                    <h3 class="menu-title">Kursus Saya</h3>
                    <p class="menu-description">Lihat semua kursus yang sedang Anda ikuti dan lanjutkan pembelajaran.</p>
                    <div class="menu-link">
                        Lihat Kursus
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </a>
            </div>

        </div>
    </div>

</x-app-layout>