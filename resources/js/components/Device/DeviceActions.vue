<template>
  <div class="flex flex-wrap -m-2">
    <!-- OPEN/REOPENED -->
    <r-button
      v-if="canEdit && ['open', 'reopened'].includes(device.latest_status)"
      color="secondary"
      icon-after="mdiChevronRight"
      class="m-2"
      @click.native="
        visitRoute('device_select_repair', {
          slug: device.slug
        })
      "
    >
      {{ t('messages.button_repair_device') }}
    </r-button>

    <!-- PROGRESS -->
    <template
      v-else-if="
        (device.latest_status === 'in_repair' && (canEdit || eventAdmin || entityAdmin)) || entityAdmin || eventAdmin
      "
    >
      <r-button
        v-if="device.latest_status === 'in_repair'"
        type="button"
        icon-before="mdiCheck"
        class="m-2"
        @click.native="$modal.show('device_status_close')"
      >
        {{ t('messages.button_repair_complete') }}
      </r-button>
      <r-button
        v-if="
          (device.latest_status === 'completed' && device.completed_repair_status_id === 4) ||
            device.latest_status === 'in_repair'
        "
        type="button"
        color="secondary"
        icon-before="mdiUndo"
        class="m-2"
        @click.native="$modal.show('device_status_reopen')"
      >
        {{ t('messages.button_repair_reopen') }}
      </r-button>
    </template>
    <!-- If Device has an event & is (open, reopened) && event admin is logged in
          OR Device status is (in_repair, reopened) && Entity admin is logged in
     -->
    <template
      v-if="
        (['open', 'reopened'].includes(device.latest_status) && eventAdmin && hasEvent) ||
          (['open', 'reopened'].includes(device.latest_status) && entityAdmin)
      "
    >
      <r-button type="button" icon-before="mdiWrench" class="m-2" @click.native="$modal.show('device_assign_repairer')">
        {{ t('messages.button_assign_repairer') }}
      </r-button>
    </template>
    <!-- IF the event admin is logged in AND there are future events AND there is no event linked -->
    <template
      v-if="
        futureEvents.length &&
          !device.event &&
          (eventAdmin || canEditEvent || entityAdmin) &&
          ['open', 'reopened', 'in_repair', 'completed'].includes(device.latest_status)
      "
    >
      <r-button type="button" icon-before="mdiCalendar" class="m-2" @click.native="$modal.show('device_assign_event')">
        {{ t('messages.button_assign_event') }}
      </r-button>
    </template>
    <template v-if="['open', 'reopened'].includes(device.latest_status) && (eventAdmin || entityAdmin)">
      <r-button type="button" class="m-2" @click.native="$modal.show('device_close')">
        {{ t('messages.button_close_device') }}
      </r-button>
    </template>
    <slot />
  </div>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';

export default {
  mixins: [DeviceDetail]
};
</script>
