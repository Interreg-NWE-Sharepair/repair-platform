<template>
  <layout-base>
    <r-section class="bg-gray-100">
      <v-step-actions :step="3" @click-prev="prevStep" />
      <device-register-steps :steps="3" />
    </r-section>
    <r-section class="bg-gray-100">
      <h1 class="text-h1 text-primary">{{ t('messages.step_2_title_device') }}</h1>
      <p class="text-lrg">{{ t('messages.create_device_step_2_description') }}</p>
    </r-section>
    <r-section class="bg-gray-100">
      <div class="col-md-7 col-12">
        <h3 class="text-h3 text-primary">{{ t('messages.repairer_get_in_touch') }}</h3>
        <form @submit.prevent="submit">
          <v-radio-group v-model="form.register_type" mandatory>
            <div>
              <v-radio
                :label="
                  t('messages.repairer_create_device_step_2_event', {
                    locationName: organisation.name[$page.props.locale]
                  })
                "
                value="event"
                :errors="getErrorMessages('register_type')"
                :disabled="!hasEvents"
              />
              <div
                v-if="hasEvents"
                v-html="
                  t('messages.repairer_create_device_step_2_event_text', {
                    locationName: organisation.name[$page.props.locale],
                    url: route('location_show', { organisation: organisation.slug[$page.props.locale] })
                  })
                "
              />
              <r-select
                v-if="hasEvents"
                v-model="form.event"
                :options="events"
                :placeholder="t('messages.form_event_select_placeholder')"
                :errors="getErrorMessages('event')"
                track-by="value"
                label-by="text"
                required
              >
                <template #option="{ option }">
                  <span :class="{ 'text-error': option.isFull }">{{ option.label }}</span>
                </template>
              </r-select>
              <div v-else class="opacity-40">
                <p class="italic text-tiny">
                  {{ t('messages.no_upcoming_events_text') }}
                </p>
              </div>
            </div>
            <div>
              <v-radio
                :label="t('messages.repairer_create_device_step_2_person')"
                value="person"
                :errors="getErrorMessages('register_type')"
                :off-icon="!hasEvents ? '$radioOn' : '$radioOff'"
              />
              <div>
                {{ t('messages.repairer_create_device_step_2_person_text') }}
              </div>
            </div>
          </v-radio-group>
          <h3 class="text-h3 text-primary">{{ t('messages.create_device_step_2_subtitle') }}</h3>
          <r-input
            v-model="form.first_name"
            required
            :label="t('messages.form_first_name')"
            :placeholder="t('messages.form_first_name_placeholder')"
            :errors="getErrorMessages('first_name')"
          />
          <r-input
            v-model="form.last_name"
            required
            :label="t('messages.form_last_name')"
            :placeholder="t('messages.form_last_name_placeholder')"
            :errors="getErrorMessages('last_name')"
          />
          <r-input
            v-model="form.email"
            :label="t('messages.form_email')"
            :placeholder="t('messages.form_email_placeholder')"
            :errors="getErrorMessages('email')"
            :rules="emailRules"
            required
          />
          <r-input
            v-model="form.telephone"
            :label="t('messages.form_telephone')"
            :placeholder="t('messages.form_telephone_placeholder')"
            :errors="getErrorMessages('telephone')"
          />
          <r-input
            v-model="form.postal_code"
            :label="t('messages.form_postal_code')"
            :placeholder="t('messages.form_postal_code_placeholder')"
            :errors="getErrorMessages('postal_code')"
          />
          <r-checkbox
            v-model="form.terms"
            :label="
              t('messages.device_accept_terms', {
                conditions: route('terms_conditions'),
                privacy: route('privacy')
              })
            "
            :errors="getErrorMessages('terms')"
            class="mb-6"
            required
          />
          <google-re-captcha-v3
            v-model="form.gRecaptchaResponse"
            :site-key="siteKeyVariable"
            :error-messages="getErrorMessages('gRecaptchaResponse')"
            id="device_step_2_store"
            ref="captcha"
            action="device_step_2_store"
          />
          <div class="flex items-center justify-between -ml-4">
            <v-step-actions :step="3" @click-prev="prevStep" color="ghost" :marginBottom="false" />
            <r-button type="submit" color="primary" icon-after="mdiChevronRight" :loading="form.processing">
              {{ t('messages.form_submit') }}
            </r-button>
          </div>
        </form>
      </div>
    </r-section>
  </layout-base>
</template>

<script>
import DeviceRegisterSteps from '@/js/components/DeviceRegisterSteps';
import GoogleReCaptchaV3 from '@/js/components/googlerecaptchav3/GoogleReCaptchaV3';

import Form from '@/js/mixins/Form';

export default {
  mixins: [Form],
  components: {
    DeviceRegisterSteps,
    GoogleReCaptchaV3
  },
  props: {
    data: {
      type: Object,
      default: () => null
    },
    organisation: {
      type: Object,
      default: () => null
    },
    event: {
      type: Object,
      default: () => null
    },
    events: {
      type: Array,
      default: () => []
    },
    location: {
      type: String,
      default: () => null
    }
  },
  data() {
    return {
      form: {
        first_name: null,
        last_name: null,
        email: null,
        telephone: null,
        postal_code: null,
        register_type: null,
        event: this.event ?? null,
        terms: null,
        gRecaptchaResponse: null
      },
      siteKeyVariable: '6Lc3Ie0UAAAAAHhl794N-_SmR_TK6_gevjzpvpr0',
      emailRules: [v => !!v || 'E-mail is required', v => /.+@.+\..+/.test(v) || 'E-mail must be valid'],
      isLoading: false
    };
  },
  computed: {
    hasEvents() {
      return !!this.events.length;
    }
  },
  mounted() {
    if (!this.hasEvents) {
      this.form.register_type = 'person';
    }
  },
  methods: {
    prevStep() {
      this.visitRoute('device_create', {
        locationCode: this.location,
        step: 1
      });
    },
    submit() {
      this.$inertia.post(
        this.route('device_step_2_store', {
          locationCode: this.organisation.uuid
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
