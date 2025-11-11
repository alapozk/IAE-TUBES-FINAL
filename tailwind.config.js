import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            // Font Family (dari Laravel Breeze/Jetstream)
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            // Custom Colors untuk EduConnect LMS
            colors: {
                'primary': {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    500: '#667eea',
                    600: '#5a67d8',
                    700: '#4c51bf',
                },
                'secondary': {
                    500: '#764ba2',
                    600: '#6b3fa0',
                },
            },
            
            // Custom Animations
            animation: {
                'fadeInDown': 'fadeInDown 0.8s ease-out',
                'fadeInUp': 'fadeInUp 0.8s ease-out',
                'slideInUp': 'slideInUp 0.3s ease-out',
                'float': 'float 3s ease-in-out infinite',
                'fadeIn': 'fadeIn 0.5s ease-out',
                'slideInRight': 'slideInRight 0.5s ease-out',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'blob': 'blob 7s infinite',
            },
            
            // Custom Keyframes
            keyframes: {
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInUp: {
                    '0%': { opacity: '0', transform: 'translateY(100px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(-30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                pulse: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.5' },
                },
                blob: {
                    '0%, 100%': { transform: 'translate(0px, 0px) scale(1)' },
                    '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                    '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                },
            },
            
            // Custom Box Shadows
            boxShadow: {
                '3xl': '0 25px 50px -12px rgba(0, 0, 0, 0.3)',
                'glow-blue': '0 0 20px rgba(102, 126, 234, 0.6)',
                'glow-purple': '0 0 20px rgba(118, 75, 162, 0.6)',
            },
            
            // Custom Backdrop Blur
            backdropBlur: {
                xs: '2px',
            },
        },
    },

    plugins: [forms],
};
