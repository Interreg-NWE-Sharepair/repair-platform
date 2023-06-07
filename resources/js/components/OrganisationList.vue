<template>
  <div>
    <r-section v-if="search" class="bg-gray-100">
      <div class="flex flex-wrap items-center -mx-4 -mt-4">
        <div class="w-full px-4 mt-4 md:w-2/5">
          <strong>{{ t('messages.location_search_label') }}</strong>
          <div class="flex items-center">
            <r-mapbox-search
              v-model="place"
              :placeholder="t('messages.location_search_placeholder')"
              :loading="isLoading"
              :config="$page.props.mapbox"
              class="grow !mb-0"
              required
            />
            <a
              href="javascript:void(0)"
              v-if="place"
              v-tooltip="{
                content: t('messages.location_search_clear_tooltip'),
                trigger: 'click hover'
              }"
              class="flex-shrink-0 ml-3 no-underline text-huge"
              @click.prevent="clearSearch()"
            >
              <r-icon name="mdiClose" />
            </a>
          </div>
        </div>
        <div class="w-full px-4 mt-4 md:w-2/5">
          <r-input
            v-model="query.search"
            :label="t('messages.location_search_name_label')"
            :placeholder="t('messages.location_search_name_placeholder')"
            class="flex-grow-1 !mb-0"
            required
          />
        </div>
      </div>
    </r-section>

    <r-section>
      <r-grid>
        <r-grid-item v-for="(location, key) in pageResults" :key="key" class="w-100 sm:w-6/12 md:w-4/12">
          <slot :data="location"></slot>
        </r-grid-item>
      </r-grid>
      <r-pagination v-model="page" :pages="totalPages" class="mt-6" />
    </r-section>
  </div>
</template>

<script>
export default {
  props: {
    search: {
      type: Boolean,
      default: () => true
    }
  },
  data: () => ({
    isLoading: true,
    place: null,
    results: [],
    page: 1,
    query: {
      search: null
    }
  }),
  computed: {
    totalPages() {
      return Math.floor(this.results.length / 9) + 1;
    },
    pageResults() {
      return this.results.slice((this.page - 1) * 9, this.page * 9);
    }
  },
  watch: {
    place() {
      this.getResults();
    },
    query: {
      deep: true,
      handler() {
        this.getResults();
      }
    }
  },
  created() {
    this.getResults();
  },
  methods: {
    async getResults() {
      const { place } = this;

      this.isLoading = true;

      const { data } =
        place || this.query.search
          ? await window.axios.post(
              `/api/organisations/search?query=${this.query.search}`,
              this.createFormData({ place })
            )
          : await window.axios.get(`/api/organisations/active?lang=${this.$page.props.locale}`);

      this.results = data.data || data;

      this.isLoading = false;
    },
    clearSearch() {
      this.place = null;
      this.getResults();
    }
  }
};
</script>
