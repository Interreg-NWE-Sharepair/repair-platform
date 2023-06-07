// @ts-nocheck
import Vue from "vue";
import RepairMap, { i18n } from "@statikbe/repair-map";
import RepguiMap from "./vue/RepguiMap.vue";
import RepairMapFilters from "./vue/RepairMapFilters.vue";

Vue.use(RepairMap);

window.addEventListener('load', () => {
  const app = new Vue({
    i18n,
    el: "#repguiMap",
    components: {
      RepguiMap,
      RepairMapFilters
    },
    data: () => {
      return {
        activeFilter: 'null',
        filters: {
          organisation_types: ['repair_cafe']
        }
      }
      // activeFilter: null,
      // filters: {
      //   location: null,
      //   organisation_types: ["repair_cafe"],
      //   product_categories: [],
      // }
    },
    methods: {
      toggleFilter(type) {
        if (this.activeFilter == type) {
          this.activeFilter = null;
        } else {
          this.activeFilter = type;
        }
      }
    }
  });
});
