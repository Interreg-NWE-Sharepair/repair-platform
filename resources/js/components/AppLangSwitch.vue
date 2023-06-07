<template>
  <v-menu>
    <template #activator="{ on }" offset-y>
      <button
        v-on="on"
        :class="{ 'ml-3': parentComponent === 'app-header' }"
        class="d-inline-flex align-center"
        type="button"
      >
        <strong>{{ $page.props.locale.toUpperCase() }}</strong>
        <v-icon>mdi-chevron-down</v-icon>
      </button>
    </template>
    <v-card>
      <v-list dense>
        <v-list-item
          v-for="localeButton in $page.props.localeButtons"
          :key="localeButton.locale"
          @click="goToUrl(localeButton.url)"
        >
          <v-list-item-title>
            {{ localeButton.locale.toUpperCase() }}
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-card>
  </v-menu>
</template>

<script>
export default {
  props: {
    parentComponent: {
      type: String,
      default: 'app-drawer'
    }
  },
  methods: {
    goToUrl(url) {
      window.location = url;
    }
  }
};
</script>
