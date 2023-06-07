export default {
  props: {
    device: {
      type: Object,
      default: () => null
    },
    deviceTypes: {
      type: Array,
      default: () => []
    },
    repairStatusesCompleted: {
      type: Array,
      default: () => []
    },
    repairStatusesClose: {
      type: Array,
      default: () => []
    },
    repairBarriers: {
      type: Array,
      default: () => []
    },
    repairArchiveReasons: {
      type: Array,
      default: () => []
    },
    canEdit: {
      type: Boolean,
      default: () => false
    },
    canEditEvent: {
      type: Boolean,
      default: () => false
    },
    eventAdmin: {
      type: Boolean,
      default: () => false
    },
    repairers: {
      type: Array,
      default: () => null
    },
    hasEvent: {
      type: Boolean,
      default: () => false
    },
    futureEvents: {
      type: Array,
      default: () => []
    },
    entityAdmin: {
      type: Boolean,
      default: () => false
    },
    editContact: {
      type: Boolean,
      default: () => false
    },
    completedEdit: {
      type: Boolean,
      default: () => false
    },
    isLogRepairer: {
      type: Boolean,
      default: () => false
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
    images_repair: {
      type: Array,
      default: () => null
    }
  }
};
