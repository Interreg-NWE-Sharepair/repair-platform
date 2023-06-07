<template>
  <r-panel v-if="device.event" class="bg-gray-100max-w-3xl">
    <div class="flex justify-content-center">
      <r-icon name="mdiCalendar" class="mr-3 text-huge text-secondary" />
      <div class="mr-3">
        <strong class="text-secondary">{{ t('messages.event_linked_title') }}</strong>
        <div>
          <a :href="route('event_show', { slug: device.event.slug })" target="_blank">
            {{ device.event.locale_name }} - {{ device.event.date_formatted }}
          </a>
        </div>
      </div>
    </div>
    <div class="mt-4 grid md:grid-cols-2">
      <div class="">
        <r-link
          v-if="device.event && (eventAdmin || canEditEvent || entityAdmin)"
          :href="route('device_unlink_event', { device: device.slug })"
          class="ml-auto"
          color="secondary"
          icon-before="mdiLinkOff"
          inertia
        >
          {{ t('messages.unlink') }}
        </r-link>
      </div>
      <div v-if="!device.event_follow_up_id">
        <r-link
          v-if="device.event && (eventAdmin || canEditEvent || entityAdmin)"
          :href="route('device_link_follow_up_via_device', { device: device.slug })"
          class="ml-auto ml-2"
          color="secondary"
          icon-before="mdiPlusCircle"
          inertia
        >
          {{ t('messages.button_assign_device') }}
        </r-link>
      </div>
      <div v-if="device.event_follow_up_id">
        <r-link
          v-if="device.event && (eventAdmin || canEditEvent || entityAdmin)"
          :href="route('device_unlink_event_follow_up', { device: device.slug, detail: true })"
          class="ml-auto ml-2"
          color="secondary"
          icon-before="mdiClose"
          inertia
        >
          {{ t('messages.remove_from_event_list') }}
        </r-link>
      </div>
    </div>
  </r-panel>
</template>
<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';

export default {
  mixins: [DeviceDetail]
};
</script>
