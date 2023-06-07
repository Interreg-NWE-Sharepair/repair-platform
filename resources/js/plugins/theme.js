Vue.use({
  install(Vue) {
    Vue.mixin({
      computed: {
        themeColor() {
          return this.$page.props.user ? 'secondary' : 'primary';
        }
      }
    });
  }
});
