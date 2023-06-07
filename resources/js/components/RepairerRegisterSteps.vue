<template>
  <div>
    <v-row>
      <v-col v-for="step in [0, 1, 2]" :key="step" cols="12" md="4" class="step text-left">
        <div
          :style="step !== steps && 'opacity: 0.5'"
          class="secondary--text text-md-h1 font-weight-black justify-space-between"
        >
          {{ step + 1 }}
        </div>
        <v-row :style="step !== steps && 'opacity: 0.5'" class="step__title font-weight-bold">
          <v-col cols="12" md="10">
            {{ t(`messages.step_${step}_title_repairer`) }}
          </v-col>
          <v-col v-if="step < 2 && $vuetify.breakpoint.mdAndUp" cols="12" md="2">
            <v-icon color="secondary" large>
              mdi-arrow-right
            </v-icon>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <!-- <v-flex>
            <v-btn
                :disabled="step === 1"
                @click="$emit('clickPrev')"
                color="primary"
            >
                {{ t('messages.form_previous_step') }}
            </v-btn>
            <v-spacer />
            <v-btn
                :disabled="step === 3"
                @click="$emit('clickNext')"
                color="primary"
            >
                {{ t('messages.form_next_step') }}
            </v-btn>
        </v-flex> -->
  </div>
</template>
<script>
export default {
  props: {
    steps: {
      type: Number,
      default: 1
    }
  }
};
</script>
