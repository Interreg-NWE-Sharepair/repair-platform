<template>
  <layout-base>
    <v-section>
      <v-heading color="secondary">
        {{ t('messages.route_repair_history_overview') }}
      </v-heading>
      <device-list endpoint="/api/devices/repaired" :filter-options="deviceFilters" />
    </v-section>
  </layout-base>
</template>

<script>
import DeviceList from '@/js/components/DeviceList';

export default {
  components: {
    DeviceList
  },
  props: {
    deviceFilters: {
      type: Object,
      default: () => ({
        deviceType: [],
        status: []
      })
    }
  }
};
</script>
