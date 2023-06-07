<template>
  <layout-dashboard :organisation="organisation" active-tab="events_overview">
    <r-section>
      <r-link
        :href="route('location_events_overview', { locationCode: organisation.slug[this.$page.props.locale] })"
        inertia
        icon-before="mdiChevronLeft"
        class="mt-3"
      >
        {{ t('messages.events_overview') }}
      </r-link>
      <h2 class="text-h2 text-secondary">
        {{ event.locale_name }}
      </h2>
      <div class="mb-6">
        <div v-if="event.is_online || event.address" class="flex items-center">
          <r-icon name="mdiMapMarker" class="mr-1 text-secondary" />
          <span v-if="event.is_online">{{ t('messages.online') }}</span>
          <span v-else>{{ event.address }}</span>
        </div>
        <div class="flex items-center">
          <r-icon name="mdiCalendar" class="mr-1 text-secondary" />
          <span>{{ t('messages.event_when', { at: event.starts_at }) }} {{ event.ends_at }}</span>
        </div>
        <div v-if="event.organizer" class="flex items-center">
          <r-icon name="mdiAccount" class="mr-1 text-secondary" />
          <span>{{ event.organizer }}</span>
        </div>
        <div class="mt-4">
          <div v-if="event.max_devices">
            <span class="font-bold">
              {{ t('messages.registered_devices', { amount: event.registered_devices, total: event.max_devices }) }}
            </span>
          </div>
        </div>
      </div>
    </r-section>
    <r-section v-if="eventAdmin || entityAdmin">
      <div class="flex flex-wrap -m-2">
        <r-button
          link
          color="secondary"
          :href="
            route('device_create', { locationCode: organisation.slug[this.$page.props.locale], step: 1 }) +
              '?event=' +
              event.slug
          "
          class="m-2"
        >
          {{ t('messages.register_device_event') }}
        </r-button>
        <r-button
          ghost
          link
          :href="
            route('location_devices_overview', { locationCode: organisation.slug[this.$page.props.locale] }) +
              '?event[]=' +
              event.id
          "
          class="m-2"
        >
          {{ t('messages.view_registered_devices') }}
        </r-button>
      </div>
    </r-section>
    <r-section v-if="devices && (eventAdmin || entityAdmin)">
      <h3 class="font-bold text-h3">{{ t('messages.event_devices_order') }}</h3>
      <div v-if="devices.length > 0" class="w-full overflow-x-auto">
        <table class="table min-w-[850px]">
          <thead class="border-gray-400 border-b-1">
            <th class="px-2 py-3">{{ t('messages.device_follow_up') }}</th>
            <th class="px-2 py-3">{{ t('messages.device_rc_nr') }}</th>
            <th class="px-2 py-3">{{ t('messages.device') }}</th>
            <th class="px-2 py-3">{{ t('messages.owner') }}</th>
            <th class="px-2 py-3">{{ t('messages.repairer_name') }}</th>
            <th class="px-2 py-3">{{ t('messages.device_status') }}</th>
            <th class="px-2 py-3"></th>
          </thead>
          <tbody>
            <tr v-for="(device, index) in devices" :key="index" :class="{ 'bg-gray-200': index % 2 === 0 }">
              <td class="px-2 py-2">{{ device.event_follow_up_id }}</td>
              <td class="px-2 py-2">#{{ device.id }}</td>
              <td class="px-2 py-2">
                <a :href="route('device_show', { slug: device.slug })" target="_blank"
                  >{{ device.brand_name }} - {{ device.model_name }}</a
                >
              </td>
              <td class="px-2 py-2">{{ device.first_name }} {{ device.last_name }}</td>
              <td class="px-2 py-2" v-if="device.status_last_updated_at">{{ device.repairer_name }}</td>
              <td class="px-2 py-2" v-else>/</td>
              <td class="px-2 py-2">
                <span
                  class="flex-shrink-0 inline-block w-2 h-2 mr-2 rounded-full"
                  :class="`bg-status-${device.latest_status}`"
                ></span>
                <span>
                  {{ t(`messages.status_${device.latest_status}`) }}
                </span>
              </td>
              <td class="px-2 py-2 align-middle">
                <r-link
                  v-if="device.event && (eventAdmin || entityAdmin)"
                  :href="route('device_unlink_event_follow_up', { device: device.slug })"
                  :title="t('messages.remove_from_event_list')"
                  v-tooltip="{
                    content: t('messages.remove_from_event_list'),
                    trigger: 'click hover'
                  }"
                  class="!flex items-center h-full ml-auto"
                  color="secondary"
                  icon-after="mdiClose"
                  inertia
                >
                  <span class="sr-only">{{ t('messages.remove_from_event_list') }}</span>
                </r-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>{{ t('messages.no_registered_event_devices') }}</div>
      <div v-if="eventAdmin || entityAdmin" class="mt-4">
        <r-link icon-before="mdiPlusCircle" class="m-2" @click.native="$modal.show('event_assign_device')">
          {{ t('messages.event_add_device') }}
        </r-link>
      </div>
    </r-section>

    <add-device-modal :endpoint="`/api/event/devices/search/${event.slug}`" :event="event" v-bind="$props" />
    <r-section class="bg-gray-100">
      <stats-event-embed :event="event.slug" :lang="$page.props.locale"></stats-event-embed>
    </r-section>
  </layout-dashboard>
</template>

<script>
import LayoutDashboard from '@/js/layouts/LayoutDashboard';
import AddDeviceModal from '@/js/components/Event/AddDeviceModal';
import StatsEventEmbed from '@/js/components/StatsEventEmbed';

export default {
  components: { AddDeviceModal, LayoutDashboard, StatsEventEmbed },
  props: {
    organisation: {
      type: Object,
      default: () => null
    },
    event: {
      type: Object,
      default: () => null
    },
    devices: {
      type: Array,
      default: () => null
    },
    title: {
      type: String,
      default: null
    },
    eventAdmin: {
      type: Boolean,
      default: () => false
    },
    entityAdmin: {
      type: Boolean,
      default: () => false
    }
  }
};
</script>
