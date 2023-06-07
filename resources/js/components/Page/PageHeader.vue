<template>
  <div v-if="data.intro" v-html="data.intro" class="text-large mb-6" />
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
