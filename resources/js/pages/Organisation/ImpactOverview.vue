<template>
  <layout-dashboard :organisation="organisation" active-tab="impact_overview">
    <r-section>
      <h2 class="text-h2 text-secondary">{{ title }}</h2>
      <r-section class="bg-gray-100">
        <stats-organisation-embed
          :organisation="organisation.uuid"
          :lang="$page.props.locale"
        ></stats-organisation-embed>
      </r-section>
    </r-section>
  </layout-dashboard>
</template>

<script>
import LayoutDashboard from '@/js/layouts/LayoutDashboard';
import StatsOrganisationEmbed from '../../components/StatsOrganisationEmbed';

export default {
  components: { StatsOrganisationEmbed, LayoutDashboard },
  props: {
    organisation: {
      type: Object,
      default: () => null
    },
    title: {
      type: String,
      default: null
    }
  }
};
</script>
