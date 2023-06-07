<template>
  <div
    :class="{
      'v-section--default': !light && !hero,
      'v-section--light grey lighten-4': light || hero,
      'py-8': !large && !hero,
      'py-12': large || hero
    }"
    class="v-section"
  >
    <v-container>
      <v-row>
        <v-col :md="computedCols" cols="12">
          <slot></slot>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  props: {
    light: {
      type: Boolean,
      default: () => false
    },
    large: {
      type: Boolean,
      default: () => false
    },
    hero: {
      type: Boolean,
      default: () => false
    },
    cols: {
      type: String,
      default: () => null
    }
  },
  computed: {
    computedCols() {
      const { cols, hero } = this;

      if (cols) return cols;

      if (hero) return '7';

      return '12';
    }
  }
};
</script>

<style lang="scss">
.v-section.v-section--default + .v-section--default,
.v-section.v-section--default + div > .v-section--default:first-child,
.v-section.v-section--light + .v-section--light,
.v-section.v-section--light + div > .v-section--light:first-child {
  padding-top: 0 !important;
}
</style>
