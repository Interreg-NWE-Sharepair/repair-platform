Vue.use({
  install(Vue) {
    Vue.mixin({
      methods: {
        /**
         * Translate the given key.
         */
        // eslint-disable-next-line no-underscore-dangle
        t(key, replace = {}) {
          let translation = this.$page.props.language[key] ? this.$page.props.language[key] : key;

          // eslint-disable-next-line no-shadow
          Object.keys(replace).forEach(key => {
            translation = translation.replace(`:${key}`, replace[key]);
          });

          return translation;
        }
      }
    });
  }
});
