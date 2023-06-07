<template>
  <div v-if="device.repair_logs" class="prose">
    <ul>
      <li v-for="(log, key) in device.repair_logs" :key="key">
        <div v-if="log.person">
          {{ log.timestamp }}: {{ t('messages.device_selected_by', { repairer: log.person.full_name }) }}
        </div>
        <div v-else-if="!log.person">
          {{ log.timestamp }}: {{ t('messages.device_selected_by', { repairer: t('messages.person_deleted') }) }}
        </div>
        <template v-if="log.status && !['in_repair'].includes(log.status)">
          <div v-if="log.person">
            {{ log.updated_timestamp }}:
            {{
              t('messages.device_repair_ended', {
                status: getStatus(log),
                repairer: log.person.full_name
              })
            }}
          </div>
          <div v-else-if="!log.person">
            {{ log.updated_timestamp }}:
            {{
              t('messages.device_repair_ended', {
                status: getStatus(log),
                repairer: t('messages.person_deleted')
              })
            }}
          </div>
        </template>
      </li>
    </ul>
  </div>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';

export default {
  mixins: [DeviceDetail],
  methods: {
    getStatus(log) {
      const repairLogStatus = this.t(`messages.status_${log.status}`);
      if (log.completed_repair_status) {
        return `${repairLogStatus}: ${log.completed_repair_status.locale_name}`;
      }

      return repairLogStatus;
    }
  }
};
</script>
