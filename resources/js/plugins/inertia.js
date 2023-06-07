import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaProgress } from '@inertiajs/progress';

Vue.use(InertiaApp);

InertiaProgress.init({
  color: '#71B8C5'
});

export default InertiaApp;
