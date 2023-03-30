/** @type {import('tailwindcss').Config} */
module.exports = {
  corePlugins: {
    preflight: false,
  },
  mode: 'jit',
  content: ['./app/view/**/*.{php,html,js}', './javascript/*.js'],
  theme: {
    extend: {},
  },
  plugins: [],
  important: true,
}
