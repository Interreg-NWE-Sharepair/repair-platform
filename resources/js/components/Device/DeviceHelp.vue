<template>
  <r-panel>
    <h2 class="text-h3 text-secondary flex items-center">
      {{ t('messages.device_help_title') }}
    </h2>
    <p
      v-html="
        t('messages.device_help_text', {
          urlRelated
        })
      "
    />
  </r-panel>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';

export default {
  mixins: [DeviceDetail],
  computed: {
    urlRelated() {
      const { device } = this;

      /*const locationCode = device.organisation.slug[$page.props.locale];*/

      const query = `type[]=${device.device_type_id}`;

      return `${this.route('history_repair_log_overview')}?${query}`;
    }
  }
};
</script>
