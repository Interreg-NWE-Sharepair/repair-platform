<template>
  <layout-base>
    <r-section class="bg-gray-100">
      <device-register-steps :steps="1" />
    </r-section>
    <r-section class="bg-gray-100">
      <h1 class="text-h1 text-primary">
        {{ t('messages.step_0_title_device') }}
      </h1>
      <p class="text-large mb-6">
        {{ t('messages.create_device_step_0_description') }}
      </p>
      <r-link :href="route('location_index')" icon-after="mdiChevronRight" inertia>
        {{ t('messages.repairer_register_step_0_cta') }}
      </r-link>
    </r-section>
    <organisation-list search>
      <template #default="location">
        <organisation-card
          :data="location.data"
          class="flex-grow-1"
          compact
          @click.native.prevent="nextStep(location.data.organisation.slug[$page.props.locale])"
        />
      </template>
    </organisation-list>
    <r-section>
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
  </layout-base>
</template>

<script>
import DeviceRegisterSteps from '@/js/components/DeviceRegisterSteps';
import OrganisationList from '@/js/components/OrganisationList';
import OrganisationCard from '@/js/components/OrganisationCard';

export default {
  components: {
    DeviceRegisterSteps,
    OrganisationList,
    OrganisationCard
  },
  methods: {
    nextStep(location) {
      this.$inertia.post(this.route('device_step_0_store'), this.createFormData({ location }));
    }
  }
};
</script>
