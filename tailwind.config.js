const resolveConfig = require('tailwindcss/resolveConfig');
const { tailwindConfig } = require('@statikbe/repair-components');

const colors = {
  ...resolveConfig(tailwindConfig).theme.colors,
  'status-open': '#91D715',
  'status-reopened': '#FAC05E',
  'status-in_repair': '#9C7A97',
  'status-completed': '#DEDEDE',
  'light': '#F9F9F9',
  'gray-100': '#F9F9F9'
};

module.exports = {
  presets: [tailwindConfig],
  mode: 'jit',
  purge: {
    content: [
      './resources/js/components/**/*.vue',
      '**/*.vue',
      './node_modules/@statikbe/repair-components/**/*.{vue,js}',
      './node_modules/@statikbe/repair-components/safelist.txt',
      './safelist.txt'
    ]
  },
  important: '.v-application', // overwrite vuetify
  theme: {
    colors,
    // backgroundColor: colors,
    borderColor: colors,
    textColor: colors
  },
  plugins: [require('@tailwindcss/aspect-ratio')]
};
