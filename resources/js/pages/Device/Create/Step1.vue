<template>
  <layout-base>
    <r-section class="bg-gray-100">
      <v-step-actions :step="2" @click-prev="prevStep" @click-next="nextStep" />
      <device-register-steps :steps="2" />
    </r-section>
    <r-section>
      <h1 class="text-h1 text-primary">{{ t('messages.step_1_title_device') }}</h1>
      <p class="text-lrg">{{ t('messages.create_device_step_1_description') }}</p>
    </r-section>
    <r-section>
      <r-form @submit.prevent="submit" enctype="multipart/form-data">
        <h3 class="text-h3 text-primary">{{ t('messages.create_device_step_1_subtitle') }}</h3>

        <h4 class="text-base font-bold">
          {{ t('messages.form_device_type') }}
          <r-icon
            name="mdiInformationOutline"
            v-tooltip="{
              content: t('messages.form_device_type_tooltip'),
              trigger: 'click hover'
            }"
            class="text-base"
          />
        </h4>

        <!-- desktop only -->
        <r-form-field :errors="getErrorMessages('device_type_id')" class="hidden md:block">
          <div class="grid grid-flow-row grid-cols-3">
            <div v-for="(deviceTypeGroup, deviceTypeGroupKey) in device_types" :key="deviceTypeGroupKey" class="mb-6">
              <strong class="text-lrg">{{ deviceTypeGroup.name }}</strong>
              <r-grid class="!mt-0">
                <r-grid-item
                  v-for="(deviceType, deviceTypeKey) in deviceTypeGroup.children"
                  :key="deviceTypeKey"
                  class="!mt-0"
                >
                  <r-radio v-model="form.device_type_id" :label="deviceType.name" :value="deviceType.value" />
                </r-grid-item>
              </r-grid>
            </div>
          </div>
        </r-form-field>

        <div class="max-w-2xl">
          <!-- mobile only -->
          <r-select
            v-model="form.device_type_id"
            :options="device_types"
            :placeholder="t('messages.form_device_type_placeholder')"
            :errors="getErrorMessages('device_type_id')"
            :value="form.device_type_id"
            track-by="value"
            label-by="name"
            group-label="name"
            group-values="children"
            class="md:hidden"
            searchable="searchable"
          />

          <r-input
            v-model="form.brand_name"
            required
            :label="t('messages.form_brand_name')"
            :tooltip="{
              content: t('messages.form_brand_name_tooltip'),
              trigger: 'click hover'
            }"
            :placeholder="t('messages.form_brand_name_placeholder')"
            :errors="getErrorMessages('brand_name')"
          />
          <r-textarea
            v-model="form.issue_description"
            required
            :label="t('messages.form_device_issue')"
            :placeholder="t('messages.form_device_issue_placeholder')"
            :errors="getErrorMessages('issue_description')"
          />
          <r-input
            v-model="form.model_name"
            :label="t('messages.form_model_name')"
            :tooltip="{
              content: t('messages.form_model_name_tooltip'),
              trigger: 'click hover'
            }"
            :placeholder="t('messages.form_model_name_placeholder')"
            :errors="getErrorMessages('model_name')"
          />

          <r-input
            v-model="form.device_description"
            :label="t('messages.form_device_description')"
            :tooltip="{
              content: t('messages.form_device_description_tooltip'),
              trigger: 'click hover'
            }"
            :placeholder="t('messages.form_device_description_placeholder')"
            :errors="getErrorMessages('device_description')"
          />

          <r-input
            v-model="form.manufacture_year"
            :label="t('messages.form_manufacture_year')"
            :tooltip="{
              content: t('messages.form_manufacture_year_tooltip'),
              trigger: 'click hover'
            }"
            :placeholder="t('messages.form_manufacture_year_placeholder')"
            :errors="getErrorMessages('manufacture_year')"
          />
        </div>

        <div class="max-w-2xl">
          <h3 class="text-h3 text-primary">
            {{ t('messages.create_device_photos_title') }}
          </h3>

          <div class="mb-3">
            <label class="mr-2 text-base font-bold" v-html="t('messages.form_image_general')"></label>
            <small class="mr-2 align-baseline opacity-70 message-optional">{{ t('messages.optional') }}</small>
            <div class="opacity-50 text-small" v-html="t('messages.form_image_general_help')"></div>
            <media-library-collection
              name="image_general"
              :validation-rules="{ accept: ['image/*'], maxSizeInKB: 10240 }"
              :initial-value="image_general"
              :validation-errors="getErrorMessages('image_general')"
              :max-items="1"
              :multiple="false"
              :sortable="false"
              @change="onChangeGeneral"
              :translations="{
                fileTypeNotAllowed: t('media.fileTypeNotAllowed'),
                tooLarge: t('media.tooLarge'),
                tooSmall: t('media.tooSmall'),
                tryAgain: t('media.tryAgain'),
                somethingWentWrong: t('media.somethingWentWrong'),
                selectOrDrag: t('media.selectOrDrag'),
                file: { singular: t('media.file'), plural: t('media.files') },
                selectOrDragMax: t('media.selectOrDragMax', { maxItems: 1 }),

                anyImage: t('media.anyImage'),
                anyVideo: t('media.anyVideo'),
                goBack: t('media.goBack'),
                dropFile: t('media.dropFile'),
                dragHere: t('media.dragHere'),
                remove: t('media.remove'),
                download: t('media.download')
              }"
            />
          </div>
          <div class="mb-3">
            <label class="mr-2 text-base font-bold" v-html="t('messages.form_images_defect')"></label>
            <small class="mr-2 align-baseline opacity-70 message-optional">{{ t('messages.optional') }}</small>
            <div class="opacity-50 text-small" v-html="t('messages.form_images_defect_help')"></div>
            <media-library-collection
              name="images_defect"
              :initial-value="images_defect"
              :validation-errors="getErrorMessages('images_defect')"
              :validation-rules="{ accept: ['image/*'], maxSizeInKB: 10240 }"
              :max-items="5"
              @change="onChangeDefect"
              :translations="{
                fileTypeNotAllowed: t('media.fileTypeNotAllowed'),
                tooLarge: t('media.tooLarge'),
                tooSmall: t('media.tooSmall'),
                tryAgain: t('media.tryAgain'),
                somethingWentWrong: t('media.somethingWentWrong'),
                selectOrDrag: t('media.selectOrDrag'),
                file: { singular: t('media.file'), plural: t('media.files') },
                selectOrDragMax: t('media.selectOrDragMax', { maxItems: 5 }),

                anyImage: t('media.anyImage'),
                anyVideo: t('media.anyVideo'),
                goBack: t('media.goBack'),
                dropFile: t('media.dropFile'),
                dragHere: t('media.dragHere'),
                remove: t('media.remove'),
                download: t('media.download')
              }"
            />
          </div>
          <div class="mb-6">
            <label class="mr-2 text-base font-bold" v-html="t('messages.form_images_barcode')"></label>
            <small class="mr-2 align-baseline opacity-70 message-optional">{{ t('messages.optional') }}</small>
            <div class="opacity-50 text-small" v-html="t('messages.form_images_barcode_help')"></div>
            <media-library-collection
              name="images_barcode"
              :initial-value="images_barcode"
              :validation-errors="getErrorMessages('images_barcode')"
              :validation-rules="{ accept: ['image/*'], maxSizeInKB: 10240 }"
              :max-items="5"
              @change="onChangeBarcode"
              :translations="{
                fileTypeNotAllowed: t('media.fileTypeNotAllowed'),
                tooLarge: t('media.tooLarge'),
                tooSmall: t('media.tooSmall'),
                tryAgain: t('media.tryAgain'),
                somethingWentWrong: t('media.somethingWentWrong'),
                selectOrDrag: t('media.selectOrDrag'),
                file: { singular: t('media.file'), plural: t('media.files') },
                selectOrDragMax: t('media.selectOrDragMax', { maxItems: 5 }),

                anyImage: t('media.anyImage'),
                anyVideo: t('media.anyVideo'),
                goBack: t('media.goBack'),
                dropFile: t('media.dropFile'),
                dragHere: t('media.dragHere'),
                remove: t('media.remove'),
                download: t('media.download')
              }"
            />
          </div>
        </div>
      </r-form>
      <v-step-actions :step="2" @click-prev="prevStep" @click-next="nextStep" />
    </r-section>
  </layout-base>
</template>

<script>
import DeviceRegisterSteps from '@/js/components/DeviceRegisterSteps';
import { MediaLibraryCollection } from 'medialibrary/media-library-pro-vue2-collection';

export default {
  components: { DeviceRegisterSteps, MediaLibraryCollection },
  props: {
    device_types: {
      type: Array,
      default: () => null
    },
    data: {
      type: Object,
      default: () => null
    },
    image_general: {
      type: Array,
      default: () => null
    },
    images_defect: {
      type: Array,
      default: () => null
    },
    images_barcode: {
      type: Array,
      default: () => null
    },
    location: {
      type: String,
      default: () => null
    },
    eventKey: {
      type: String,
      default: () => null
    }
  },
  data() {
    return {
      form: {
        device_type_id: this.data ? parseInt(this.data.device_type_id) : null,
        device_description: this.data ? this.data.device_description : null,
        brand_name: this.data ? this.data.brand_name : null,
        model_name: this.data ? this.data.model_name : null,
        manufacture_year: this.data ? this.data.manufacture_year : null,
        issue_description: this.data ? this.data.issue_description : null,
        image_general: this.data ? this.data.image_general : null,
        images_defect: this.data ? this.data.images_defect : [],
        images_barcode: this.data ? this.data.images_barcode : [],
        eventKey: this.eventKey
      }
    };
  },
  methods: {
    prevStep() {
      this.visitRoute('device_create');
    },
    onChangeGeneral(image_general) {
      this.form.image_general = image_general;
    },
    onChangeDefect(images_defect) {
      this.form.images_defect = images_defect;
    },
    onChangeBarcode(images_barcode) {
      this.form.images_barcode = images_barcode;
    },
    nextStep() {
      this.$inertia.post(
        this.route('device_step_1_store', {
          locationCode: this.location
        }),
        this.createFormData(this.form)
      );
    }
  }
};
</script>
