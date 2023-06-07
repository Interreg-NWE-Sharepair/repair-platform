<template>
  <div v-if="device.repair_log">
    <h2 class="text-h3 text-secondary">
      {{ t('messages.repair_log_details') }}
      <r-link
        v-if="(eventAdmin || entityAdmin || isLogRepairer) && device.latest_status === 'completed'"
        :href="route('device_show', { slug: device.slug }) + '?edit=true'"
        type="button"
        class="bg-white bg-opacity-0 text-base ml-auto"
      >
        <r-icon name="mdiPencil" class="text-secondary" />
      </r-link>
    </h2>

    <h3 class="text-h4 text-secondary">{{ t('messages.repair_device_history') }}</h3>
    <device-repair-history v-bind="$props" class="mb-6" />

    <h3 class="text-h4 text-secondary">{{ t('messages.repair_diagnosis') }}</h3>
    <div v-html="device.repair_log.diagnosis || '&ndash;'" class="prose" />

    <h3 class="text-h4 text-secondary">{{ t('messages.repair_root_cause') }}</h3>
    <div v-html="device.repair_log.root_cause || '&ndash;'" class="prose" />

    <h3 class="text-h4 text-secondary">
      {{ t('messages.repair_title') }}
    </h3>
    <dl>
      <div class="mb-6">
        <dt>{{ t('messages.repair_images_gallery') }}</dt>
        <dd>
          <div v-if="device.repair_log.images.length" class="max-w-xs">
            <r-gallery :items="device.repair_log.images">
              <template #thumbnail="{ item }">
                <img :src="item.small" alt="" class="object-cover w-full" />
              </template>
              <template #modal="{ item }">
                <img :src="item.url" alt="" class="flex object-contain max-w-full mx-auto" />
              </template>
            </r-gallery>
          </div>
          <div v-else>
            &ndash;
          </div>
        </dd>
      </div>

      <div v-if="device.log_notes.length" class="mb-6">
        <dt>{{ t('messages.log_notes') }}</dt>
        <dd>
          <div v-for="(note, key) in device.log_notes" :key="key" class="mb-3">
            <small>{{ note['formatted_timestamp'] }} - {{ note['repairer'] }}</small>
            <div v-html="note['content']" class="prose"></div>
          </div>
        </dd>
      </div>
      <div class="mb-6">
        <dt>{{ t('messages.aide_links') }}</dt>
        <dd v-html="device.repair_log.used_links || '&ndash;'" class="prose"></dd>
      </div>

      <div class="mb-6">
        <dt>{{ t('messages.repair_user_materials') }}</dt>
        <dd v-html="device.repair_log.used_materials || '&ndash;'" class="prose"></dd>
      </div>
    </dl>

    <h3 class="text-h4 text-secondary">
      {{ t('messages.last_status_repair') }}
    </h3>

    <dl>
      <div class="mb-6">
        <dt>{{ t('messages.last_status') }}</dt>
        <dd>
          {{ t(`messages.status_${device.latest_status}`) }}
          <template v-if="device.completed_repair_status">: {{ device.completed_repair_status.locale_name }}</template>
        </dd>
      </div>

      <div v-if="device.repair_log.barriers.length && device.repair_log.repair_status !== 'fixed'" class="mb-6">
        <dt>{{ t('messages.last_repair_barrieres') }}</dt>
        <dd class="prose">
          <ul v-for="(barrier, key) in device.repair_log.barriers" :key="key">
            <li>{{ barrier.name[$page.props.locale] }}</li>
          </ul>
        </dd>
      </div>
    </dl>

    <slot />
  </div>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';
import Form from '@/js/mixins/Form';

import DeviceRepairHistory from '@/js/components/Device/DeviceRepairHistory';

export default {
  mixins: [DeviceDetail, Form],
  components: { DeviceRepairHistory }
};
</script>
