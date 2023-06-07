<template>
  <r-modal name="device_contact_edit">
    <form @submit.prevent="submit">
      <h3 class="text-h3 text-secondary">{{ t('messages.device_contact_details') }}</h3>
      <r-input
        v-model="formModel.first_name"
        :label="t('messages.form_first_name')"
        :placeholder="t('messages.form_first_name_placeholder')"
        :errors="formModel.errors.first_name"
        required
      />
      <r-input
        v-model="formModel.last_name"
        :label="t('messages.form_last_name')"
        :placeholder="t('messages.form_last_name_placeholder')"
        :errors="formModel.errors.last_name"
        required
      />

      <r-input
        v-model="formModel.email"
        :label="t('messages.form_email')"
        :placeholder="t('messages.form_email_placeholder')"
        :errors="formModel.errors.email"
        :rules="[v => !!v || 'E-mail is required', v => /.+@.+\..+/.test(v) || 'E-mail must be valid']"
        required
      />
      <r-input
        v-model="formModel.telephone"
        :label="t('messages.form_telephone')"
        :placeholder="t('messages.form_telephone_placeholder')"
        :errors="formModel.errors.telephone"
      />
      <r-button type="submit" :loading="isLoading">
        {{ t('messages.form_confirm') }}
      </r-button>
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
        route('device_contact_edit', {
          uuid: device.slug
        }),
        {
          onFinish: () => {
            this.isLoading = false;
            if (!errors.first_name && !errors.last_name && !errors.email && !errors.telephone) {
              $modal.hide('device_contact_edit');
            }
          }
        }
      );
    }
  }
};
</script>
