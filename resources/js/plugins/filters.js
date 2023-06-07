Vue.use({
  install(Vue) {
    Vue.filter('stripTags', string => string.replace(/<\/?[^>]+>/gi, ' '));
  }
});
