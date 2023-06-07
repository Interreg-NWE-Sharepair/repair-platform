import { createInertiaApp } from '@inertiajs/inertia-vue';
import { InertiaProgress } from '@inertiajs/progress';

import { i18n } from '@statikbe/repair-components';

import '@/js/setup/webpack';
import '@/js/setup/axios';

import '@/js/plugins/algolia';
import '@/js/plugins/ckeditor';
import '@/js/plugins/components';
import '@/js/plugins/cookies';
import '@/js/plugins/filters';
import '@/js/plugins/flare';
import '@/js/plugins/form';
import '@/js/plugins/fuse';
import '@/js/plugins/lang';
import '@/js/plugins/moment';
import '@/js/plugins/meta';
import '@/js/plugins/repair-components';
import '@/js/plugins/theme';
import '@/js/plugins/ziggy';
import '@/js/plugins/locale-trans';

import Vuetify from '@/js/plugins/vuetify';

import '@/sass/custom/app.scss';
import '@/css/app.css';

InertiaProgress.init({
  color: '#71B8C5'
});

i18n.locale = document.documentElement.lang;

createInertiaApp({
  resolve: name => require(`./pages/${name}`),
  setup({ el, app, props }) {
    new Vue({
      i18n,
      vuetify: Vuetify,
      render: h => h(app, props)
    }).$mount(el);
  }
});
