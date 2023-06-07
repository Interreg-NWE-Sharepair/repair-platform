<template>
  <div class="mb-6 wysiwyg" v-if="data.body" v-html="data.body" />
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true
    }
  }
};
</script>
