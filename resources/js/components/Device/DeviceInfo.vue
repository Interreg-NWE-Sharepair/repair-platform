<template>
  <r-panel class="relative">
    <h2 class="flex items-start justify-between text-h3 text-secondary">
      <span class="pr-1">{{ t('messages.device_details') }}</span>
      <span>
        <button
          v-if="canEdit && device.repair_log && device.latest_status === 'in_repair'"
          type="button"
          class="ml-auto text-base bg-white bg-opacity-0"
          v-tooltip="{
            content: t('messages.edit'),
            trigger: 'click hover'
          }"
          @click="$modal.show('device_edit')"
        >
          <r-icon name="mdiPencil" class="text-secondary" />
        </button>
      </span>
    </h2>
    <dl>
      <div class="max-w-xs mb-6">
        <dt>
          {{ t('messages.form_images') }}
        </dt>
        <dd>
          <div v-if="device.images.length" class="max-w-xs">
            <r-gallery :items="device.images">
              <template #thumbnail="{ item }">
                <img :src="item.small" alt="" class="object-cover w-full" />
              </template>
              <template #modal="{ item }">
                <img :src="item.url" alt="" class="flex object-contain max-w-full mx-auto" />
              </template>
            </r-gallery>
          </div>
          <div v-else>&ndash;</div>
        </dd>
      </div>
      <div class="mb-6">
        <dt>
          {{ t('messages.form_device_description') }}
        </dt>
        <dd v-html="device.device_description || '&ndash;'"></dd>
      </div>
      <div class="mb-6">
        <dt>
          {{ t('messages.form_device_type') }}
        </dt>
        <dd v-html="device.device_type.name[$page.props.locale]"></dd>
      </div>
      <div class="mb-6">
        <dt>
          {{ t('messages.form_brand_name') }}
        </dt>
        <dd v-html="device.brand_name"></dd>
      </div>
      <div class="mb-6">
        <dt>
          {{ t('messages.form_model_name') }}
        </dt>
        <dd v-html="device.model_name || '&ndash;'"></dd>
      </div>
      <div class="mb-6">
        <dt>
          {{ t('messages.form_manufacture_year') }}
        </dt>
        <dd v-html="device.manufacture_year || '&ndash;'"></dd>
      </div>
      <div class="mb-6">
        <dt>
          {{ t('messages.form_device_issue') }}
        </dt>
        <dd v-html="device.issue_description"></dd>
      </div>
    </dl>
    <slot />
  </r-panel>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';

export default {
  mixins: [DeviceDetail]
};
</script>
