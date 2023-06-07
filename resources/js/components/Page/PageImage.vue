<template>
  <div v-if="data.image && data.image.resize" class="flex justify-center mb-6">
    <figure>
      <img class="max-w-md" :src="data.image.resize" :title="data.caption" :alt="data.caption" />
      <figcaption class="text-small text-left italic px-3" v-if="data.caption">
        {{ data.caption }}
      </figcaption>
    </figure>
  </div>
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
