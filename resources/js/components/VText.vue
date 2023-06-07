<template>
  <p :class="{ 'v-text--lg': large, 'v-text--sm': small }" class="v-text">
    <slot></slot>
  </p>
</template>

<script>
export default {
  props: {
    large: {
      type: Boolean,
      default: () => false
    },
    small: {
      type: Boolean,
      default: () => false
    }
  }
};
</script>

<style lang="scss">
.v-text {
  margin-bottom: 1.5em;
  &:last-child {
    margin-bottom: 0;
  }
}

.v-text.v-text--sm {
  font-size: 16px;
}

.v-text.v-text--lg {
  font-size: 20px;
}
</style>
