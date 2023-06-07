<template>
  <layout-base>
    <v-section hero>
      <v-heading>
        {{ t('messages.title_password_reset') }}
      </v-heading>
    </v-section>
    <v-section cols="6" light>
      <v-heading level="3">
        {{ t('messages.reset_password') }}
      </v-heading>
      <form @submit.prevent="submit">
        <input v-model="form.token" type="hidden" name="token" required />
        <v-form-field :label="t('messages.form_email')">
          <template #default="inputProps">
            <v-text-field
              v-model="form.email"
              v-bind="inputProps"
              :error-messages="getErrorMessages('email')"
              name="email"
              required
              prepend-icon="mdi-account"
            />
          </template>
        </v-form-field>
        <v-form-field :label="t('messages.form_password')">
          <template #default="inputProps">
            <v-text-field
              v-model="form.password"
              v-bind="inputProps"
              :error-messages="getErrorMessages('password')"
              :placeholder="t('messages.pick_safe_password')"
              name="password"
              required
              prepend-icon="mdi-lock"
              type="password"
            />
          </template>
        </v-form-field>
        <v-form-field :label="t('messages.form_password_confirm')">
          <template #default="inputProps">
            <v-text-field
              v-model="form.password_confirmation"
              v-bind="inputProps"
              :error-messages="getErrorMessages('password')"
              :placeholder="t('messages.pick_safe_password_repeat')"
              name="password_confirmation"
              required
              prepend-icon="mdi-lock"
              type="password"
            />
          </template>
        </v-form-field>
        <v-btn @click="submit" color="primary">
          {{ t('messages.form_reset_password') }}
        </v-btn>
      </form>
    </v-section>
  </layout-base>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      default: () => null
    }
  },
  data() {
    return {
      form: {
        email: this.data ? this.data.email : null,
        password: null,
        token: this.data ? this.data.token : null
      }
    };
  },
  methods: {
    submit() {
      this.$inertia.post(this.route('password.update'), this.createFormData(this.form));
    }
  }
};
</script>
