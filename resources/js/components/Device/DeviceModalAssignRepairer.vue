<template>
  <r-modal name="device_assign_repairer">
    <form @submit.prevent="submit">
      <h2 class="text-h2 text-secondary">{{ t('messages.button_assign_repairer') }}</h2>
      <r-select
        v-model="formModel.repairer_select"
        :options="repairers"
        :label="t('messages.form_repairer_select')"
        :placeholder="t('messages.form_repairer_select_placeholder')"
        :errors="formModel.errors.repairer_select"
        :max-height="100"
        :searchable="true"
        track-by="value"
        label-by="name"
        class="!mb-[100px]"
        required
      />
      <div class="pt-6">
        <r-button type="submit" :loading="isLoading">
          {{ t('messages.form_confirm') }}
        </r-button>
        <r-link class="ml-1" @click="$modal.hide('device_assign_repairer')">
          {{ t('messages.cancel') }}
        </r-link>
      </div>
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
        route('device_assign_repairer', {
          slug: device.slug
        }),
        {
          onFinish: () => {
            this.isLoading = false;
            this.$modal.hide('device_assign_repairer');
          }
        }
      );
    }
  }
};
</script>
