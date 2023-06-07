<template>
  <footer>
    <div :class="{ 'pa-4': $page.props.user }" class="primary">
      <div :class="{ container: !$page.props.user }" class="py-3 d-flex align-center">
        <inertia-link :href="route('home_index')" class="text-decoration-none">
          <v-toolbar-title>
            <div class="text-right font-weight-black text-h4 text-sm-h3 text-md-h2 text-uppercase logo">
              <div class="white--text">
                Repair
              </div>
              <div class="mr-6 black--text">
                Connects
              </div>
            </div>
          </v-toolbar-title>
        </inertia-link>
        <app-main-nav color="white" class="ml-auto" />
      </div>
    </div>

    <r-section>
      <div class="mb-6 text-center text-lrg">{{ t('messages.footer_partners') }}</div>
      <div class="flex flex-wrap items-center justify-center -mt-4">
        <div class="w-1/3 px-4 mt-4 sm:w-1/4 md:w-1/6">
          <div>
            <a
              href="https://www.kbs-frb.be/"
              target="_blank"
              rel="noopener noreferrer"
              alt="king baudouin foundation"
              class="block text-center"
            >
              <img
                src="/img/logo_king_baudouin_foundation.jpg"
                alt="Interreg"
                width="162"
                height="90"
                class="w-auto"
                loading="lazy"
              />
            </a>
          </div>
        </div>
        <div class="w-1/3 px-4 mt-4 sm:w-1/4 md:w-1/6">
          <div>
            <a
              href="https://repairtogether.be/"
              target="_blank"
              rel="noopener noreferrer"
              alt="king baudouin foundation"
              class="block text-center"
            >
              <img
                src="/img/logo_repair_together.png"
                alt="repair together"
                width="162"
                height="162"
                class="w-auto"
                loading="lazy"
              />
            </a>
          </div>
        </div>
        <div class="w-1/3 px-4 mt-4 sm:w-1/4 md:w-1/6">
          <div>
            <a href="https://www.wallonie.be/fr/" target="_blank" rel="noopener noreferrer" class="block text-center">
              <img
                src="/img/logo_wallonie.png"
                alt="Wallonie"
                width="80"
                height="80"
                class="inline-block"
                loading="lazy"
              />
            </a>
          </div>
        </div>
        <div class="w-1/3 px-4 mt-4 sm:w-1/4 md:w-1/6">
          <div>
            <a
              href="https://www.nweurope.eu/projects/project-search/sharepair-digital-support-infrastructure-for-citizens-in-the-repair-economy/"
              target="_blank"
              rel="noopener noreferrer"
              class="block text-center"
            >
              <img src="/img/logo_interreg.png" alt="Interreg" width="162" height="87" class="w-auto" loading="lazy" />
            </a>
          </div>
        </div>
        <div class="w-1/3 px-4 mt-4 sm:w-1/4 md:w-1/6">
          <div>
            <a href="https://repairshare.be/" target="_blank" rel="noopener noreferrer" class="block text-center">
              <img
                src="/img/logo_repair_and_share.png"
                alt="repair and share"
                width="162"
                height="75"
                class="inline-block w-auto"
                loading="lazy"
              />
            </a>
          </div>
        </div>
        <div class="w-1/3 px-4 mt-4 sm:w-1/4 md:w-1/6">
          <div>
            <a
              href="https://www.smarthubvlaamsbrabant.be/"
              target="_blank"
              rel="noopener noreferrer"
              class="text-center"
            >
              <img
                src="/img/logo_shvb.png"
                alt="Smart Hub Vlaams-Brabant"
                width="162"
                height="191"
                class="inline-block"
                loading="lazy"
              />
            </a>
          </div>
        </div>
      </div>
    </r-section>
    <r-section>
      <div class="items-center justify-center md:flex">
        <div
          class="
            text-primary
            md:bg-primary md:text-primary-contrast
            font-semibold
            md:max-w-[200px]
            py-3
            px-9
            text-center
            md:rounded-l-full md:text-right
            text-sml
            leading-tight
          "
        >
          {{ t('messages.footer_tagline') }}
        </div>
        <div class="md:ml-6">
          <div class="mb-2 text-center md:text-left">
            &copy; Sharepair, {{ new Date().getFullYear() }} - Made with <span class="text-primary">&hearts;</span> by
            <a href="https://www.statik.be/" target="_blank" rel="noopener noreferrer">Statik.be</a>
          </div>
          <ul
            class="space-y-1 text-center divide-gray-600 md:text-left md:flex md:flex-wrap md:divide-x-1 md:space-y-0"
          >
            <li class="xs:pr-2">
              <inertia-link :href="route('privacy')">
                {{ t('messages.route_privacy') }}
              </inertia-link>
            </li>
            <li class="xs:px-2">
              <inertia-link :href="route('cookies')" class="">
                {{ t('messages.route_cookies') }}
              </inertia-link>
            </li>
            <li class="xs:px-2">
              <inertia-link :href="route('terms_conditions')" class="">
                {{ t('messages.route_terms_conditions') }}
              </inertia-link>
            </li>
            <li class="xs:px-2">
              <a
                @click="$emit('click:cookiesettings')"
                @keydown.enter="$emit('click:cookiesettings')"
                @keydown.space="$emit('click:cookiesettings')"
                href="javascript:void(0)"
                class=""
              >
                {{ t('cookie.settings_title') }}
              </a>
            </li>
          </ul>
        </div>
      </div>
    </r-section>
  </footer>
</template>

<script>
import AppMainNav from '@/js/components/AppMainNav';

export default {
  components: {
    AppMainNav
  }
};
</script>

<style lang="scss" scoped>
.logo {
  line-height: 1;
}

.partner {
  height: 45px;

  @media (min-width: 720px) {
    height: 60px;
  }
}
</style>
