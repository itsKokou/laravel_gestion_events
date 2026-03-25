module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/css/**/*.css',
  ],
  theme: {
    extend: {
      colors: {
        'we-primary': '#ea580c',
        'we-primary-hover': '#c2410c',
        'we-sand': '#fdfcfb',
        'we-charcoal': '#1c1917',
        'we-peach': '#ffedd5',
      },
      boxShadow: {
        'sunset': '0 10px 25px -5px rgba(234, 88, 12, 0.25), 0 8px 10px -6px rgba(234, 88, 12, 0.1)',
        'premium': '0 10px 40px -10px rgba(0,0,0,0.08)',
        'premium-hover': '0 20px 40px -10px rgba(0,0,0,0.12)',
        'glass': '0 4px 30px rgba(0, 0, 0, 0.05)',
      },
      borderRadius: {
        '4xl': '2rem',
        '5xl': '2.5rem',
      }
    },
  },
  plugins: [],
};
