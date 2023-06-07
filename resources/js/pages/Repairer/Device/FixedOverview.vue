<template>
  <layout-base>
    <v-container>
      <v-heading color="secondary">
        {{
          t('messages.repairer_fixed_overview_title', {
            location: $page.props.user.location
          })
        }}
      </v-heading>
      <device-list endpoint="/api/devices/active" :filter-options="deviceFilters" class="mb-4" />
      <inertia-link :href="route('location_devices_overview', { locationCode: $page.props.user.locationCode })">
        <span
          v-html="
            t('messages.route_repairer_general_overview', {
              location: $page.props.user.location
            })
          "
        ></span>
      </inertia-link>
    </v-container>
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
