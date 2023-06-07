<template>
  <layout-base>
    <v-container>
      <div class="text-center">
        <v-heading>
          <span v-if="draft">
            {{
              t('messages.title_device_repair_edit', {
                brand: device.brand_name,
                model: device.model_name !== null ? device.model_name : ''
              })
            }}
          </span>
          <span v-else class="secondary--text text-h4 font-weight-black my-4">
            {{
              t('messages.title_device_repair', {
                brand: device.brand_name,
                model: device.model_name !== null ? device.model_name : ''
              })
            }}
          </span>
        </v-heading>
        <p
          v-text="
            t('messages.registered_by') +
              ' ' +
              device.first_name +
              ' ' +
              device.last_name.charAt(0) +
              ' ' +
              t('messages.at') +
              ' ' +
              device.created_timestamp
          "
        ></p>
        <div>
          {{ t('messages.repair_log_device_text') }}
        </div>
      </div>
      <div class="my-4">
        <v-heading level="3">
          {{ t('messages.repair_log_title') }}
        </v-heading>
        <p>
          {{ t('messages.repair_log_description') }}
        </p>
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <v-text-field
            v-model="form.brand_name"
            :label="t('messages.form_brand_name')"
            :placeholder="t('messages.form_brand_name_placeholder')"
            :error-messages="getErrorMessages('brand_name')"
            outlined
          />
          <v-text-field
            v-model="form.model_name"
            :label="t('messages.form_model_name')"
            :placeholder="t('messages.form_model_name_placeholder')"
            :error-messages="getErrorMessages('model_name')"
            outlined
          />
          <v-autocomplete
            v-model="form.device_type_id"
            v-bind:value="device_types.value"
            :items="device_types"
            :label="t('messages.form_device_type')"
            :placeholder="t('messages.form_device_type_placeholder')"
            :error-messages="getErrorMessages('device_type_id')"
            outlined
          />
          <v-text-field
            v-model="form.manufacture_year"
            :label="t('messages.form_manufacture_year')"
            :placeholder="t('messages.form_manufacture_year_placeholder')"
            :error-messages="getErrorMessages('manufacture_year')"
            outlined
          />
          <v-heading level="2">
            {{ t('messages.repair_log_details') }}
          </v-heading>
          <v-heading level="3">
            {{ t('messages.repair_diagnosis') }}
          </v-heading>
          <v-ckeditor
            v-model="form.diagnosis"
            :label="t('messages.form_diagnosis')"
            :error-messages="getErrorMessages('diagnosis')"
          />
          <v-heading level="3">
            {{ t('messages.repair_root_cause') }}
          </v-heading>
          <v-ckeditor
            v-model="form.root_cause"
            :label="t('messages.form_root_cause')"
            :error-messages="getErrorMessages('root_cause')"
          />
          <v-heading level="3">
            {{ t('messages.repair_title') }}
          </v-heading>
          <v-image-gallery :images="images_repair" />
          <r-form-image
            v-model="form.images_repair"
            :label="t('messages.form_images_repair')"
            :placeholder="t('messages.form_images_repaired_placeholder')"
            :errors="getErrorMessages('images_repair')"
            multiple
          />
          <p>
            {{ t('messages.form_images_repair_help') }}
          </p>
          <v-radio-group v-if="!draft" v-model="form.repair_status_id" mandatory>
            <template #label>
              <div>
                {{ t('messages.form_solution_select_title') }}
              </div>
            </template>
            <v-radio
              v-for="status in repair_statuses"
              :key="status['value']"
              :label="status['text']"
              :value="status['value']"
              :error-messages="getErrorMessages('repair_status_id')"
            />
          </v-radio-group>

          <!-- TODO: Change from id to CODE -->
          <v-select
            v-show="form.repair_status_id === 2 || form.repair_status_id === 7"
            v-model="form.repair_barriers"
            v-bind:value="getRepairBarrierValues()"
            :items="getRepairBarrierItems()"
            :label="t('messages.form_repair_barriers')"
            :placeholder="t('messages.form_repair_barriers_placeholder')"
            :error-messages="getErrorMessages('repair_barriers')"
            outlined
            multiple
          />
          <v-ckeditor
            v-if="!draft && showDescriptionField"
            v-model="form.fix_description"
            :label="t('messages.form_device_fix')"
            :placeholder="t('messages.form_device_fix_placeholder')"
            :error-messages="getErrorMessages('fix_description')"
          />
          <v-heading level="3">
            {{ t('messages.repair_log_aides') }}
          </v-heading>
          <v-ckeditor
            v-model="form.used_links"
            :label="t('messages.form_used_links')"
            :error-messages="getErrorMessages('used_links')"
          />
          <v-heading level="3">
            {{ t('messages.repair_user_materials') }}
          </v-heading>
          <v-ckeditor
            v-model="form.used_materials"
            :label="t('messages.form_used_materials')"
            :error-messages="getErrorMessages('used_materials')"
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
import moment from 'moment';

export default {
  props: {
    device: {
      type: Object,
      default: () => null
    },
    log: {
      type: Object,
      default: () => null
    },
    device_types: {
      type: Array,
      default: () => null
    },
    repair_statuses: {
      type: Array,
      default: () => null
    },
    repair_barriers_archived: {
      type: Array,
      default: () => null
    },
    repair_barriers_end_of_life: {
      type: Array,
      default: () => null
    },
    barriers: {
      type: Array,
      default: () => null
    },
    images_repair: {
      type: Array,
      default: () => null
    },
    repair_links: {
      type: Array,
      default: () => null
    },
    draft: {
      type: Boolean,
      default: false
    },
    showDescriptionField: {
      type: Boolean,
      default: false
    },
    data: {
      type: Object,
      default: () => null
    }
  },
  data() {
    return {
      form: {
        device_type_id: parseInt(this.data.device_type_id, 10),
        repair_status_id: parseInt(this.log.repair_status_id, 10),
        repair_barriers: this.data ? this.barriers : [],
        brand_name: this.data ? this.data.brand_name : null,
        model_name: this.data ? this.data.model_name : null,
        manufacture_year: this.data ? this.data.manufacture_year : null,
        fix_description: null,
        images_repair: this.data ? this.images_repair.images_repair : [],
        repair_links: this.data ? this.log.repair_links : [],
        draft: this.draft,
        showDescriptionField: this.showDescriptionField
      }
    };
  },
  methods: {
    moment() {
      return moment();
    },
    submit() {
      this.$inertia.post(
        this.route('device_log_repaired_update', {
          uuid: this.log.uuid
        }),
        this.createFormData(this.form)
      );
    },
    openImageGallery(key) {
      this.imageGalleryIndex = key;
      this.imageGallery = true;
    },
    getRepairBarrierValues() {
      return this.getRepairBarrierItems().value;
    },
    getRepairBarrierItems() {
      if (this.form.repair_status_id === 7) {
        return this.repair_barriers_archived;
      }

      return this.repair_barriers_end_of_life;
    }
  }
};
</script>
