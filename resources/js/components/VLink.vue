<template>
  <component :is="component" v-bind="$attrs" class="d-inline-flex align-center black--text font-weight-bold">
    <v-icon v-if="iconBefore" :color="iconColor" class="d-block" small left>
      {{ iconBefore }}
    </v-icon>
    <span>
      <slot></slot>
    </span>
    <v-icon v-if="iconAfter" :color="iconColor" class="d-block" small right>
      {{ iconAfter }}
    </v-icon>
  </component>
</template>

<script>
export default {
  props: {
    ext: {
      type: Boolean,
      default: () => false
    },
    iconBefore: {
      type: String,
      default: () => null
    },
    iconAfter: {
      type: String,
      default: () => null
    },
    iconColor: {
      type: String,
      default: () => 'primary'
    }
  },
  computed: {
    component() {
      return this.ext ? 'a' : 'inertia-link';
    }
  }
};
</script>

<style></style>
