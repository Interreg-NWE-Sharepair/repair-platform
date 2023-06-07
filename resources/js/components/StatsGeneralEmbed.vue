<template>
  <iframe
    title="Repair Statistics"
    id="iframe-stats"
    :src="`${$page.props.app.url}/api/repair/statistics?lang=${lang}`"
    frameborder="0"
  ></iframe>
</template>

<script>
// :src="`${app.url}/api/repair/statistics?organisation=${organisation}&lang=${lang}`"
import iFrameResize from 'iframe-resizer/js/iframeResizer';

export default {
  data: function() {
    return {
      app: ''
    };
  },
  props: {
    lang: {
      type: String,
      required: true
    }
  },
  mounted() {
    iFrameResize({ log: false }, '#iframe-stats');
  }
};
</script>
