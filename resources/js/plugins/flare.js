import { flareVue } from '@flareapp/flare-vue';

if (typeof window.flare !== 'undefined') {
  Vue.use(flareVue);
}
