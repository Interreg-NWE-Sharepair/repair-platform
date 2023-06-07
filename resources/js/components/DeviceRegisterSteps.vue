<template>
  <div>
    <v-row class="hidden md:flex">
      <v-col v-for="step in [0, 1, 2]" :key="step" cols="12" md="4" class="text-left step">
        <div
          :style="step + 1 !== steps && 'opacity: 0.5'"
          class="primary--text text-md-h1 font-weight-black justify-space-between"
        >
          {{ step + 1 }}
        </div>
        <v-row :style="step + 1 !== steps && 'opacity: 0.5'" class="step__title font-weight-bold">
          <v-col cols="12" md="10">
            {{ t(`messages.step_${step}_title_device`) }}
          </v-col>
          <v-col v-if="step < 2 && $vuetify.breakpoint.mdAndUp" cols="12" md="2">
            <v-icon color="primary" large>
              mdi-arrow-right
            </v-icon>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <!-- Mobile view -->
    <v-row class="md:hidden">
      <v-col v-for="step in [0, 1, 2]" :key="step" cols="12" md="4" class="flex items-center px-2 py-0">
        <div
          :style="step + 1 !== steps && 'display: none'"
          class="flex items-center mb-0 primary--text text-h1 font-weight-black"
        >
          {{ step + 1 }} <span class="block w-px h-8 mx-4 transform bg-gray-400 rotate-12"></span>
          <span class="mt-0 opacity-50 text-h3">3</span>
        </div>
        <v-row
          :style="step + 1 !== steps && 'display: none'"
          class="mt-0 ml-4 step__title text-h4 sm:text-h1 text-primary"
        >
          <v-col cols="12" md="10" class="p-0">
            {{ t(`messages.step_${step}_title_device`) }}
          </v-col>
          <v-col v-if="step < 2 && $vuetify.breakpoint.mdAndUp" cols="12" md="2">
            <v-icon color="primary" large>
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
