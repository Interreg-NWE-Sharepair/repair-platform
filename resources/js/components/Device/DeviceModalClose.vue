<template>
  <r-modal name="device_close">
    <form @submit.prevent="submit">
      <h2 class="text-h2 text-secondary">{{ t('messages.button_repair_complete') }}</h2>
      <r-form-field :label="t('messages.form_solution_select_title')" :errors="formModel.errors.repair_status" required>
        <r-radio
          v-model="formModel.completed_status"
          v-for="completedStatus in repairStatusesClose"
          :key="completedStatus.id"
          :label="completedStatus.name[$page.props.locale]"
          :value="completedStatus.code"
          :tooltip="completedStatus.tooltip ? completedStatus.tooltip[$page.props.locale] : null"
        />
      </r-form-field>
      <!-- IF ARCHIVED -->
      <r-form-field
        v-show="formModel.completed_status === 'archive'"
        :label="t('messages.form_archive_option')"
        :errors="formModel.errors.archive_reason"
        required
      >
        <r-radio
          v-model="formModel.archive_reason"
          v-for="archiveReason in repairArchiveReasons"
          :key="archiveReason.id"
          :label="archiveReason.name[$page.props.locale]"
          :value="archiveReason.id"
        />
      </r-form-field>

      <r-textarea v-model="formModel.note" :label="t('messages.note_add')" optional />
      <div class="text-small text-gray-500 mb-3" v-html="t('messages.repair_info_submit')"></div>
      <r-button type="submit" :loading="isLoading">
        {{ t('messages.form_confirm') }}
      </r-button>
      <r-link class="ml-1" @click="$modal.hide('device_status_close')">
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
      const { route, formModel, device } = this;
      this.isLoading = true;
      formModel.post(
        route('device_close_store', {
          device: device.slug
        }),
        {
          onFinish: () => {
            this.isLoading = false;
            if (
              !formModel.errors.completed_status &&
              !formModel.errors.repair_barrier &&
              !formModel.errors.archive_reason
            ) {
              this.$modal.hide('device_close');
            }
          }
        }
      );
    }
  }
};
</script>
