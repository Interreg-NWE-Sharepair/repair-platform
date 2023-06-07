<template>
  <layout-base>
    <r-section class="bg-gray-100">
      <div class="md:w-8/12">
        <h1 class="text-h1 text-primary">
          {{ t('messages.organisation_overview_title') }}
        </h1>
        <p class="mb-6 text-large">
          {{ t('messages.organisation_overview_body') }}
        </p>
        <div class="flex flex-wrap -m-2">
          <r-button inertia :href="route('location_create')" icon-after="mdiChevronRight" class="m-2">
            {{ t('messages.organisation_register') }}
          </r-button>
          <r-link inertia :href="route('about')" icon-after="mdiChevronRight" class="m-2">
            {{ t('messages.route_about_cta') }}
          </r-link>
        </div>
      </div>
    </r-section>
    <organisation-list>
      <template #default="location">
        <organisation-card :data="location.data" />
      </template>
    </organisation-list>
    <r-section class="bg-gray-100">
      <div class="md:w-8/12">
        <h2 class="text-h2 text-secondary">
          {{ t('messages.create_organisation_title') }}
        </h2>
        <p class="mb-6">
          {{ t('messages.create_organisation_body') }}
        </p>
        <r-link inertia :href="route('location_create')" icon-after="mdiChevronRight">
          {{ t('messages.location_create') }}
        </r-link>
      </div>
    </r-section>
  </layout-base>
</template>

<script>
import OrganisationCard from '@/js/components/OrganisationCard';
import OrganisationList from '@/js/components/OrganisationList';

export default {
  components: {
    OrganisationCard,
    OrganisationList
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
</style>
