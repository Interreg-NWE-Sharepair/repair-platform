<template>
  <r-panel class="relative items-stretch block mb-6 overflow-hidden no-underline bg-gray-100 rounded-lg xl:flex">
    <div>
      <h3 class="my-2 text-h3 text-secondary">{{ data.person.first_name }} {{ data.person.last_name }}</h3>
      <div v-if="data.organisation.show_employee_info" class="flex flex-wrap gap-2 mb-3">
        <div>
          <div v-if="data.person && data.person.telephone">
            <r-link color="secondary" :href="'tel:' + data.person.telephone" icon-before="mdiPhone">{{
              data.person.telephone
            }}</r-link>
          </div>
          <div v-if="data.person && data.person.user && data.person.user.email">
            <r-link color="secondary" :href="'mailto:' + data.person.user.email" icon-before="mdiEmail">
              {{ data.person.user.email }}
            </r-link>
          </div>
          <div v-if="data.person.location">
            <r-icon name="mdiMapMarker" class="mr-2 text-secondary" />
            <span>{{ data.person.location }}</span>
          </div>
        </div>
        <div v-if="data.person.specialization && !compact" class="pl-6">
          <h4 class="mb-1 font-black text-secondary">{{ t('messages.specialization_title') }}</h4>
          <div class="wysiwyg" v-html="data.person.specialization"></div>
        </div>
      </div>
      <div
        v-if="!compact"
        class="absolute top-0 right-0 flex items-center px-2 text-white rounded-bl-lg bg-secondary text-tiny"
      >
        <span class="font-semibold">{{ data.roles_as_string }}</span>
      </div>
    </div>
  </r-panel>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true
    },
    compact: {
      type: Boolean,
      default: () => false
    }
  },
  computed: {
    isAdmin() {
      return !!this.data.admin;
    }
  }
};
</script>
