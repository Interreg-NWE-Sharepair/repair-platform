<template>
  <r-modal name="device_edit">
    <form @submit.prevent="submit">
      <h2 class="text-h3 text-secondary">{{ t('messages.button_details_edit') }}</h2>
      <h3 class="text-h4 text-secondary">{{ t('messages.create_device_photos_title') }}</h3>
      <div class="mt-2 mb-4">
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
      <div class="mb-4">
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
      <div class="mb-4">
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
      <h3 class="text-h4 text-secondary">{{ t('messages.device_details') }}</h3>
      <r-input
        v-model="formModel.device_description"
        :label="t('messages.form_device_description')"
        :placeholder="t('messages.form_device_description_placeholder')"
        :errors="formModel.errors.device_description"
      />
      <r-select
        v-model="formModel.device_type_id"
        :label="t('messages.form_device_type')"
        :options="deviceTypes"
        :placeholder="t('messages.form_device_type_placeholder')"
        :errors="formModel.errors.device_type_id"
        track-by="value"
        label-by="name"
        searchable
      />
      <r-input
        v-model="formModel.brand_name"
        :label="t('messages.form_brand_name')"
        :placeholder="t('messages.form_brand_name_placeholder')"
        :errors="formModel.errors.brand_name"
      />
      <r-input
        v-model="formModel.model_name"
        :label="t('messages.form_model_name')"
        :placeholder="t('messages.form_model_name_placeholder')"
        :errors="formModel.errors.model_name"
      />
      <r-input
        v-model="formModel.manufacture_year"
        :label="t('messages.form_manufacture_year')"
        :placeholder="t('messages.form_manufacture_year_placeholder')"
        :errors="formModel.errors.manufacture_year"
      />
      <r-textarea
        v-model="formModel.issue_description"
        :label="t('messages.form_device_issue')"
        :placeholder="t('messages.form_device_issue_placeholder')"
        :errors="formModel.errors.issue_description"
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
import { MediaLibraryCollection } from 'medialibrary/media-library-pro-vue2-collection';

export default {
  mixins: [DeviceDetail, Form],
  components: { MediaLibraryCollection },
  methods: {
    submit() {
      const { route, formModel, device, $modal } = this;
      const { errors } = formModel;
      this.isLoading = true;
      formModel.post(
        route('repair_log_edit_device', {
          uuid: device.repair_log.uuid
        }),
        {
          onFinish: () => {
            this.isLoading = false;
            if (
              !errors.image_general &&
              !errors.images_defect &&
              !errors.images_barcode &&
              !errors.device_description &&
              !errors.device_type_id &&
              !errors.brand_name &&
              !errors.model_name &&
              !errors.manufacture_year &&
              !errors.issue_description
            ) {
              $modal.hide('device_edit');
            }
          }
        }
      );
    },
    onChangeGeneral(image_general) {
      this.formModel.image_general = image_general;
    },
    onChangeDefect(images_defect) {
      this.formModel.images_defect = images_defect;
    },
    onChangeBarcode(images_barcode) {
      this.formModel.images_barcode = images_barcode;
    }
  }
};
</script>
