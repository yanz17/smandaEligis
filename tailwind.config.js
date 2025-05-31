/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './storage/framework/views/*.php', // tambahkan ini
    './app/View/Components/**/*.php', 
  ],
  theme: {
    extend: {},
  },
  plugins: [require('daisyui')],
  daisyui: {
    themes: ['light'], // ubah ke 'light' agar teks tidak putih, atau bisa pakai 'corporate', 'lofi', dll
    base: true,     // tambahkan ini
    styled: true,   // tambahkan ini
    utils: true,    // tambahkan ini
  },
};