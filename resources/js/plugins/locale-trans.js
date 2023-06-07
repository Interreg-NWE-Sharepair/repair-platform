Vue.use({
  install(Vue) {
    Vue.mixin({
      methods: {
        /**
         * Return current locale value from json, or fallback when not available.
         */
        // eslint-disable-next-line no-underscore-dangle
        lt(value, locale, fallback) {
          return value[locale] ? value[locale] : value[fallback];
        }
      }
    });
  }
});
