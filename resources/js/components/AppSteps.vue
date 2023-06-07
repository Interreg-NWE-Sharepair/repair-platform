<template>
  <div>
    <v-heading level="2" class="mb-0">
      {{ t('messages.step_title_device') }}
    </v-heading>
    <p>
      {{ t('messages.step_text') }}
    </p>
    <v-row>
      <v-col v-for="step in [0, 1, 2]" :key="step" cols="12" md="4" class="step text-left">
        <div
          :style="step === 2 && 'opacity: 0.5'"
          class="primary--text text-md-h1 font-weight-black justify-space-between"
        >
          {{ step + 1 }}
        </div>
        <v-row class="step__title font-weight-bold">
          <v-col cols="12" md="10">
            {{ t(`messages.step_${step}_title_device`) }}
          </v-col>
          <v-col v-if="step < 2 && $vuetify.breakpoint.mdAndUp" cols="12" md="2">
            <v-icon color="primary" large>
              mdi-arrow-right
            </v-icon>
          </v-col>
        </v-row>
        <v-row>
          <v-col :md="step < 2 && 10" cols="12">
            <p>
              {{ t(`messages.step_${step}_text`) }}
            </p>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </div>
</template>
