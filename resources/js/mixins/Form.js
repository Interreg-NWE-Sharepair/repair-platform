export default {
  model: {
    prop: 'modelValue',
    emits: 'update:modelValue'
  },
  props: {
    modelValue: {
      type: Object,
      default: () => {}
    }
  },
  data: () => ({
    formModel: null,
    isLoading: false
  }),
  watch: {
    modelValue: {
      immediate: true,
      handler() {
        this.formModel = this.modelValue;
      }
    },
    formModel(formModel) {
      this.$emit('update:modelValue', formModel);
    }
  }
};
