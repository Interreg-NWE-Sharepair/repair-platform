<template>
  <layout-base>
    <r-section class="bg-gray-100">
      <repairer-register-steps :steps="0" />
    </r-section>
    <r-section class="bg-gray-100">
      <h1 class="text-h1 text-secondary">
        {{ t(`messages.step_0_title_repairer`) }}
      </h1>
      <p class="text-large mb-6">
        {{ t('messages.repairer_register_step_0_description') }}
      </p>
      <r-link :href="route('location_index')" icon-after="mdiChevronRight" inertia>
        {{ t('messages.repairer_register_step_0_cta') }}
      </r-link>
    </r-section>
    <organisation-list search>
      <template #default="organisation">
        <organisation-card
          :data="organisation.data"
          class="flex-grow-1"
          compact
          @click.native.prevent="nextStep(organisation.data.organisation.slug[$page.props.locale])"
        />
      </template>
    </organisation-list>
  </layout-base>
</template>

<script>
import RepairerRegisterSteps from '@/js/components/RepairerRegisterSteps';
import OrganisationList from '@/js/components/OrganisationList';
import OrganisationCard from '@/js/components/OrganisationCard';

export default {
  components: {
    OrganisationList,
    OrganisationCard,
    RepairerRegisterSteps
  },
  methods: {
    nextStep(organisation) {
      this.$inertia.post(this.route('repairer_register_step_0_store'), this.createFormData({ organisation }));
    }
  }
};
</script>
