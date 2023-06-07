<template>
  <layout-base>
    <v-section hero>
      <v-heading>
        {{ t('messages.title_forgot_password') }}
      </v-heading>
      <!-- <v-text large>
                {{ t('messages.forgot_password') }}
            </v-text> -->
    </v-section>
    <v-section cols="6" light>
      <form @submit.prevent="submit">
        <v-form-field :label="t('messages.form_email')">
          <template #default="inputProps">
            <v-text-field
              v-model="form.email"
              v-bind="inputProps"
              :error-messages="getErrorMessages('email')"
              name="email"
              required
              prepend-icon="mdi-account"
              type="text"
            />
          </template>
        </v-form-field>
        <v-btn @click="submit" color="primary">
          {{ t('messages.form_reset') }}
          <v-icon right>mdi-chevron-right</v-icon>
        </v-btn>
      </form>
    </v-section>
  </layout-base>
</template>

<script>
export default {
  data() {
    return {
      form: {
        email: null
      }
    };
  },
  methods: {
    submit() {
      this.$inertia.post(this.route('password.email'), this.createFormData(this.form));
    }
  }
};
</script>
