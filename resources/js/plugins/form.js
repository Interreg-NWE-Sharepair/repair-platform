Vue.use({
  install(Vue) {
    Vue.mixin({
      methods: {
        getErrorMessages(fieldName) {
          const fieldErrorKeys = Object.keys(this.$page.props.errors).filter(key => key.indexOf(fieldName) === 0);

          if (!fieldErrorKeys.length) return [];

          return fieldErrorKeys.map(errorKey => this.$page.props.errors[errorKey][0]);
        },
        createFormData(formObject) {
          const formData = new FormData();

          formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

          function buildFormData(data, parentKey) {
            if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
              Object.keys(data).forEach(key => {
                buildFormData(data[key], parentKey ? `${parentKey}[${key}]` : key);
              });
            } else {
              const value = data == null ? '' : data;

              formData.append(parentKey, value);
            }
          }

          buildFormData(formObject);

          return formData;
        }
      }
    });
  }
});
