<template>
  <layout-base>
    <r-section>
      <device-header v-bind="$props" />
      <device-event v-bind="$props" />

      <h2 class="text-h2 text-secondary">{{ t('messages.device_repair_select_title') }}</h2>
      <div v-html="t('messages.device_repair_select_event_body_1')" class="my-6"></div>
      <div v-html="t('messages.device_repair_select_event_body_2')" class="my-6"></div>
      <div v-html="t('messages.device_repair_select_event_body_3')" class="my-6"></div>
      <div class="my-6">
        <r-button
          color="secondary"
          icon-after="mdiChevronRight"
          @click.native="
            visitRoute('device_confirm_repair', {
              slug: device.slug
            })
          "
        >
          {{ t('messages.button_repair_confirm') }}
        </r-button>
      </div>
      <div class="my-6">
        <r-link
          href="javascript:void(0)"
          icon-before="mdiChevronLeft"
          @click="visitRoute('device_show', { slug: device.slug })"
        >
          {{ t('messages.back') }}
        </r-link>
      </div>
    </r-section>
  </layout-base>
</template>

<script>
import DeviceHeader from '@/js/components/Device/DeviceHeader';
import DeviceEvent from '../../../components/Device/DeviceEvent';

export default {
  components: {
    DeviceEvent,
    DeviceHeader
  },
  props: {
    device: {
      type: Object,
      default: () => null
    },
    event: {
      type: Object,
      default: () => null
    }
  },
  provide() {
    return this.$props;
  }
};
</script>
