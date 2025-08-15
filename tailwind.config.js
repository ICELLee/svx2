/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.vue',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary:   'rgb(var(--primary) / <alpha-value>)',   // Lila
                accent:    'rgb(var(--accent) / <alpha-value>)',    // Orange
                bg:        'rgb(var(--bg) / <alpha-value>)',
                card:      'rgb(var(--card) / <alpha-value>)',
                text:      'rgb(var(--text) / <alpha-value>)',
                border:    'rgb(var(--border) / <alpha-value>)',
                muted:     'rgb(var(--muted) / <alpha-value>)',
                ring:      'rgb(var(--ring) / <alpha-value>)',
            },
        },
    },
    plugins: [],
}
