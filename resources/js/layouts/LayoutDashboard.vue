<template>
  <layout-base>
    <r-section class="pb-0 bg-gray-100">
      <h1 class="text-h1 text-secondary">{{ organisation.name[$page.props.locale] }}</h1>
      <r-tabs
        :hasContent="false"
        v-if="tabValue"
        v-model="tabValue"
        :values="tabValues"
        :labels="tabLabels"
        bg="white"
        class="mt-6 tabs-container"
      />
    </r-section>
    <slot />
  </layout-base>
</template>

<script>
export default {
  props: {
    organisation: {
      type: Object,
      required: true
    },
    activeTab: {
      type: String,
      default: () => null
    }
  },
  data() {
    return {
      tabValue: this.activeTab
    };
  },
  computed: {
    tabValues() {
      return ['general_overview', 'devices_overview', 'repairers_overview', 'events_overview', 'impact_overview'];
    },
    tabLabels() {
      return this.tabValues.reduce((accumulator, currentValue) => {
        accumulator[currentValue] = this.t(`messages.repairer_${currentValue}`);
        return accumulator;
      }, {});
    }
  },
  watch: {
    tabValue(tabValue, oldTabValue) {
      if (oldTabValue !== null) {
        this.visitRoute(`location_${tabValue}`, { locationCode: this.organisation.slug[this.$page.props.locale] });
      }
    }
  }
};
</script>
<!-- Tweaks for mobile view -->
<style>
.tabs-container div button > div {
  @apply text-tiny md:text-small px-1 md:px-2 min-w-[65px] md:min-w-[100px] !important;
}
</style>
