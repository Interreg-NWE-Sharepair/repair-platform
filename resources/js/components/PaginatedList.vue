<template>
  <div>
    <v-row v-if="items.length">
      <v-col v-for="item in pageItems" :key="item.id" v-bind="grid">
        <slot v-bind="item"></slot>
      </v-col>
    </v-row>

    <div v-else>
      {{ t(emptyLabel) }}
    </div>

    <!--    <v-pagination v-if="items.length > itemsPerPage" v-model="page" :length="paginationLength" />-->
    <!-- <v-pagination
      v-if="pagination && pagination.total > pagination.per_page"
      v-model="page"
      :length="pagination.last_page"
    /> -->
    <r-pagination v-if="pagination" v-model="page" :pages="pagination.pages" />
  </div>
</template>

<script>
import qs from 'qs';

export default {
  props: {
    items: {
      type: Array,
      default: () => []
    },
    itemsPerPage: {
      type: Number,
      default: () => 12
    },
    emptyLabel: {
      type: String,
      default: () => 'messages.paginated_list_empty'
    },
    grid: {
      type: Object,
      default: () => ({
        cols: 12
      })
    },
    pagination: {
      type: Array,
      default: () => null
    }
  },
  data: () => ({
    page: 1
  }),
  computed: {
    pageItems() {
      const { page, itemsPerPage, items } = this;

      const startIndex = (page - 1) * itemsPerPage;
      const endIndex = startIndex + itemsPerPage;

      return items.slice(startIndex, endIndex);
    },
    paginationLength() {
      return Math.ceil(this.items.length / this.itemsPerPage);
    }
  },
  created() {
    this.detectPage();
  },
  methods: {
    detectPage() {
      let page = 1;

      const query = qs.parse(window.location.search);

      if (query.page) {
        page = parseInt(query.page, 10);
      }

      this.page = page;
    }
  }
};
</script>
