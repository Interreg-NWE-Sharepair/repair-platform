<template>
  <layout-base>
    <r-section class="bg-light">
      <h1 class="text-h1 text-primary">{{ t('messages.contact_title') }}</h1>
      <p class="text-large">{{ t('messages.contact_body') }}</p>
      <r-link :href="route('about')" icon-after="mdiChevronRight" inertia>{{ t('messages.route_about') }}</r-link>
    </r-section>
    <r-section>
      <form v-if="form" @submit.prevent="submit" class="max-w-2xl">
        <r-input v-model="form.name" :label="t('messages.form_name')" :errors="getErrorMessages('name')" required />

        <r-input v-model="form.email" :label="t('messages.form_email')" :errors="getErrorMessages('email')" required />

        <r-select
          v-model="form.repair_organisation"
          :options="repairOrganisations"
          :label="t('messages.contact_repair_organisation')"
          :placeholder="t('messages.contact_repair_organisation_placeholder')"
          :info="t('messages.contact_repair_organisation_hint')"
          :errors="getErrorMessages('repair_organisation')"
          label-by="text"
          track-by="value"
        />

        <r-textarea
          v-model="form.message"
          :label="t('messages.form_message')"
          :errors="getErrorMessages('message')"
          required
        />

        <r-checkbox
          v-model="form.terms"
          :label="
            t('messages.contact_accept_terms', {
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
          :errors="form.errors.gRecaptchaResponse"
          id="contact_store"
          ref="captcha"
          action="contact_Store"
        />

        <r-button type="submit" color="primary" class="my-6" icon-after="mdiChevronRight" :loading="form.processing">
          {{ t('messages.send_message') }}
        </r-button>
      </form>
    </r-section>
  </layout-base>
</template>

<script>
import Form from '@/js/mixins/Form';
import GoogleReCaptchaV3 from '@/js/components/googlerecaptchav3/GoogleReCaptchaV3';

export default {
  components: {
    GoogleReCaptchaV3
  },
  mixins: [Form],
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
      form: null,
      siteKeyVariable: '6Lc3Ie0UAAAAAHhl794N-_SmR_TK6_gevjzpvpr0',
      isLoading: false
    };
  },
  created() {
    this.form = this.$inertia.form({
      name: null,
      email: null,
      message: null,
      repair_organisation: null,
      terms: null,
      gRecaptchaResponse: null
    });
  },
  methods: {
    submit() {
      this.$inertia.post(this.route('contact_store'), this.createFormData(this.form), {
        onBefore: () => this.$refs.captcha.execute()
      });
    }
  }
};
</script>
