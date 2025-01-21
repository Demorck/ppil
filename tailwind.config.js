/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'primary': "#F55ED5",
        'secondary': "#FCD0F3",
        'accent': "#801D6B",
        'accentHover': '#a7278b',

        'tab': "#fcb6dc",
        'row': "#c4719e",
      }
    },
  },
  plugins: [],
}
