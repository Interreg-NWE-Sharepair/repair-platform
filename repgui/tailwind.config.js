const defaultTheme = require('tailwindcss/defaultTheme');

const colorShade = require('./tailoff/tailwind/color-shades');
// const underlineAnimation = require("./tailoff/tailwind/underline-animation");
const aspectRatio = require('tailwindcss-aspect-ratio');

const siteColors = {
  primary: {
    default: '#71B8C5',
    contrast: '#ffffff',
    hover: '#5A939D',
    hoverContrast: '#ffffff'
  },
  secondary: {
    default: '#9C7A97',
    contrast: '#ffffff',
    hover: '#7C6178',
    hoverContrast: '#ffffff'
  },
  tertiary: {
    default: '#C79C7D',
    contrast: '#ffffff',
    hover: '#b28b6f'
  }
};

module.exports = {
  target: 'ie11',
  purge: {
    content: [
      '../resources/views/repgui/**/*.blade.php',
      '../resources/views/repsta/**/*.blade.php'
    ],
  },
  theme: {
    borderWidth: {
      default: '1px',
      0: '0',
      1: '1px',
      2: '2px',
      3: '3px',
      4: '4px'
    },
    container: {
      center: true,
      padding: defaultTheme.spacing['4']
    },
    fontFamily: {
      base: "'Titillium Web', sans-serif"
    },
    screens: {
      xs: '480px',
      sm: '660px',
      md: '820px',
      lg: '980px',
      xl: '1200px'
    },
    colors: {
      ...defaultTheme.colors,
      ...siteColors,
      'black': '#333333',
      'light': '#f9f9f9',
      'divider': 'rgba(51,51,51,0.2)',
      'black-40': 'rgba(51,51,51,0.4)',
      'professional-repairer': '#3D64C8',
      'repair-cafe': '#F57C7C',
      'urban-repair-center': '#333333',
      'fablab': '#2ECC71',
      'spare-parts-shop-or-library': '#F2CA27',
      'recycling-center': '#FF64A6'
    },
    aspectRatio: {
      'none': 0,
      'square': [1, 1],
      '16/9': [16, 9],
      '4/3': [4, 3],
      '21/9': [21, 9]
    },
    extend: {
      fontSize: {
        '2xs': ['.6rem', '.75rem'],
        'lg': ['1.125rem', '1.5625rem'], // 18px - 25px
        'xl': ['1.25rem', '1.5625rem'], // 20px - 25px
        '2xl': ['1.375rem', '1.5625rem'], // 22px - 25px
        '3xl': ['1.75rem', '2.1875rem'], // 28px - 35px
        '4xl': ['2.1875rem', '2.5rem'], // 35px - 40px
        '5xl': ['2.5rem', '3.125rem'], // 40px - 50px
        '6xl': ['3rem', '3.75rem'] // 48px - 56px
      },
      maxWidth: {
        flyout: '280px',
        modal: '700px',
        logo: '150px'
      },
      maxHeight: {
        'logo': '85px',
        'logo-mobile': '50px'
      },
      zIndex: {
        99: '99',
        100: '100'
      },
      boxShadow: {
        card: '0 0 30px 0 rgba(0,0,0,0.15)',
        focus: '0 0 0 3px rgba(238,71,55,0.5)'
      },
      borderRadius: {
        card: '30px',
        panel: '40px'
      },
      inset: {
        '1/2': '50%'
      },
      minHeight: {
        '[200px]': '200px'
      },
      height: {
        '1/12': '8.333333%',
        '2/12': '16.666667%',
        '5/12': '41.666667%',
        '6/12': '50%',
        '11/12': '91.666667%',
        '75-screen': '75vh'
      }
    }
  },
  variants: {
    textDecoration: ['responsive', 'hover', 'focus', 'group-hover'],
    textColor: ['responsive', 'hover', 'focus', 'group-hover']
  },
  plugins: [
    colorShade(siteColors),
    // underlineAnimation,
    aspectRatio
  ]
};
