<template>
  <div class="p-3  max-w-3xl">
    <div v-if="device.latest_status === 'completed' && device.device_notes.length">
      <div class="mr-3 mb-3 mt-3">
        <h3 class="text-h3 text-secondary">{{ t('messages.notes') }}</h3>
      </div>
      <div v-for="(note, key) in device.device_notes" :key="key" class="mb-3">
        <div>
          <small>{{ note['formatted_timestamp'] }} - {{ note['repairer'] }}</small>
          <div v-html="note['content']" class="prose"></div>
        </div>
      </div>
    </div>
    <div v-else-if="(entityAdmin || eventAdmin) && device.latest_status !== 'completed'" class="mt-6">
      <div class="mr-3 mb-3 mt-3">
        <h3 class="text-h3 text-secondary">{{ t('messages.notes') }}</h3>
      </div>
      <form v-if="formModel" @submit.prevent="submit">
        <r-form-collection v-model="formModel.device_notes" :errors="formModel.errors.device_notes">
          <template #default="{ item, updateItem }">
            <form-collection-item-note v-bind="item" @update="updateItem" />
          </template>
        </r-form-collection>
        <r-button type="submit" color="secondary" icon-before="mdiContentSave" ghost>
          {{ t('messages.save_notes') }}
        </r-button>
      </form>
    </div>
  </div>
</template>
<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';
import Form from '@/js/mixins/Form';
import FormCollectionItemNote from '@/js/components/Device/FormCollectionItemNote';

export default {
  components: { FormCollectionItemNote },
  mixins: [DeviceDetail, Form],
  methods: {
    submit() {
      const { formModel, route, device } = this;
      formModel.post(
        route('device_note_add', {
          slug: device.slug
        })
      );
    }
  }
};
</script>
