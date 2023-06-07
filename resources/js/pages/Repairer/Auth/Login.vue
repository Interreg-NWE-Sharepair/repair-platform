<template>
  <layout-base>
    <r-section class="bg-gray-100">
      <h1 class="text-h1 text-secondary">
        {{ t('messages.title_login_repairer') }}
      </h1>
    </r-section>
    <r-section>
      <r-grid>
        <r-grid-item class="md:w-5/12">
          <r-panel>
            <h2 class="text-h3 text-secondary">{{ t('messages.already_account') }}</h2>
            <form @submit.prevent="submit">
              <r-input
                v-model="form.email"
                :label="t('messages.form_email')"
                :errors="getErrorMessages('email')"
                required
              />

              <r-input
                v-model="form.password"
                type="password"
                :label="t('messages.form_password')"
                :errors="getErrorMessages('password')"
                required
              />
              <div class="mt-6 flex items-center">
                <r-button @click="submit" color="secondary" icon-after="mdiChevronRight">
                  {{ t('messages.form_login') }}
                </r-button>
                <r-link inertia :href="route('password.request')" color="secondary" class="text-small ml-3">
                  {{ t('messages.password_reset') }}
                </r-link>
              </div>
            </form>
          </r-panel>
        </r-grid-item>
        <r-grid-item class="md:w-7/12">
          <r-panel>
            <h2 class="text-h3 text-secondary">
              {{ t('messages.repairer_register') }}
            </h2>
            <p>{{ t('messages.home_register_text') }}</p>
            <div class="wysiwyg mb-6">
              <ol>
                <li>{{ t('messages.home_register_list_0') }}</li>
                <li>{{ t('messages.home_register_list_1') }}</li>
                <li>{{ t('messages.home_register_list_2') }}</li>
              </ol>
            </div>
            <r-button inertia :href="route('repairer_register_index')" color="secondary" icon-after="mdiChevronRight">
              {{ t('messages.home_register_cta') }}
            </r-button>
          </r-panel>
        </r-grid-item>
      </r-grid>
    </r-section>
  </layout-base>
</template>

<script>
export default {
  data() {
    return {
      form: {
        email: null,
        password: null
      }
    };
  },
  methods: {
    submit() {
      this.$inertia.post(this.route('repairer_login_store'), this.createFormData(this.form));
    }
  }
};
</script>
