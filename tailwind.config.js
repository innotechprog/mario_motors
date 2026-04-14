/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./views/**/*.php', './public/**/*.php', './admin/**/*.php'],
  theme: {
    extend: {
      fontFamily: {
        heading: ['Oswald', 'sans-serif'],
        sans: ['Inter', 'sans-serif'],
      },
      colors: {
        border: 'hsl(40 15% 85%)',
        background: 'hsl(40 33% 96%)',
        foreground: 'hsl(0 0% 12%)',
        primary: { DEFAULT: 'hsl(0 78% 50%)', foreground: 'hsl(0 0% 100%)' },
        brandyellow: 'hsl(48 100% 52%)',
        muted: { DEFAULT: 'hsl(40 15% 90%)', foreground: 'hsl(0 0% 40%)' },
        card: { DEFAULT: 'hsl(40 30% 92%)', foreground: 'hsl(0 0% 12%)' },
      },
    },
  },
  plugins: [],
};
