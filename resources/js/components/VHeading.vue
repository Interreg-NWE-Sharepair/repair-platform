<template>
  <component :is="tagName" :class="`${color}--text text-${tagName}`" class="heading">
    <slot></slot>
  </component>
</template>

<script>
export default {
  props: {
    level: {
      type: String,
      default: () => '1'
    },
    color: {
      type: String,
      default: () => 'primary'
    }
  },
  computed: {
    tagName() {
      return `h${this.level}`;
    }
  }
};
</script>

<style lang="scss">
.heading {
  margin-top: 0.25em;
  margin-bottom: 0.75em;

  &:first-child {
    margin-top: 0;
  }

  &:last-child {
    margin-bottom: 0;
  }
}
</style>
