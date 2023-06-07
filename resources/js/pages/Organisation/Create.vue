<template>
  <layout-base>
    <v-section hero>
      <v-heading>
        {{ t('messages.create_organisation_title') }}
      </v-heading>
      <v-text large>
        {{ t('messages.create_organisation_body') }}
      </v-text>
    </v-section>
    <v-section cols="6" light>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <v-form-field :label="t('messages.form_organisation_name')">
          <template #default="inputProps">
            <v-text-field
              v-model="form.organisation_name"
              v-bind="inputProps"
              :placeholder="t('messages.form_organisation_name_placeholder')"
              :error-messages="getErrorMessages('organisation_name')"
            />
          </template>
        </v-form-field>

        <v-row no-gutters>
          <v-col>
            <v-form-field :label="t('messages.form_organisation_postal_code')">
              <template #default="inputProps">
                <v-text-field
                  v-model="form.postal_code"
                  v-bind="inputProps"
                  :placeholder="t('messages.form_organisation_postal_code_placeholder')"
                  :error-messages="getErrorMessages('organisation_postal_code')"
                  class="pa-2"
                />
              </template>
            </v-form-field>
          </v-col>
          <v-col>
            <v-form-field :label="t('messages.form_organisation_municipality')">
              <template #default="inputProps">
                <v-text-field
                  v-model="form.municipality"
                  v-bind="inputProps"
                  :placeholder="t('messages.form_organisation_municipality_placeholder')"
                  :error-messages="getErrorMessages('organisation_municipality')"
                  class="pa-2"
                />
              </template>
            </v-form-field>
          </v-col>
        </v-row>

        <v-form-field :label="t('messages.form_email')">
          <template #default="inputProps">
            <v-text-field
              v-model="form.email"
              v-bind="inputProps"
              :placeholder="t('messages.form_email_placeholder')"
              :error-messages="getErrorMessages('email')"
            />
          </template>
        </v-form-field>
        <google-re-captcha-v3
          v-model="form.gRecaptchaResponse"
          :site-key="siteKeyVariable"
          :error-messages="getErrorMessages('gRecaptchaResponse')"
          id="contact_us_id"
          ref="captcha"
          action="location_store"
        />

        <v-btn @click="submit" color="secondary" class="my-6">
          {{ t('messages.send_message') }}
          <v-icon right>mdi-chevron-right</v-icon>
        </v-btn>
      </form>
    </v-section>
  </layout-base>
</template>

<script>
import GoogleReCaptchaV3 from '@/js/components/googlerecaptchav3/GoogleReCaptchaV3';

export default {
  components: {
    GoogleReCaptchaV3
  },
  props: {
    data: {
      type: Object,
      default: () => null
    },
    repairOrganisations: {
      type: Array,
      default: () => null
    }
  },
  data() {
    return {
      form: {
        name: null,
        email: null,
        message: null,
        repair_organisation: null,
        gRecaptchaResponse: null
      },
      siteKeyVariable: '6Lc3Ie0UAAAAAHhl794N-_SmR_TK6_gevjzpvpr0'
    };
  },
  methods: {
    submit() {
      this.$inertia.post(this.route('location_store'), this.createFormData(this.form), {
        onBefore: () => this.$refs.captcha.execute()
      });
    }
  }
};
</script>
