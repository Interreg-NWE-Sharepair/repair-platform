export default {
  props: {
    index: {
      type: Number,
      required: true
    },
    name: {
      type: String,
      required: true
    },
    value: {
      type: Object,
      default: () => null
    }
  },
  watch: {
    value: {
      deep: true,
      handler() {
        this.updateData();
      }
    },
    $data: {
      deep: true,
      handler() {
        this.emitInputEvent();
      }
    }
  },
  mounted() {
    if (this.value === null) {
      this.emitInputEvent();
    } else {
      this.updateData();
    }
  },
  methods: {
    emitInputEvent() {
      this.$emit('input', this.$data);
    },
    updateData() {
      Object.keys(this.value).forEach(key => {
        this[key] = this.value[key];
      });
    }
  }
};
