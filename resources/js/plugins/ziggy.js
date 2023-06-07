import { Ziggy } from '@/js/setup/ziggy';

Ziggy.baseUrl = window.location.origin;
[Ziggy.baseProtocol] = window.location.protocol.split(':');
Ziggy.baseDomain = window.location.host;

Vue.use({
  install(Vue) {
    Vue.mixin({
      methods: {
        route(name, params, absolute) {
          return ziggyRoute(name, params, absolute, Ziggy).url(); // eslint-disable-line
        },
        visitRoute() {
          this.$inertia.visit(this.route(...arguments));
        }
      }
    });
  }
});
