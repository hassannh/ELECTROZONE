/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Cairo', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                primary: {
                    DEFAULT: '#007bff',
                    dark: '#0056b3',
                    light: '#e8f4ff',
                },
                accent: {
                    DEFAULT: '#32CD32',
                    dark: '#28a428',
                    light: '#eafbea',
                },
                dark: '#333333',
                mid: '#666666',
                light: '#999999',
                border: '#CCCCCC',
                surface: '#F8F8F8',
                danger: '#dc3545',
            },
            borderRadius: {
                'base': '10px',
                'lg': '16px',
            },
            boxShadow: {
                'base': '0 2px 12px rgba(0, 0, 0, .08)',
                'premium': '0 8px 32px rgba(0, 0, 0, .15)',
            },
        },
    },
    plugins: [],
}
