<template>
  <div>
    <div v-if="repairer && created_timestamp" class="text-small">{{ repairer }} - {{ toDate(created_timestamp) }}</div>
    <r-editor v-if="!disabled" :model-value="content" @update:modelValue="update" required />
    <div
      v-else
      v-html="content"
      class="text-small block w-full rounded border-gray-300 border-2 border-solid bg-gray-100 px-3 py-2 box-border prose max-w-full mb-6"
    />
  </div>
</template>

<script>
export default {
  props: {
    disabled: {
      type: Boolean,
      default: () => false
    },
    repairer: {
      type: String,
      default: function() {
        return this.$page.props.user?.name || null;
      }
    },
    content: {
      type: String,
      default: () => null
    },
    created_timestamp: {
      type: Number,
      default: () => Math.floor(new Date().getTime() / 1000)
    },
    id: {
      type: Number,
      default: () => null
    }
  },
  created() {
    this.update(this.content);
  },
  methods: {
    update(content) {
      this.$emit('update', {
        ...this.$props,
        content
      });
    },
    toDate(timestampSeconds) {
      const date = new Date(timestampSeconds * 1000);
      return `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`;
    }
  }
};
</script>
