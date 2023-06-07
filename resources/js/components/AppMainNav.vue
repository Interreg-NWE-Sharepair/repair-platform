<template>
  <!-- nav when not logged in -->
  <div>
    <div class="nav nav--main">
      <ul
        :class="`${color == 'white' ? `${color}--text` : 'main--text'}`"
        class="flex flex-wrap items-center nav__menu"
      >
        <li v-if="$vuetify.breakpoint.mdAndUp" class="px-4 nav__item">
          <inertia-link
            :class="
              `
              ${color == 'white' ? `text-${color} hover:${color}` : 'text-main hover:text-primary'}`
            "
            :href="route('about')"
            class="nav__link hover:font-bold"
            :data-text="t('messages.route_about')"
          >
            {{ t('messages.route_about') }}
          </inertia-link>
        </li>
        <li v-if="$vuetify.breakpoint.mdAndUp" class="px-4 nav__item">
          <inertia-link
            :class="`${color == 'white' ? `text-${color} hover:${color}` : 'text-main hover:text-primary'}`"
            :href="route('location_index')"
            class="nav__link hover:font-bold"
            :data-text="t('messages.locations')"
          >
            {{ t('messages.locations') }}
          </inertia-link>
        </li>
        <li v-if="$vuetify.breakpoint.mdAndUp" class="px-4 nav__item">
          <inertia-link
            :class="`${color == 'white' ? `text-${color} hover:${color}` : 'text-main hover:text-primary'}`"
            :href="route('contact_index')"
            class="nav__link hover:font-bold"
            :data-text="t('messages.route_contact_index')"
          >
            {{ t('messages.route_contact_index') }}
          </inertia-link>
        </li>
        <li v-if="!$page.props.user" class="px-4 nav__item">
          <inertia-link
            :class="`${color == 'white' ? `text-${color} hover:${color}` : 'text-main hover:text-primary'}`"
            :href="route('repairer_login_index')"
            class="no-underline hover:font-bold"
          >
            <v-btn color="secondary">
              <v-icon small left>
                mdi-account-outline
              </v-icon>
              <span v-if="$vuetify.breakpoint.smAndUp">
                {{ t('messages.route_repairer_login_index') }}
              </span>
            </v-btn>
          </inertia-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    color: {
      type: String,
      default: 'main'
    }
  }
};
</script>

<style lang="scss" scoped>
.nav {
  .nav__menu {
    margin: 0;
    padding: 0;
    list-style-type: none;
    display: flex;
  }

  .nav__link {
    // color: white !important;
    text-decoration: none;
  }

  .nav__link.is-active {
    font-weight: 700;
  }
}
</style>
