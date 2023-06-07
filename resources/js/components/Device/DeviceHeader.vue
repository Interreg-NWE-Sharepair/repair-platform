<template>
  <div class="border-0 border-l-[8px] border-solid pl-8 mb-6" :class="`border-status-${device.latest_status}`">
    <h1 class="text-h1 text-secondary mb-2">{{ device.brand_name }} {{ device.model_name }}</h1>
    <div class="text-large italic mb-2">
      #{{ device.padded_id }}
      <span v-if="device.latest_status !== 'completed'">
        {{ t('messages.registered_by_person', { owner: device.owner_name }) }}
        {{ t('messages.at_time', { at: device.created_timestamp }) }}
      </span>
      <span v-if="device.postal_code && device.latest_status !== 'completed'">
        - {{ t('messages.device_postal_code', { postalcode: device.postal_code }) }}
      </span>
    </div>
    <div class="flex items-center mb-1">
      <r-icon name="mdiCheckboxBlankCircle" :class="`text-status-${device.latest_status}`" />
      <span class="font-bold ml-1">{{ t(`messages.status_${device.latest_status}`) }}</span>
    </div>
    <div v-if="device.latest_status === 'in_repair'" class="flex items-center">
      <r-icon name="mdiProgressWrench" />
      <span class="ml-1">{{ device.repair_log.person.full_name }}</span>
    </div>
    <div v-if="device.latest_status === 'completed'" class="flex items-center">
      <span class="ml-1">{{ device.repair_log.status }}</span>
    </div>
  </div>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';

export default {
  mixins: [DeviceDetail]
};
</script>
