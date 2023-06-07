<template>
  <div>
    <v-alert v-if="$page.props.info" v-html="$page.props.info" type="info" dismissible tile class="mb-0" />

    <v-alert v-if="$page.props.success" v-html="$page.props.success" type="success" dismissible tile class="mb-0" />

    <v-alert v-if="$page.props.warning" v-html="$page.props.warning" type="warning" dismissible tile class="mb-0" />

    <!--
    <v-alert v-if="$page.props.errors" v-html="$page.props.errors" type="error" dismissible tile class="mb-0" />
-->
  </div>
</template>
<script>
export default {};
</script>
