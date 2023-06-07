<template>
  <r-panel>
    <h2 class="flex items-center justify-between text-h3 text-secondary">
      <span>{{ t('messages.contact') }}</span>
      <button
        v-if="editContact && device.latest_status !== 'completed'"
        type="button"
        class="ml-auto text-base bg-white bg-opacity-0"
        v-tooltip="{
          content: t('messages.edit'),
          trigger: 'click hover'
        }"
        @click="$modal.show('device_contact_edit')"
      >
        <r-icon name="mdiPencil" class="ml-1 text-secondary" />
      </button>
    </h2>
    <div class="flex items-center mb-3">
      <r-icon name="mdiAccount" class="mr-2 text-secondary" />
      <span> {{ device.first_name }} {{ device.last_name }} </span>
    </div>
    <div v-if="device.email" class="flex items-center mb-3">
      <r-icon name="mdiEmail" class="mr-2 text-secondary" />
      <span>
        <a :href="`mailto:${device.email}`">{{ device.email }}</a>
      </span>
    </div>
    <div v-if="device.telephone" class="flex items-center mb-3">
      <r-icon name="mdiPhone" class="mr-2 text-secondary" />
      <span>
        <a :href="`tel:${device.telephone}`">{{ device.telephone }}</a>
      </span>
    </div>
    <div v-if="device.postal_code" class="flex items-center mb-3">
      <r-icon name="mdiMapMarker" class="mr-2 text-secondary" />
      <span> {{ t('messages.form_organisation_postal_code') }}: {{ device.postal_code }} </span>
    </div>
  </r-panel>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';

export default {
  mixins: [DeviceDetail]
};
</script>
