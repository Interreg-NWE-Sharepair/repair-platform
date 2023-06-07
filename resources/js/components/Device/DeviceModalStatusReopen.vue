<template>
  <r-modal name="device_status_reopen">
    <form @submit.prevent="submit">
      <h2 class="text-h2 text-secondary">{{ t('messages.button_repair_reopen') }}</h2>
      <r-textarea v-model="formModel.reopen_note" :label="t('messages.note_add')" optional />
      <div class="m-3">
        <p class="text text-small" v-if="!entityAdmin && !eventAdmin">{{ t('messages.repair_reopen_modal_text') }}</p>
      </div>
      <r-button type="submit" :loading="isLoading">
        {{ t('messages.form_confirm') }}
      </r-button>
      <r-link class="ml-1" @click="$modal.hide('device_status_reopen')">
        {{ t('messages.cancel') }}
      </r-link>
    </form>
  </r-modal>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';
import Form from '@/js/mixins/Form';

export default {
  mixins: [DeviceDetail, Form],
  methods: {
    submit() {
      const { route, formModel, device, $modal } = this;
      const { errors } = formModel;
      this.isLoading = true;
      formModel.post(
        route('device_log_repaired_reopen', {
          uuid: device.repair_log.uuid
        }),
        {
          onFinish: () => {
            this.isLoading = false;
            if (!errors.repair_status) {
              $modal.hide('device_status_reopen');
            }
          }
        }
      );
    }
  }
};
</script>
