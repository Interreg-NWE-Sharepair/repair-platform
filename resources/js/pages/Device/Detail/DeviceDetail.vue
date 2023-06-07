<template>
  <layout-base>
    <r-section>
      <device-header v-bind="$props" />
      <device-actions v-bind="$props" />
      <device-event v-bind="$props" class="mt-6 md:w-8/12" />
      <device-notes v-model="formDeviceNotes" v-bind="$props" class="md:w-8/12" />
    </r-section>

    <!-- OPEN/REOPENED -->
    <r-section v-if="['open', 'reopened'].includes(device.latest_status) && !completedEdit">
      <r-grid>
        <r-grid-item v-bind:class="[entityAdmin || (hasEvent && eventAdmin) ? 'md:w-8/12' : 'md:w-full']">
          <device-info v-bind="$props">
            <device-log v-model="formRepairLogNotes" v-bind="$props" class="mt-6" />
            <device-actions v-model="formReopenLog" v-bind="$props" />
          </device-info>
        </r-grid-item>
        <r-grid-item v-if="entityAdmin || (hasEvent && eventAdmin)" class="md:w-4/12">
          <device-contact v-bind="$props" class="mb-6" />
        </r-grid-item>
      </r-grid>
    </r-section>

    <!-- PROGRESS -->
    <r-section v-else-if="device.latest_status === 'in_repair' || completedEdit">
      <r-grid>
        <r-grid-item class="md:w-8/12">
          <device-form-repair-log v-if="canEdit || completedEdit" v-model="formRepairLog" v-bind="$props">
            <device-actions v-bind="$props" class="mt-6" />
          </device-form-repair-log>
          <r-panel v-else>
            <device-log v-model="formRepairLogNotes" v-bind="$props" />
          </r-panel>
        </r-grid-item>
        <r-grid-item class="md:w-4/12">
          <device-contact v-if="canEdit" v-bind="$props" class="mb-6" />
          <device-info v-bind="$props" class="mb-6" />
          <device-help v-bind="$props" />
        </r-grid-item>
      </r-grid>
    </r-section>

    <!-- CLOSED -->
    <r-section v-else-if="device.latest_status === 'completed'">
      <device-info v-bind="$props">
        <device-log v-model="formRepairLogNotes" v-bind="$props" class="mt-6" />
      </device-info>
    </r-section>

    <device-modal-edit v-model="formDeviceEdit" v-bind="$props" />
    <device-modal-contact-edit v-model="formContactEdit" v-bind="$props" />
    <device-modal-status-close v-model="formRepairLog" v-bind="$props" />
    <device-modal-close v-model="formDeviceClose" v-bind="$props" />
    <device-modal-status-reopen v-model="formRepairLog" v-bind="$props" />
    <device-modal-assign-repairer v-model="formAssignRepairer" v-bind="$props" />
    <device-modal-assign-event v-model="formAssignEvent" v-bind="$props" />
  </layout-base>
</template>

<script>
import DeviceDetail from '@/js/mixins/DeviceDetail';

import DeviceActions from '@/js/components/Device/DeviceActions';
import DeviceContact from '@/js/components/Device/DeviceContact';
import DeviceFormRepairLog from '@/js/components/Device/DeviceFormRepairLog';
import DeviceHeader from '@/js/components/Device/DeviceHeader';
import DeviceHelp from '@/js/components/Device/DeviceHelp';
import DeviceInfo from '@/js/components/Device/DeviceInfo';
import DeviceLog from '@/js/components/Device/DeviceLog';
import DeviceModalEdit from '@/js/components/Device/DeviceModalEdit';
import DeviceModalContactEdit from '@/js/components/Device/DeviceModalContactEdit';
import DeviceModalStatusClose from '@/js/components/Device/DeviceModalStatusClose';
import DeviceModalClose from '@/js/components/Device/DeviceModalClose';
import DeviceModalStatusReopen from '@/js/components/Device/DeviceModalStatusReopen';
import DeviceModalAssignRepairer from '@/js/components/Device/DeviceModalAssignRepairer';
import DeviceModalAssignEvent from '@/js/components/Device/DeviceModalAssignEvent';
import DeviceEvent from '@/js/components/Device/DeviceEvent';
import DeviceNotes from '@/js/components/Device/DeviceNotes';

export default {
  mixins: [DeviceDetail],
  components: {
    DeviceNotes,
    DeviceModalAssignEvent,
    DeviceEvent,
    DeviceActions,
    DeviceContact,
    DeviceFormRepairLog,
    DeviceHeader,
    DeviceHelp,
    DeviceInfo,
    DeviceLog,
    DeviceModalEdit,
    DeviceModalContactEdit,
    DeviceModalStatusClose,
    DeviceModalClose,
    DeviceModalStatusReopen,
    DeviceModalAssignRepairer
  },
  data: () => ({
    formDeviceEdit: null,
    formRepairLog: null,
    formReopenLog: null,
    formAssignRepairer: null,
    formAssignEvent: null,
    formContactEdit: null,
    formDeviceNotes: null,
    formRepairLogNotes: null,
    formDeviceClose: null
  }),
  created() {
    const { device } = this;

    this.formDeviceEdit = this.$inertia.form({
      image_general: device.general_image,
      images_defect: device.defect_images,
      images_barcode: device.barcode_images,
      device_description: device.device_description,
      device_type_id: device.device_type_id,
      brand_name: device.brand_name,
      model_name: device.model_name,
      manufacture_year: device.manufacture_year,
      issue_description: device.issue_description
    });

    this.formContactEdit = this.$inertia.form({
      first_name: device.first_name,
      last_name: device.last_name,
      email: device.email,
      telephone: device.telephone
    });

    this.formDeviceNotes = this.$inertia.form({
      device_notes: device.device_notes.length ? device.device_notes : []
    });

    this.formRepairLogNotes = this.$inertia.form({
      notes: device.log_notes.length ? device.log_notes : []
    });

    this.formRepairLog = this.$inertia.form({
      diagnosis: device.repair_log ? device.repair_log.diagnosis : null,
      root_cause: device.repair_log ? device.repair_log.root_cause : null,
      images_repair: device.repair_log ? device.repair_log.repair_images : null,
      notes: device.log_notes.length ? device.log_notes : [],
      used_links: device.repair_log ? device.repair_log.used_links : null,
      used_materials: device.repair_log ? device.repair_log.used_materials : null,
      completed_status: null,
      repair_barrier: null,
      archive_reason: null,
      reopen_note: null,
      note: null
    });

    this.formAssignRepairer = this.$inertia.form({
      repairer_select: null
    });

    this.formAssignEvent = this.$inertia.form({
      event_select: null
    });

    this.formDeviceClose = this.$inertia.form({
      completed_status: this.$props.repairStatusesClose[0]['code'],
      repair_barrier: null,
      archive_reason: null,
      note: null
    });
  }
};
</script>
