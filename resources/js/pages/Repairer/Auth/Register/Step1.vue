<template>
  <layout-base>
    <v-section light>
      <repairer-register-steps :steps="1" />
      <v-row>
        <v-col cols="12" md="7">
          <v-heading color="secondary">
            {{ t('messages.step_1_title_repairer') }}
          </v-heading>
          <v-text large>
            {{ t('messages.register_repairer_body') }}
          </v-text>
        </v-col>
      </v-row>
    </v-section>
    <v-section light>
      <v-row>
        <v-col cols="12" md="6">
          <v-heading level="3" color="secondary">
            {{ t('messages.fill_in_info') }}
          </v-heading>
          <form @submit.prevent="submit" enctype="multipart/form-data">
            <v-form-field :label="t('messages.form_first_name')">
              <template #default="inputProps">
                <v-text-field
                  v-model="form.first_name"
                  v-bind="inputProps"
                  :error-messages="getErrorMessages('first_name')"
                  placeholder="vb. Mark"
                />
              </template>
            </v-form-field>

            <v-form-field :label="t('messages.form_last_name')">
              <template #default="inputProps">
                <v-text-field
                  v-model="form.last_name"
                  v-bind="inputProps"
                  :error-messages="getErrorMessages('last_name')"
                  placeholder="vb. Baarmans"
                />
              </template>
            </v-form-field>

            <v-form-field :label="t('messages.form_email')">
              <template #default="inputProps">
                <v-text-field
                  v-model="form.email"
                  v-bind="inputProps"
                  :error-messages="getErrorMessages('email')"
                  placeholder="vb. mark.baarmans@repairconnects.org"
                />
              </template>
            </v-form-field>

            <v-form-field :label="t('messages.form_telephone')">
              <template #default="inputProps">
                <v-text-field
                  v-model="form.telephone"
                  v-bind="inputProps"
                  :placeholder="t('messages.form_telephone_placeholder')"
                  :error-messages="getErrorMessages('telephone')"
                />
              </template>
            </v-form-field>

            <v-form-field :label="t('messages.form_password')">
              <template #default="inputProps">
                <v-text-field
                  v-model="form.password"
                  v-bind="inputProps"
                  :placeholder="t('messages.pick_safe_password')"
                  :error-messages="getErrorMessages('password')"
                  type="password"
                />
              </template>
            </v-form-field>

            <v-form-field :label="t('messages.form_password_confirm')">
              <template #default="inputProps">
                <v-text-field
                  v-model="form.password_confirmation"
                  v-bind="inputProps"
                  :placeholder="t('messages.pick_safe_password_repeat')"
                  :error-messages="getErrorMessages('password_confirmation')"
                  type="password"
                />
              </template>
            </v-form-field>

            <v-checkbox v-model="form.terms" :error-messages="getErrorMessages('terms')" required>
              <div
                v-html="
                  t('messages.repairer_accept_terms', {
                    conditions: route('terms_conditions'),
                    privacy: route('privacy')
                  })
                "
                @click.stop
                @keyup.stop
                slot="label"
              ></div>
            </v-checkbox>
            <google-re-captcha-v3
              v-model="form.gRecaptchaResponse"
              :site-key="siteKeyVariable"
              :error-messages="getErrorMessages('gRecaptchaResponse')"
              id="repairer_register_step_1_store"
              ref="captcha"
              action="repairer_register_step_1_store"
              class="mb-6"
            />
            <v-btn @click="submit" color="secondary">
              {{ t('messages.form_submit') }}
              <v-icon right>mdi-chevron-right</v-icon>
            </v-btn>
          </form>
        </v-col>
      </v-row>
    </v-section>
  </layout-base>
</template>

<script>
import GoogleReCaptchaV3 from '@/js/components/googlerecaptchav3/GoogleReCaptchaV3';
import RepairerRegisterSteps from '@/js/components/RepairerRegisterSteps';

export default {
  components: {
    GoogleReCaptchaV3,
    RepairerRegisterSteps
  },
  props: {
    locations: {
      type: Array,
      default: () => null
    },
    defaultLocation: {
      type: Number,
      default: () => null
    },
    location: {
      type: String,
      default: () => null
    }
  },
  data() {
    return {
      form: {
        location_id: this.defaultLocation ? parseInt(this.defaultLocation, 10) : null,
        first_name: null,
        last_name: null,
        email: null,
        telephone: null,
        terms: null,
        password: null,
        password_confirmation: null,
        gRecaptchaResponse: null
      },
      siteKeyVariable: '6Lc3Ie0UAAAAAHhl794N-_SmR_TK6_gevjzpvpr0'
    };
  },
  methods: {
    submit() {
      this.$inertia.post(
        this.route('repairer_register_step_1_store', {
          locationCode: this.location
        }),
        this.createFormData(this.form),
        {
          onBefore: () => this.$refs.captcha.execute()
        }
      );
    }
  }
};
</script>
