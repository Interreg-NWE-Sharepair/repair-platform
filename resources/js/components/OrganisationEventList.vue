<template>
  <div>
    <r-section>
      <r-grid>
        <r-grid-item v-for="(event, key) in events" :key="key" class="w-100 sm:w-6/12 md:w-1/2">
          <event-card :data="event" :locationDetail="true" class="flex flex-col items-stretch" />
        </r-grid-item>
      </r-grid>
      <r-pagination v-model="page" :pages="pages" class="mt-6" />
    </r-section>
  </div>
</template>

<script>
import EventCard from './EventCard';
export default {
  components: { EventCard },
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
        .get('/api/organisation/events/future/' + this.$page.props.organisation.uuid + '?page=' + page)
        .then(({ data: { data, last_page } }) => {
          this.events = data;
          this.pages = last_page;
        });
    }
  }
};
</script>
