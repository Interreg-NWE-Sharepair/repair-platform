<template>
  <layout-dashboard :organisation="organisation" active-tab="general_overview">
    <r-section>
      <r-grid>
        <r-grid-item class="md:w-7/12">
          <h2 class="text-h2 text-secondary">{{ t('messages.repairer_devices_overview') }}</h2>
          <device-list :endpoint="`/api/devices/organisation/overview/${organisation.uuid}`" class="mb-6" />
          <r-link
            inertia
            :href="route('location_devices_overview', { locationCode: organisation.slug[$page.props.locale] })"
            icon-after="mdiChevronRight"
          >
            {{
              t('messages.route_repairer_general_overview', {
                location: organisation.name[$page.props.locale]
              })
            }}
          </r-link>
        </r-grid-item>
        <r-grid-item class="md:w-5/12">
          <h2 class="text-h2 text-secondary">
            {{ t('messages.future_events') }}
          </h2>
          <event-card-organisation v-for="event in events" :key="event.id" :data="event" />
          <r-link
            v-if="events"
            inertia
            :href="route('location_events_overview', { locationCode: organisation.slug[$page.props.locale] })"
            icon-after="mdiChevronRight"
          >
            {{
              t('messages.repairer_location_events_overview_title', {
                location: organisation.name[$page.props.locale]
              })
            }}
          </r-link>
          <span v-if="!events">
            {{ t('messages.no_future_events') }}
          </span>
        </r-grid-item>
      </r-grid>
    </r-section>
  </layout-dashboard>
</template>

<script>
import LayoutDashboard from '@/js/layouts/LayoutDashboard';
import DeviceList from '@/js/components/DeviceList';
import EventCardOrganisation from '../../components/EventCardOrganisation';

export default {
  components: {
    LayoutDashboard,
    EventCardOrganisation,
    DeviceList
  },
  props: {
    organisation: {
      type: Object,
      default: () => null
    },
    events: {
      type: Array,
      default: () => []
    }
  }
};
</script>
