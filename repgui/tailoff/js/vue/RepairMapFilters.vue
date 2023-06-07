<template>
  <div class="mb-4">
    <button class="mt-2 mr-2 btn btn--secondary" @click="$emit('change', 'TYPE')">
      {{ typeText }}
      <svg v-if="activeFilter == 'TYPE'" class="ml-2 icon" aria-hidden="true">
        <use xlink:href="/repgui/icon/sprite.svg#chevron-up"></use>
      </svg>
      <svg v-else class="ml-2 icon" aria-hidden="true">
        <use xlink:href="/repgui/icon/sprite.svg#chevron-down"></use>
      </svg>
    </button>
    <button class="mt-2 mr-2 btn btn--secondary" @click="$emit('change', 'CATEGORY')">
      {{ categoryText }}
      <svg v-if="activeFilter == 'CATEGORY'" class="ml-2 icon" aria-hidden="true">
        <use xlink:href="/repgui/icon/sprite.svg#chevron-up"></use>
      </svg>
      <svg v-else class="ml-2 icon" aria-hidden="true">
        <use xlink:href="/repgui/icon/sprite.svg#chevron-down"></use>
      </svg>
    </button>
  </div>
</template>

<script>
export default {
  props: {
    activeFilter: {
      type: String,
      default: null
    },
    typeText: {
      type: String,
      default: 'Type'
    },
    categoryText: {
      type: String,
      default: 'Category'
    }
  }
};
</script>
