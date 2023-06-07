<template>
  <div :class="{ 'error--text': errorMessages.length }">
    <v-label v-if="label" :for="editorId">
      {{ label }}
    </v-label>
    <ckeditor :id="editorId" :editor="editor" :config="editorConfig" :value="value" @input="$emit('input', $event)" />
    <div v-if="errorMessages.length" color="error">
      <div v-for="(errorMessage, key) in errorMessages" :key="key">
        {{ errorMessage }}
      </div>
    </div>
  </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

import '@ckeditor/ckeditor5-build-classic/build/translations/fr';
import '@ckeditor/ckeditor5-build-classic/build/translations/nl';

export default {
  props: {
    label: {
      type: String,
      default: () => ''
    },
    placeholder: {
      type: String,
      default: () => ''
    },
    errorMessages: {
      type: Array,
      default: () => []
    },
    value: {
      type: String,
      default: () => ''
    }
  },
  computed: {
    editor() {
      return ClassicEditor;
    },
    editorConfig() {
      const config = {
        language: document.documentElement.lang,
        toolbar: {
          items: ['bold', 'italic', '|', 'link', '|', 'lists']
        }
      };

      if (this.placeholder) {
        config.placeholder = this.placeholder;
      }

      return config;
    },
    editorId() {
      return `editor-${this._uid}`;
    }
  }
};
</script>

<style lang="scss">
:root {
  --ck-border-radius: 4px;
}
</style>
