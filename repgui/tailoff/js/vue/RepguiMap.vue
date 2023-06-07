<template>
  <div>
    <repair-map
      :show-filter-buttons="false"
      :filter="activeFilter"
      @filter-close="closeFilter"
      :filters="filters"
      :locale="locale"
    >
      <template #locationTitle="{ location: { id, name }, defaultClass }">
        <a :href="`${baseUrl}/${id}`" :class="defaultClass">
          <span>{{ name[locale] || name.default }}</span>
        </a>
      </template>
    </repair-map>
  </div>
</template>
<script>
// import { RepairMap } from "repair-map";
// import 'repair-map/dist/repair-map.css';
// const RepairMap = require("repair-map").RepairMap.default;
const RIcon = require('@statikbe/repair-components').RIcon;

export default {
  components: {
    RIcon
  },
  props: {
    activeFilter: {
      type: String,
      default: null
    },
    locale: {
      type: String,
      default: null
    },
    filters: {
      type: Object,
      organisation_types: {
        type: Array,
        default: ['repair_cafe']
      }
    }
  },
  created: function() {
    this.baseUrl = this.$attrs['baseurl'];
  },
  methods: {
    closeFilter() {
      // this.activeFilter = null;
      this.$emit('close-filter', null);
    }
  }
};
</script>
