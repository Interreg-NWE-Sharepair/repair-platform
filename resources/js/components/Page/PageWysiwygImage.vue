<template>
  <div v-if="data.image && data.image.resize" class="flex items-center mb-6">
    <div class="w-full md:w-1/2">
      <figure>
        <img :src="data.image.resize" :alt="data.caption" class="max-w-md" />
        <figcaption class="text-small text-left italic px-3" v-if="data.caption">
          {{ data.caption }}
        </figcaption>
      </figure>
    </div>
    <div
      class="w-full md:w-1/2 wysiwyg py-3"
      :class="{
        'order-first': data.position === 'right'
      }"
      v-html="data.body"
    ></div>
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
