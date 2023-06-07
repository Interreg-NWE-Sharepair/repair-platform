<template>
  <layout-base>
    <v-container>
      <div>
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <v-heading level="2">
            {{ t('messages.form_note_edit') }}
          </v-heading>
          <v-ckeditor
            v-model="form.note_content"
            :placeholder="t('messages.form_device_fix_placeholder')"
            :error-messages="getErrorMessages('fix_description')"
          />
          <v-btn @click="submit" class="mt-4" color="primary">
            {{ t('messages.form_confirm') }}
          </v-btn>
          <div class="my-4">
            <v-link :href="route('device_log_show', { uuid: log.uuid })" icon-before="mdi-chevron-left">
              {{ t('messages.back') }}
            </v-link>
          </div>
        </form>
      </div>
    </v-container>
  </layout-base>
</template>

<script>
export default {
  props: {
    repairNote: {
      type: Object,
      default: () => null
    },
    device: {
      type: Object,
      default: () => null
    },
    log: {
      type: Object,
      default: () => null
    }
  },
  data() {
    return {
      form: {
        note_content: this.repairNote.content
      }
    };
  },
  methods: {
    submit() {
      this.$inertia.post(
        this.route('device_log_note_update', {
          uuid: this.log.uuid,
          id: this.repairNote.id
        }),
        this.createFormData(this.form)
      );
    }
  }
};
</script>
