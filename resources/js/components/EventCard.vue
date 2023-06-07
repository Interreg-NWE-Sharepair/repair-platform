<template>
  <r-panel class="mb-6">
    <div v-bind:class="{ 'flex items-baseline': !locationDetail }">
      <div v-if="$page.props.user">
        <a :href="route('event_show', { slug: data.slug })" class="no-underline ">
          <h3 class="mr-auto text-h3 text-secondary">{{ data.locale_name }}</h3>
        </a>
      </div>
      <h3 v-else class="mr-auto text-h3 text-secondary">{{ data.locale_name }}</h3>
      <r-checkbox
        v-if="data.is_future && $page.props.user && !locationDetail"
        v-model="isAttending"
        :label="
          t(isAttending ? 'messages.event_repairer_label_switch_true' : 'messages.event_repairer_label_switch_off')
        "
        toggle
        class="flex-shrink-0 ml-3"
        :disabled="isLoading || (data.is_future && data.is_organizer && !$page.props.repairer)"
      />
    </div>
    <div v-if="data.is_future && $page.props.user && locationDetail" class="my-3">
      <r-checkbox
        v-model="isAttending"
        :label="
          t(isAttending ? 'messages.event_repairer_label_switch_true' : 'messages.event_repairer_label_switch_off')
        "
        toggle
        class="flex-shrink-0 ml-3 text-h2"
        :disabled="isLoading || (data.is_future && data.is_organizer && !$page.props.repairer)"
      />
    </div>
    <div class="mb-3 text-small">
      <div v-if="data.is_online || data.address" class="flex items-center">
        <r-icon name="mdiMapMarker" class="mr-1 text-secondary" />
        <span v-if="data.is_online">{{ t('messages.online') }}</span>
        <span v-else>{{ data.address }}</span>
      </div>
      <div class="flex items-center">
        <r-icon name="mdiCalendar" class="mr-1 text-secondary" />
        <span>{{ t('messages.event_when', { at: data.starts_at }) }} {{ data.ends_at }}</span>
      </div>
      <div v-if="data.organizer && !$page.props.showOrganizer" class="flex items-center">
        <r-icon name="mdiAccount" class="mr-1 text-secondary" />
        <span>{{ data.organizer }}</span>
      </div>
      <div
        v-if="$page.props.user"
        class="inline-flex items-center"
        v-tooltip="{
          content: t(isAttending ? 'messages.event_repairer_switch_true' : 'messages.event_repairer_switch_false', {
            repairers: data.attending_repairers
          }),
          trigger: 'click hover'
        }"
      >
        <r-icon name="mdiInformationOutline" class="flex-shrink-0 mr-2 text-secondary" />
        <span>{{ data.people.length }} {{ t('messages.event_repairer_attendees') }}</span>
      </div>
      <div v-if="data.max_devices">
        <span class="font-bold">
          {{ t('messages.registered_devices', { amount: data.registered_devices, total: data.max_devices }) }}
        </span>
      </div>
    </div>
    <div v-if="data.locale_description" v-html="data.locale_description" class="mb-4" />
    <div v-if="locationDetail" class="mt-auto">
      <r-button
        v-if="$page.props.user"
        color="secondary"
        class="ml-auto"
        icon-after="mdiChevronRight"
        @click.native="
          visitRoute('event_show', {
            slug: data.slug
          })
        "
      >
        {{ t('messages.event_dashboard') }}
      </r-button>
    </div>
  </r-panel>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true
    },
    locationDetail: {
      type: Boolean,
      default: () => false
    }
  },
  data: () => ({
    isAttending: false,
    isLoading: false
  }),
  created() {
    this.isAttending = this.data.is_attending;

    this.$watch('isAttending', async function() {
      this.isLoading = true;
      await axios.post(`/api/repairer/event/attend/${this.data.id}`);
      this.isLoading = false;
    });
  }
};
</script>
