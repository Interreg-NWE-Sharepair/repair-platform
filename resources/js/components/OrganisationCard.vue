<template>
  <inertia-link
    :href="route('location_show', { organisation: data.organisation.slug[$page.props.locale] })"
    class="flex flex-col items-stretch no-underline cursor-pointer group text-main"
  >
    <r-panel class="p-[0px!important] transition-colors flex-grow-1 group-hover:bg-gray-200">
      <div
        class="overflow-hidden transition-colors bg-white border-t-4 border-l-4 border-r-4 border-gray-100 border-solid aspect-w-16 aspect-h-9 rounded-t-3xl group-hover:border-gray-200"
      >
        <div class="bg-center bg-contain" :style="{ backgroundImage: `url(${data.organisation.image})` }" />
      </div>
      <div class="relative p-6 text-small">
        <div
          v-if="data.organisation.is_virtual"
          class="absolute top-0 right-0 flex items-center px-2 text-white rounded-bl-lg bg-primary text-tiny"
        >
          <r-icon name="mdiEarth" class="mr-1" />
          <span class="font-semibold"> {{ t('messages.online') }} </span>
        </div>
        <div class="flex items-center">
          <h3 class="mb-0 text-h3 text-primary">
            {{ data.organisation.name[$page.props.locale] }}
          </h3>
          <r-icon v-if="compact" name="mdiArrowRight" class="flex-shrink-0 ml-auto text-huge text-primary" />
        </div>
        <div v-if="!data.organisation.is_virtual" class="flex items-center mt-1">
          <r-icon name="mdiMapMarker" class="mr-1 text-primary" />
          <span class="font-semibold">{{ data.city }} ({{ data.country_code }})</span>
        </div>
        <template v-if="!compact">
          <p v-if="data.organisation.description" class="mt-3 mb-0">
            {{ data.organisation.description_short }}
          </p>
          <div
            class="flex items-center mt-3 font-semibold text-black underline group-hover:text-primary group-hover:no-underline"
          >
            {{ t('messages.read_more') }}
            <r-icon name="mdiChevronRight" class="ml-2 text-primary" />
          </div>
        </template>
      </div>
    </r-panel>
  </inertia-link>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true
    },
    compact: {
      type: Boolean,
      default: () => false
    }
  }
};
</script>
