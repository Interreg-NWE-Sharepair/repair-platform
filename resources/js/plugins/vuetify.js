import Vuetify from 'vuetify/lib';

import '@mdi/font/css/materialdesignicons.css';

Vue.use(Vuetify);

export default new Vuetify({
  icons: {
    iconfont: 'mdi'
  },
  theme: {
    themes: {
      light: {
        primary: '#71B8C5',
        secondary: '#9C7A97',
        status_green: '#91D715',
        status_orange: '#9C7A97',
        status_yellow: '#FAC05E',
        status_gray: '#DEDEDE'
      },
      dark: {
        primary: '#71B8C5',
        secondary: '#9C7A97',
        status_green: '#91D715',
        status_orange: '#9C7A97',
        status_yellow: '#FAC05E',
        status_gray: '#DEDEDE'
      }
    }
  }
});
