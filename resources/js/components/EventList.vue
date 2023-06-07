<template>
  <div>
    <v-section>
      <template #default="event">
        <event-card :data="event" />
      </template>
    </v-section>
  </div>
</template>

<script>
export default {
  props: {
    event: {
      type: Object,
      default: () => null
    }
  }
};
</script>
