<template>
  <layout-dashboard :organisation="organisation" active-tab="devices_overview">
    <r-section>
      <h2 class="text-h2 text-secondary">
        {{
          t('messages.location_overview_devices_fixed_title', {
            location: organisation.name[$page.props.locale]
          })
        }}
      </h2>
      <device-list :endpoint="`/api/devices/fixed/repairer/${organisation.uuid}`" />
    </r-section>
  </layout-dashboard>
</template>

<script>
import LayoutDashboard from '@/js/layouts/LayoutDashboard';
import DeviceList from '@/js/components/DeviceList';

export default {
  components: {
    DeviceList,
    LayoutDashboard
  },
  props: {
    organisation: {
      type: Object,
      default: () => null
    }
  }
};
</script>
