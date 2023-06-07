<template>
  <layout-base>
    <r-section class="sm:!py-12" color="light">
      <div class="flex flex-wrap items-center -mx-4">
        <div class="flex justify-center w-full px-4 flex-column align-start md:w-7/12">
          <h1 class="text-h1 text-primary">
            {{ t('messages.home_title') }}
          </h1>
          <p class="text-large">
            {{ t('messages.home_text') }}
          </p>
          <div class="flex-wrap d-flex align-center">
            <r-button inertia :href="route('device_create')" icon-after="mdiChevronRight" class="my-2 mr-3">
              {{ t('messages.home_register_device') }}
            </r-button>
            <r-link :href="route('about')" icon-after="mdiChevronRight">
              {{ t('messages.read_more_about') }}
            </r-link>
          </div>
        </div>
        <div v-if="$vuetify.breakpoint.mdAndUp" class="w-full px-4 md:w-5/12">
          <img-devices />
        </div>
      </div>
    </r-section>
    <r-section class="sm:!py-12 section--default">
      <app-steps />
      <r-button inertia :href="route('device_create')" icon-after="mdiChevronRight">
        {{ t('messages.home_register_device') }}
      </r-button>
    </r-section>
    <r-section class="sm:!py-12 section--default" v-if="!$page.props.user">
      <r-panel>
        <h2 class="text-h3 text-secondary">
          {{ t('messages.repairer_register') }}
        </h2>
        <p>{{ t('messages.home_register_text') }}</p>
        <div class="mb-6 wysiwyg">
          <ol>
            <li>{{ t('messages.home_register_list_0') }}</li>
            <li>{{ t('messages.home_register_list_1') }}</li>
            <li>{{ t('messages.home_register_list_2') }}</li>
          </ol>
        </div>
        <r-button inertia :href="route('repairer_register_index')" color="secondary" icon-after="mdiChevronRight">
          {{ t('messages.home_register_cta') }}
        </r-button>
      </r-panel>
    </r-section>
    <r-section class="sm:!py-12 section--default">
      <h2 id="location-list" class="text-h2 text-primary">
        {{ t('messages.locations_title_home') }}
      </h2>
      <p>
        {{ t('messages.locations_intro_home') }}
      </p>
      <div class="flex flex-wrap -mx-4 -mt-4" v-if="locations.length">
        <div v-for="location in locations" :key="location.id" class="w-full px-4 mt-8 sm:w-1/2 md:w-1/3">
          <organisation-card :data="location" class="h-full" />
        </div>
      </div>
      <div class="mt-4">
        <r-link :href="route('location_index')" icon-after="mdiChevronRight" class="my-2">
          {{ t('messages.locations_index') }}
        </r-link>
      </div>
    </r-section>
    <r-section class="sm:!py-12 section--default">
      <r-panel>
        <div class="max-w-2xl">
          <h2 class="text-h2 text-secondary">{{ t('messages.create_organisation_title') }}</h2>
          <p>
            {{ t('messages.create_organisation_body') }}
          </p>
          <r-link :href="route('location_create')" inertia icon-after="mdiChevronRight" class="mt-3">
            {{ t('messages.location_create') }}
          </r-link>
        </div>
      </r-panel>
    </r-section>
    <!-- <v-section large>
      <v-panel>
        <v-row>
          <v-col cols="12" md="6" lg="7">
            <v-heading level="2" color="secondary">
              {{ t('messages.create_organisation_title') }}
            </v-heading>
            <p>
              {{ t('messages.create_organisation_body') }}
            </p>
            <v-link :href="route('location_create')" icon-after="mdi-chevron-right" class="my-2">
              {{ t('messages.location_create') }}
            </v-link>
          </v-col>
          <v-col cols="12" md="6" lg="5" />
        </v-row>
      </v-panel>
    </v-section> -->
    <r-section color="light" class="sm:!py-12">
      <stats-general-embed :lang="$page.props.locale"></stats-general-embed>
      <!--      <div class="flex flex-wrap -mx-4 -mt-8">
        <div class="w-full px-4 mt-8 sm:w-1/2">
          <div class="flex items-center justify-center">
            <img-device class="mr-8 stats__icon" />
            <div>
              <div class="primary&#45;&#45;text font-weight-black stats__text">
                {{ stats.devices }}
              </div>
              <div class="font-weight-bold">
                {{ t('messages.stats_fixed_devices_text') }}
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-4 mt-8 sm:w-1/2">
          <div class="flex items-center justify-center">
            <img-repairer class="mr-8 stats__icon" />
            <div>
              <div class="secondary&#45;&#45;text font-weight-black stats__text">
                {{ stats.repairers }}
              </div>
              <div class="font-weight-bold">
                {{ t('messages.stats_active_repairers_text') }}
              </div>
            </div>
          </div>
        </div>
      </div>-->
    </r-section>
  </layout-base>
</template>

<script>
import AppSteps from '@/js/components/AppSteps';
import OrganisationCard from '@/js/components/OrganisationCard';
import StatsGeneralEmbed from '@/js/components/StatsGeneralEmbed';
import ImgDevices from '@/img/svg/devices.svg';

export default {
  components: {
    AppSteps,
    OrganisationCard,
    StatsGeneralEmbed,
    ImgDevices
  },
  props: {
    stats: {
      type: Object,
      default: () => ({
        repairers: 0,
        devices: 0
      })
    },
    locations: {
      type: Array,
      default: () => []
    }
  }
};
</script>

<style lang="scss" scoped>
.stats {
  .stats__icon {
    max-height: 140px;
    width: auto;
  }
  .stats__text {
    font-size: 60px;
    line-height: 1;
  }
}
.vuetify-link {
  font-weight: 600 !important;

  &:hover {
    text-decoration: none;
  }
}
</style>
