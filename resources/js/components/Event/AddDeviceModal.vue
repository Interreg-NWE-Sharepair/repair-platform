<template>
  <r-modal name="event_assign_device" height="350">
    <form @submit.prevent="submit">
      <h2 class="text-h2 text-secondary">{{ t('messages.button_assign_device') }}</h2>

      <r-select :label="t('messages.search_connect_number')" v-model="model" :options="options" :loading="isLoading"
      :placeholder="t('messages.search_device_number')" label-by="label" track-by="value" searchable
      @update:modelValue="$emit('update.modelValue', $event)" @search-change="search" required: true/>
      <div class="pt-6">
        <r-button type="submit" :loading="isLoading">
          {{ t('messages.form_add') }}
        </r-button>
      </div>
    </form>
  </r-modal>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';
import Form from '@/js/mixins/Form';
import axios from 'axios';

export default {
  mixins: [DeviceDetail, Form],
  props: {
    endpoint: {
      type: String,
      required: true
    },
    event: {
      type: Object,
      default: () => null
    }
  },
  data: () => ({
    options: [],
    isLoading: false,
    model: []
  }),
  methods: {
    search(val) {
      if (!val) {
        return;
      }

      this.isLoading = true;
      this.fetchOptionsDebounced(val);
    },
    clear() {
      this.options = [];
    },
    fetchOptionsDebounced(val) {
      clearTimeout(this._searchTimerId);
      this._searchTimerId = setTimeout(() => {
        this.fetchOptions(val);
      }, 500);
    },
    async fetchOptions(val) {
      const { data } = await axios.get(`${this.endpoint}?search=${val}`);
      this.options = data.map(entry => {
        const label = entry.name;
        const value = entry.value;
        return { value, label };
      });

      this.isLoading = false;
    },
    submit() {
      this.$inertia.post(
        this.route('device_link_event_follow_up', { event: this.event.slug }),
        this.createFormData(this.$data)
      );
      this.$data.model = [];
      this.$data.options = [];
      this.$modal.hide('event_assign_device');
    }
  }
};
</script>
