<template>
  <r-panel>
    <form v-if="formModel" @submit.prevent="submit">
      <h2 class="text-h3 text-secondary">
        {{ t('messages.repair_device_history') }}
      </h2>
      <device-repair-history v-bind="$props" class="mb-6" />
      <div>
        <h2 class="text-h3 text-secondary">{{ t('messages.repair_diagnosis') }}</h2>
        <r-editor
          v-model="formModel.diagnosis"
          :info="t('messages.form_diagnosis')"
          :errors="getErrorMessages('diagnosis')"
          required
        />
        <h2 class="text-h3 text-secondary">{{ t('messages.repair_root_cause') }}</h2>
        <r-editor
          v-model="formModel.root_cause"
          :info="t('messages.form_root_cause')"
          :errors="getErrorMessages('root_cause')"
          required
        />
        <h2 class="text-h3 text-secondary">{{ t('messages.repair_title') }}</h2>
        <label class="mr-2 text-base font-bold" v-html="t('messages.repair_images_gallery')"></label>
        <small class="mr-2 align-baseline opacity-70 message-optional">{{ t('messages.optional') }}</small>
        <div class="opacity-50 text-small" v-html="t('messages.form_repair_images')"></div>
        <media-library-collection
          name="images_repair"
          :initial-value="images_repair"
          :validation-errors="getErrorMessages('images_repair')"
          :validation-rules="{ accept: ['image/*'], maxSizeInKB: 10240 }"
          :max-items="5"
          @change="onChangeRepair"
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
      <div class="mt-6">
        <r-form-collection v-model="formModel.notes" :label="t('messages.log_notes')" :errors="formModel.errors.notes">
          <template #default="{ item, updateItem }">
            <form-collection-item-note v-bind="item" @update="updateItem" />
          </template>
        </r-form-collection>
      </div>
      <div>
        <r-editor
          v-model="formModel.used_links"
          :label="t('messages.aide_links')"
          :info="t('messages.form_used_links')"
          :errors="getErrorMessages('used_links')"
        />
        <r-editor
          v-model="formModel.used_materials"
          :label="t('messages.repair_user_materials')"
          :info="t('messages.form_used_materials')"
          :errors="getErrorMessages('used_materials')"
        />
      </div>

      <r-button
        v-if="completedEdit"
        type="save"
        color="secondary"
        icon-before="mdiContentSave"
        ghost
        :loading="isLoading"
      >
        {{ t('messages.save') }}
      </r-button>
      <r-button v-else type="submit" color="secondary" icon-before="mdiContentSave" ghost :loading="isLoading">
        {{ t('messages.save_and_overview') }}
      </r-button>
      <slot />
    </form>
  </r-panel>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';
import Form from '@/js/mixins/Form';

import DeviceRepairHistory from '@/js/components/Device/DeviceRepairHistory';
import FormCollectionItemNote from '@/js/components/Device/FormCollectionItemNote';
import { MediaLibraryCollection } from 'medialibrary/media-library-pro-vue2-collection';

export default {
  mixins: [DeviceDetail, Form],
  components: { DeviceRepairHistory, FormCollectionItemNote, MediaLibraryCollection },
  methods: {
    submit() {
      const { formModel, route, device } = this;
      this.isLoading = true;
      formModel.post(
        route('device_log_repaired_edit', {
          uuid: device.repair_log.uuid
        }),
        {
          onFinish() {
            this.isLoading = false;
          }
        }
      );
    },
    onChangeRepair(images_repair) {
      this.formModel.images_repair = images_repair;
    }
  }
};
</script>
