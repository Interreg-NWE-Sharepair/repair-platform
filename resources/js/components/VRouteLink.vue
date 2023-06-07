<template>
  <inertia-link :data="{ anchor }" :href="href" v-bind="{ ...$props, ...$attrs }">
    <slot>
      <v-icon v-if="icon" small left>{{ icon }}</v-icon>
      <span v-if="name">
        {{ name }}
      </span>
      <span v-else>
        {{ t(`messages.route_${id}`) }}
      </span>
    </slot>
  </inertia-link>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      required: true
    },
    params: {
      type: Object,
      default: () => ({})
    },
    name: {
      type: String,
      default: () => null
    },
    icon: {
      type: String,
      default: () => null
    },
    anchor: {
      type: String,
      default: ''
    }
  },
  computed: {
    href() {
      try {
        return this.route(this.id, this.params);
      } catch (e) {
        return '#';
      }
    }
  }
};
</script>
