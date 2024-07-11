/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./presentation/pages/**/*.{html,js,php}",
    "./presentation/scripts/**/*.{html,js,php}",
    "./presentation/components/**/*.{html,js,php}","./presentation/**/*.{html,js,php}"
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
