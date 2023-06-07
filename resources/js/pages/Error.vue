<template>
  <layout-base>
    <r-section class="bg-gray-100">
      <h1 class="text-h1 text-primary">
        {{ t(`error.${status}_title`) }}
      </h1>
    </r-section>
    <r-section>
      <p>{{ t(`error.${status}_text`) }}</p>
      <r-button inertia :href="route('home_index')" icon-before="mdiChevronLeft">
        {{ t('error.back') }}
      </r-button>
    </r-section>
  </layout-base>
</template>

<script>
export default {
  props: {
    status: {
      type: Number,
      default: () => 404
    }
  },
  computed: {
    title() {
      return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden'
      }[this.status];
    },
    description() {
      return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.'
      }[this.status];
    }
  }
};
</script>
