<template>
  <v-app>
    <r-app class="v-application--wrap">
      <inertia-head>
        <title v-if="this.$page.props.title">{{ this.$page.props.title | stripTags }} - Repair Connects</title>
        <title v-else>Repair Connects</title>
      </inertia-head>

      <app-header @drawer:open="isDrawerOpen = true" fixed flat />
      <app-drawer v-model="isDrawerOpen" :permanent="true" @drawer:close="isDrawerOpen = false" />
      <v-main>
        <div class="flex flex-col items-stretch min-h-full">
          <div class="mb-6">
            <app-alerts />
            <slot name="hero"></slot>
            <slot name="default"></slot>
          </div>
          <app-footer
            @click:cookiesettings="
              // $cookies.remove(cookieName);
              hasCookiesEnabled = false
            "
            class="mt-auto"
          />
        </div>
      </v-main>
      <r-cookies :policy-url="route('cookies')" />
    </r-app>
  </v-app>
</template>

<script>
import AppAlerts from '@/js/components/AppAlerts';
import AppDrawer from '@/js/components/AppDrawer';
import AppFooter from '@/js/components/AppFooter';
import AppHeader from '@/js/components/AppHeader';

export default {
  components: {
    AppAlerts,
    AppDrawer,
    AppFooter,
    AppHeader
  },
  props: {
    cookieName: {
      type: String,
      default: '__cookie_consent'
    }
  },
  data() {
    return {
      isDrawerOpen: false,
      hasCookiesEnabled: true
    };
  },
  // },
  mounted() {
    this.hasCookiesEnabled = this.$cookies.get(this.cookieName);
    const [url, paramString] = document.location.href.split('?');
    if (url && paramString) {
      const searchParams = new URLSearchParams(paramString);
      const anchor = searchParams.get('anchor');
      if (anchor) {
        const anchorElement = document.getElementById(anchor);
        if (anchorElement) {
          anchorElement.scrollIntoView();
        }
      }
    }
  }
};
</script>
