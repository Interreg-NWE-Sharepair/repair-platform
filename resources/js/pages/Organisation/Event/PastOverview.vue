<template>
  <layout-dashboard :organisation="organisation" active-tab="events_overview">
    <r-section>
      <h2 class="text-h2 text-secondary">{{ title }}</h2>
      <div class="md:w-8/12">
        <event-card v-for="event in events" :key="event.id" :data="event" :locationDetail="true" />
        <r-pagination v-model="page" :pages="pages" />
      </div>
    </r-section>
  </layout-dashboard>
</template>

<script>
import LayoutDashboard from '@/js/layouts/LayoutDashboard';
import EventCard from '@/js/components/EventCard';

export default {
  components: { EventCard, LayoutDashboard },
  props: {
    organisation: {
      type: Object,
      default: () => null
    },
    title: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      events: [],
      page: 1,
      pages: 1
    };
  },

  watch: {
    page: {
      handler(page) {
        this.getResults(page);
      },
      immediate: true
    }
  },

  methods: {
    // Our method to GET results from a Laravel endpoint
    getResults(page = 1) {
      axios
        .get('/api/organisation/events/past/' + this.organisation.uuid + '?page=' + page)
        .then(({ data: { data, last_page } }) => {
          this.events = data;
          this.pages = last_page;
        });
    }
  }
};
</script>
