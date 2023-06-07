<template>
  <div>
    <v-label v-if="label" :for="computedId" class="mb-1">
      {{ label }}
    </v-label>
    <slot v-bind="inputProps"></slot>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: () => null
    }
  },
  computed: {
    computedId() {
      return `form-input-${this._uid}`;
    },
    inputProps() {
      return {
        id: this.computedId,
        outlined: true,
        solo: true,
        filled: true,
        dense: true,
        flat: true,
        class: 'my-0'
      };
    }
  }
};
</script>

<style lang="scss" scoped>
.v-label {
  font-size: 16px;
  font-weight: 700;
}
</style>
