<template>
  <v-form-field
    :label="
      t('messages.form_repair_links', {
        count: index + 1
      })
    "
  >
    <template #default="inputProps">
      <v-text-field v-model="url" v-bind="inputProps" :error-messages="getErrorMessages(`${name}[${index}].url`)" />
    </template>
  </v-form-field>
</template>

<script>
import FormCollectionItem from '@/js/mixins/FormCollectionItem';

export default {
  mixins: [FormCollectionItem],
  data: () => ({
    url: null
  })
};
</script>
