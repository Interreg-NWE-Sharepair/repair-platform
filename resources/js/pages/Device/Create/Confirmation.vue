<template>
  <layout-base>
    <r-section>
      <div class="wysiwyg mx-auto">
        <h1 v-if="title" class="text-h1 text-primary">
          {{ title }}
        </h1>
        <h4 class="text-black font-semibold">
          {{ t('messages.device_confirmation_device_number', { key: device.id }) }}
        </h4>
        <div class="v-text v-text--lg">
          <p>{{ intro }}</p>
        </div>
        <div v-if="(body && !flexible) || !flexible.length" v-html="body" ref="body" />
        <div v-else>
          <div class="mx-auto">
            <div v-for="item in flexible" :key="item.key">
              <component v-if="item.layout !== 'header'" :is="'page-' + item.layout" :data="item.attributes" />
            </div>
          </div>
        </div>
      </div>
    </r-section>
  </layout-base>
</template>

<script>
import PageHeader from '../../../components/Page/PageHeader';
import PageWysiwyg from '../../../components/Page/PageWysiwyg';
import PageImage from '../../../components/Page/PageImage';
import PageWysiwygImage from '../../../components/Page/PageWysiwygImage';
import PageVideo from '../../../components/Page/PageVideo';
export default {
  components: { PageVideo, PageWysiwygImage, PageImage, PageWysiwyg, PageHeader },
  props: {
    title: {
      type: String,
      default: () => null
    },
    intro: {
      type: String,
      default: () => null
    },
    body: {
      type: String,
      default: () => null
    },
    device: {
      type: Object,
      default: () => null
    },
    flexible: {
      type: Array,
      default: () => []
    }
  },
  mounted() {
    if (this.$refs.body) {
      this.$refs.body.querySelectorAll('oembed[url]').forEach(embed => {
        const iframe = document.createElement('iframe');
        iframe.src = embed.getAttribute('url');
        iframe.frameborder = 0;
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.allowfullscreen = true;
        embed.parentNode.setAttribute('class', 'u-embed mx-auto my-3');
        embed.parentNode.replaceChild(iframe, embed);
      });
    }

    if (this.$refs.flexible) {
      this.$refs.flexible.querySelectorAll('oembed[url]').forEach(embed => {
        const iframe = document.createElement('iframe');
        iframe.src = embed.getAttribute('url');
        iframe.frameborder = 0;
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.allowfullscreen = true;
        embed.parentNode.setAttribute('class', 'u-embed mx-auto my-3');
        embed.parentNode.replaceChild(iframe, embed);
      });
    }
  }
};
</script>
