<template>
  <div class="d-flex step-actions" v-bind:class="{ 'mb-6': marginBottom }">
    <v-btn
      :disabled="disablePrev"
      @click="$emit('click-prev')"
      :color="color"
      class="px-4 text-tiny md:text-base md:px-12"
    >
      <v-icon left>mdi-chevron-left</v-icon>
      {{ t('messages.form_previous_step') }}
    </v-btn>
    <v-spacer />
    <v-btn
      v-if="step !== 3"
      :disabled="disableNext"
      @click="$emit('click-next')"
      :color="color"
      class="px-4 text-tiny md:text-base md:px-12"
    >
      {{ t('messages.form_next_step') }}
      <v-icon right>mdi-chevron-right</v-icon>
    </v-btn>
  </div>
</template>

<script>
export default {
  props: {
    step: {
      type: Number,
      required: true
    },
    marginBottom: {
      type: Boolean,
      default: () => true
    },
    color: {
      type: String,
      default: () => 'primary'
    },
    disablePrev: {
      type: Boolean,
      default: () => false
    },
    disableNext: {
      type: Boolean,
      default: () => false
    }
  }
};
</script>
