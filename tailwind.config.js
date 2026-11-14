import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Plus Jakarta Sans', 'Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    dark: '#060814',
                    darker: '#03050a',
                    card: '#0c1228',
                    border: 'rgba(56, 189, 248, 0.15)',
                    cyan: '#00f0ff',
                    blue: '#0ea5e9',
                    purple: '#9333ea',
                    violet: '#a855f7',
                    neonPink: '#ec4899',
                }
            },
            boxShadow: {
                'neon-cyan': '0 0 25px -3px rgba(0, 240, 255, 0.35), 0 0 10px -2px rgba(0, 240, 255, 0.2)',
                'neon-purple': '0 0 25px -3px rgba(168, 85, 247, 0.4), 0 0 10px -2px rgba(168, 85, 247, 0.25)',
                'glass-3d': '0 20px 50px rgba(0, 0, 0, 0.6), 0 0 20px rgba(0, 240, 255, 0.15)',
                'card-3d': '0 15px 35px -5px rgba(2, 6, 23, 0.7), 0 0 15px rgba(147, 51, 234, 0.15)',
            },
            animation: {
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'float': 'float 6s ease-in-out infinite',
                'float-slow': 'float 9s ease-in-out infinite',
                'glow': 'glow 3s ease-in-out infinite alternate',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-12px)' },
                },
                glow: {
                    '0%': { filter: 'drop-shadow(0 0 10px rgba(0,240,255,0.4))' },
                    '100%': { filter: 'drop-shadow(0 0 25px rgba(168,85,247,0.7))' },
                }
            }
        },
    },

    plugins: [forms],
};
