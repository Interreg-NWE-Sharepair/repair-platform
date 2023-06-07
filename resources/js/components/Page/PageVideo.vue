<template>
  <div v-if="data.video || data.iframe" class="mb-6">
    <h3 v-if="data.title" class="text-h3">
      {{ data.title }}
    </h3>
    <div class="aspect-w-16 aspect-h-9">
      <iframe
        v-if="data.video"
        class="border-0"
        :src="data.video"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        :allowfullscreen="true"
      />
      <div class="border-0" v-else v-html="data.iframe"></div>
      <div v-if="data.iframe[$page.props.locale]">
        <div v-html="data.iframe[$page.props.locale]" />
      </div>
      <span class="text-small text-left italic px-3" v-if="data.caption[$page.props.locale]">
        {{ data.caption[$page.props.locale] }}</span
      >
    </div>
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
