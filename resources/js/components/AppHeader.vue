<template>
  <v-app-bar
    :height="$vuetify.breakpoint.mdAndUp ? '120px' : '80px'"
    app
    color="white"
    flat
    style="left: 0px; border-bottom: 8px solid #f5f5f5 !important;"
  >
    <div class="flex-grow-1">
      <div
        :class="{
          container: !$page.props.user && $vuetify.breakpoint.lgAndUp
        }"
        class="d-flex align-center"
      >
        <v-app-bar-nav-icon v-show="$vuetify.breakpoint.smAndDown" @click="$emit('drawer:open')">
          <v-icon>mdi-menu</v-icon>
        </v-app-bar-nav-icon>
        <inertia-link
          :href="$page.props.user ? route('repairer_dashboard') : route('home_index')"
          class="text-decoration-none"
        >
          <v-toolbar-title :class="{ 'ml-3': $vuetify.breakpoint.mdAndDown }">
            <div class="leading-5 text-right lg:leading-8 font-weight-black text-h3 text-md-h1 text-uppercase logo">
              <div class="primary--text">
                Repair
              </div>
              <div class="mr-6 secondary--text">
                Connects
              </div>
            </div>
          </v-toolbar-title>
        </inertia-link>
        <app-main-nav v-if="!$page.props.user" color="primary" class="ml-auto" />
        <app-lang-switch v-if="!$page.props.user" class="ml-3" parent-component="app-header" />
      </div>
    </div>
  </v-app-bar>
</template>
<script>
import AppLangSwitch from '@/js/components/AppLangSwitch';
import AppMainNav from '@/js/components/AppMainNav';

export default {
  components: {
    AppMainNav,
    AppLangSwitch
  }
};
</script>

<style lang="scss" scoped>
.logo {
  line-height: 1;
}
</style>
