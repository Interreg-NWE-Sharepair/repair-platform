<template>
  <r-grid>
    <r-grid-item v-if="showFilters" class="lg:sticky lg:top-[135px] lg:self-start lg:w-4/12">
      <r-input v-model="query.search" :label="t('messages.devices_search')" required />
      <r-form-field v-if="filterOptions.order" :label="t('messages.devices_sort')" required>
        <r-radio v-model="query.sort" :label="t('messages.devices_sort_date_desc')" value="desc" />
        <r-radio v-model="query.sort" :label="t('messages.devices_sort_date_asc')" value="asc" />
        <r-radio v-model="query.sort" :label="t('messages.devices_sort_status')" value="status" />
      </r-form-field>
      <r-select
        v-model="query.type"
        :placeholder="t('messages.form_device_type_placeholder')"
        :options="deviceTypes"
        :multiple="true"
        :group-select="false"
        :label="t('messages.devices_filter_device_type')"
        class="select__container"
        track-by="value"
        label-by="name"
        group-label="name"
        group-values="children"
        searchable="searchable"
      />
      <r-select
        v-model="query.event"
        :placeholder="t('messages.filter_events.placeholder')"
        :options="filterOptions.events"
        :multiple="true"
        :label="t('messages.filter_events')"
        class="select__container"
        track-by="value"
        label-by="name"
        group-label="name"
        group-values="children"
        searchable="searchable"
        v-if="filterOptions.events && filterOptions.events.length"
      />
      <r-form-field v-if="filterOptions.status" :label="t('messages.devices_filter_status')" required>
        <r-checkbox
          v-model="query.status"
          v-for="(status, key) in filterOptions.status"
          :key="key"
          :label="status.text"
          :value="status.value"
        >
          <template #label>
            <span :class="`inline-block w-3 h-3 rounded-full mr-2 bg-status-${status.status.toLowerCase()}`"></span>
            <span>{{ status.text }}</span>
          </template>
        </r-checkbox>
      </r-form-field>
    </r-grid-item>
    <r-grid-item :class="{ 'lg:w-8/12': showFilters }">
      <div class="relative mb-6">
        <div v-show="isLoading" class="absolute inset-0 z-10 bg-white bg-opacity-50 cursor-wait pointer-events-none">
          <span class="absolute transform translate-x-1/2 translate-y-1/2 bottom-1/2 right-1/2">
            <r-icon name="mdiLoading" class="text-huge text-secondary animate-spin" />
          </span>
        </div>
        <div v-if="response && response.total && showFilters">
          <p class="text-italic">
            {{ t('messages.amount_devices_found', { total: response.total }) }}
          </p>
        </div>
        <div v-if="response && response.data.length">
          <device-card v-for="item in response.data" :key="item.id" :data="item" :isLoading="isLoading" class="mb-5" />
          <r-pagination v-if="pagination" v-model="query.page" :pages="response.last_page" />
        </div>
        <div v-else>
          <p class="text-italic">
            {{ t('messages.device_list_empty') }}
          </p>
        </div>
      </div>
    </r-grid-item>
  </r-grid>
</template>

<script>
import debounce from 'lodash.debounce';
import qs from 'qs';

import DeviceCard from '@/js/components/DeviceCard';
// import PaginatedList from '@/js/components/PaginatedList';

export default {
  components: {
    DeviceCard
    // PaginatedList
  },
  props: {
    endpoint: {
      type: String,
      required: true
    },
    filterOptions: {
      type: Object,
      default: () => ({
        deviceType: [],
        status: [],
        events: []
      })
    },
    pagination: {
      type: Boolean,
      default: () => true
    }
  },
  data: () => ({
    isLoading: true,
    response: null,
    query: {
      search: null,
      type: [],
      event: [],
      status: [],
      sort: 'status',
      page: 1,
      lang: document.documentElement.lang
    }
  }),
  computed: {
    showFilters() {
      return this.filterOptions.deviceType.length > 0;
    },
    deviceTypes() {
      const deviceTypes = [];

      Object.keys(this.filterOptions.deviceType).forEach(deviceTypeGroupId => {
        const deviceTypeParent = this.filterOptions.deviceType[deviceTypeGroupId];
        deviceTypes.push({
          name: deviceTypeParent.name,
          value: deviceTypeParent.value,
          children: deviceTypeParent.children.map(deviceTypeChild => ({
            name: deviceTypeChild.name,
            value: deviceTypeChild.value
          }))
        });
      });

      return deviceTypes;
    }
  },
  watch: {
    query: {
      deep: true,
      handler() {
        this.handleQueryChange();
      }
    }
  },
  async created() {
    this.handleQueryChange = debounce(() => {
      this.getDevices();
    }, 350);

    //  Set initial query from URL
    if (location.search) {
      const query = qs.parse(location.search.substring(1));
      for (let key in query) {
        if (key === 'type') {
          this.query[key] = query[key].map(id => parseInt(id, 10));
        } else if (key === 'page') {
          this.query[key] = parseInt(query[key], 10);
        } else if (key === 'event') {
          this.query[key] = query[key].map(id => parseInt(id, 10));
        } else if (this.query.hasOwnProperty(key)) {
          this.query[key] = query[key];
        }
      }
    }

    this.getDevices();
  },
  methods: {
    async getDevices(shouldUpdateQuery = true) {
      this.isLoading = true;

      const { data } = await axios.get(`${this.endpoint}?${qs.stringify(this.query, { skipNulls: true })}`);

      if (shouldUpdateQuery) {
        this.updateQuery();
      }

      // Fix for pagination reset pagination when filtering
      if (data.current_page > data.last_page) {
        this.query.page = 1;
        this.updateQuery();
      }

      this.response = data;
      this.isLoading = false;
    },
    updateQuery() {
      const newQuery = qs.stringify(
        {
          ...qs.parse(location.search.substring(1)),
          ...this.query
        },
        { skipNulls: true }
      );

      history.pushState({}, '', `${location.pathname}?${newQuery}${location.hash}`);
    }
  }
};
</script>
