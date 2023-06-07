<template>
  <layout-base>
    <r-section class="bg-gray-100">
      <h1 class="text-h1 text-secondary">
        {{ t('messages.person_profile') }}
      </h1>
      <p>{{ t('messages.profile_info') }}</p>
    </r-section>
    <r-section>
      <h2 class="text-h2 text-secondary">{{ t('messages.basic_info') }}</h2>
      <form v-if="form" @submit.prevent="submit">
        <div class="grid md:grid-cols-2 gap-3">
          <r-input
            v-model="form.first_name"
            :label="t('messages.form_first_name')"
            required
            :errors="form.errors.first_name"
          />
          <r-input
            v-model="form.last_name"
            :label="t('messages.form_last_name')"
            required
            :errors="form.errors.last_name"
          />
          <r-input
            v-model="form.email"
            :label="t('messages.form_email')"
            type="email"
            required
            :errors="form.errors.email"
          />
          <r-input v-model="form.telephone" :label="t('messages.telephone')" :errors="form.errors.telephone" />
          <r-input
            v-model="form.location"
            :label="t('messages.form_location')"
            :errors="form.errors.location"
            :tooltip="t('messages.repairer_region_living')"
          />
          <r-editor
            v-model="form.specialization"
            :label="t('messages.specialization_title')"
            :errors="form.errors.specialization"
            class="wysiwyg"
          />
        </div>
        <div class="grid md:grid-cols-3 mt-6">
          <r-button type="submit" color="secondary" :loading="isLoading">
            {{ t('messages.form_confirm') }}
          </r-button>
        </div>
      </form>
    </r-section>
    <r-section>
      <h2 class="text-h2 text-secondary">{{ t('messages.locations') }}</h2>

      <div></div>
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              scope="col"
              class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2"
            >
              {{ t('messages.form_organisation_name') }}
            </th>
            <th
              scope="col"
              class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2"
            >
              {{ t('messages.roles') }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="organisation in organisations" :key="organisation.id">
            <td class="px-3 md:px-6 py-2 md:py-3 w-1/2">
              <a
                class="no-underline"
                :href="route('location_general_overview', { locationCode: organisation.slug[$page.props.locale] })"
                target="_blank"
              >
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 hidden md:block md:mr-4">
                    <img class="h-10 w-10 rounded-full" :src="organisation.image" />
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">
                      {{ organisation.name[$page.props.locale] }}
                      <v-icon right class="hidden md:inline-block">mdi-chevron-right</v-icon>
                    </div>
                    <div class="text-sm text-gray-500">
                      {{ organisation.organisation_type.name[$page.props.locale] }}
                    </div>
                  </div>
                </div>
              </a>
            </td>
            <td class="px-3 md:px-6 py-2 md:py-3 w-1/2">
              <div class="text-sm text-gray-900">
                {{ roles[organisation.uuid] }}
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </r-section>
  </layout-base>
</template>

<script>
import Form from '@/js/mixins/Form';

export default {
  mixins: [Form],
  props: {
    person: {
      type: Object,
      default: () => null
    },
    organisations: {
      type: Array,
      default: () => []
    },
    roles: {
      type: Object,
      default: () => null
    }
  },
  data: () => ({
    form: null
  }),

  created() {
    const { person } = this;

    this.form = this.$inertia.form({
      first_name: person.first_name,
      last_name: person.last_name,
      email: person.user.email,
      location: person.location,
      telephone: person.telephone,
      specialization: person.specialization
    });
  },
  methods: {
    submit() {
      const { route, form } = this;
      this.isLoading = true;
      form.post(route('person_profile_store', {}), {
        onFinish: () => {
          this.isLoading = false;
        }
      });
    }
  }
};
</script>
