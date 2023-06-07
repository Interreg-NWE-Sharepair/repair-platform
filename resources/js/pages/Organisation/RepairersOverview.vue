<template>
  <layout-dashboard :organisation="organisation" active-tab="repairers_overview">
    <r-section>
      <h2 class="text-h2 text-secondary">{{ title }}</h2>
      <div class="md:w-9/12">
        <r-input v-model="search" :label="t('messages.search')" class="mb-6" required />
        <div v-if="isLoading" class="text-center text-huge text-secondary">
          <r-icon name="mdiLoading" class="animate-spin" />
        </div>
        <div class="grid xl:grid-cols-2 gap-4" v-else>
          <repairer-card v-for="employee in employees" :key="employee.id" :data="employee" />
        </div>
        <r-pagination v-model="page" :pages="pages" />
      </div>
    </r-section>
  </layout-dashboard>
</template>

<script>
import debounce from 'lodash/debounce';

import LayoutDashboard from '@/js/layouts/LayoutDashboard';
import RepairerCard from '@/js/components/RepairerCard';

export default {
  components: { RepairerCard, LayoutDashboard },
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
      employees: [],
      page: 1,
      pages: 1,
      search: '',
      isLoading: false
    };
  },

  watch: {
    page: {
      handler(page) {
        this.getResults(page);
      },
      immediate: true
    },
    search: debounce(function() {
      this.getResults(this.page);
    }, 500)
  },

  methods: {
    getResults(page = 1) {
      this.isLoading = true;
      axios
        .get(
          `/api/organisation/repairers/${this.organisation.uuid}?page=${page}&search=${this.search}&lang=${this.$page.props.locale}`
        )
        .then(({ data: { data, last_page } }) => {
          this.employees = data;
          this.pages = last_page;
          this.isLoading = false;
        });
    }
  }
};
</script>
