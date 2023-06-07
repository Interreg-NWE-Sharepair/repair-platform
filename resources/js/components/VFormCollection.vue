<template>
  <div class="v-form-collection">
    <v-heading v-if="title" level="3" class="v-form-collection__title">
      {{ title }}
    </v-heading>
    <div v-if="value.length" class="v-form-collection__container">
      <div v-for="(item, index) in value" :key="index" class="v-form-collection__item">
        <slot :item="item" :index="index" :update-item="updateItem(index)"></slot>
        <button
          :aria-label="labelRemove || t('messages.form_collection_item_remove')"
          @click="removeItem(index)"
          @keyup.enter="removeItem(index)"
          type="button"
          class="v-form-collection__remove"
        >
          <v-icon>mdi-close</v-icon>
        </button>
      </div>
    </div>
    <div v-else class="font-italic mb-3">
      {{ labelEmpty || t('messages.form_collection_empty') }}
    </div>
    <v-btn @click="addItem" @keyup.enter="addItem" class="v-form-collection__add" small>
      {{ labelAdd || t('messages.form_collection_item_add') }}
    </v-btn>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: () => null
    },
    value: {
      type: Array,
      default: () => []
    },
    initialized: {
      type: Boolean,
      default: () => false
    },
    labelEmpty: {
      type: String,
      default: () => null
    },
    labelAdd: {
      type: String,
      default: () => null
    },
    labelRemove: {
      type: String,
      default: () => null
    }
  },
  mounted() {
    if (this.initialized && !this.value.length) {
      this.addItem();
    }
  },
  methods: {
    addItem() {
      this.$emit('input', [...this.value, null]);
    },

    removeItem(index) {
      this.$emit('input', [...this.value.slice(0, index), ...this.value.slice(index + 1)]);
    },

    updateItem(index, key) {
      return value => {
        const newValue = [...this.value];

        newValue[index][key] = value;

        this.$emit('input', newValue);
      };
    }
  }
};
</script>

<style lang="scss">
.v-form-collection {
  .v-form-collection__item {
    display: flex;
    align-items: flex-start;
  }
  .v-form-collection__remove {
    margin-left: 10px;
  }
}
</style>
