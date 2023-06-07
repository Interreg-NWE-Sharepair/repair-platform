<template>
  <layout-dashboard :organisation="organisation" active-tab="events_overview">
    <r-section>
      <h2 class="text-h2 text-secondary">
        {{ title }}
      </h2>
      <div class="mb-6">
        <div v-if="futureEvents">
          <organisation-event-list></organisation-event-list>
          <!--          <event-card v-for="event in futureEvents" :key="event.id" :data="event" />-->
        </div>
        <!--        <div v-else v-html="t('messages.no_future_events')" />-->
      </div>

      <div v-if="pastEvents">
        <h2 class="text-h3 text-secondary">{{ t('messages.past_events') }}</h2>
        <r-grid>
          <r-grid-item v-for="(pastEvent, key) in pastEvents" :key="key" class="w-100 sm:w-6/12 md:w-1/2">
            <event-card :data="pastEvent" :locationDetail="true" class="flex flex-col items-stretch" />
          </r-grid-item>
        </r-grid>
        <!--        <div class="w-100 sm:w-6/12 md:w-1/2">
          <event-card v-for="event in pastEvents" :key="event.id" :data="event" />
        </div>-->
        <r-link
          inertia
          :href="route('location_past_events_overview', { locationCode: organisation.slug[$page.props.locale] })"
          icon-after="mdiChevronRight"
        >
          {{
            t('messages.all_past_events', {
              location: organisation.name[$page.props.locale]
            })
          }}
        </r-link>
      </div>
    </r-section>
  </layout-dashboard>
</template>

<script>
import LayoutDashboard from '@/js/layouts/LayoutDashboard';
import EventCard from '../../../components/EventCard';
import OrganisationEventList from '../../../components/OrganisationEventList';

export default {
  components: { OrganisationEventList, EventCard, LayoutDashboard },
  props: {
    organisation: {
      type: Object,
      default: () => null
    },
    title: {
      type: String,
      default: null
    },
    futureEvents: {
      type: Array,
      default: () => []
    },
    pastEvents: {
      type: Array,
      default: () => []
    }
  }
};
</script>
