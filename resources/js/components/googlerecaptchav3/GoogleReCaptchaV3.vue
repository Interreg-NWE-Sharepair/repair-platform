<template>
  <div>
    <div :id="id"></div>
    <div v-if="errorMessages" class="text-caption error--text">
      {{ errorMessages[0] }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'GoogleRecaptchaV3',
  props: {
    action: {
      type: String,
      default: () => 'validate_grecaptcha'
    },
    id: {
      type: String,
      default: () => 'grecaptcha_container'
    },
    siteKey: {
      type: String,
      default: () => '6Lc3Ie0UAAAAAHhl794N-_SmR_TK6_gevjzpvpr0' // set siteKey here if you want to store it in this component
    },
    inline: {
      type: Boolean,
      default: () => false
    },
    errorMessages: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      captchaId: null
    };
  },
  mounted() {
    this.init();
  },
  methods: {
    init() {
      if (!document.getElementById('gRecaptchaScript')) {
        window.gRecaptchaOnLoadCallbacks = [this.render];
        window.gRecaptchaOnLoad = function captcha() {
          for (let i = 0; i < window.gRecaptchaOnLoadCallbacks.length; i += 1) {
            window.gRecaptchaOnLoadCallbacks[i]();
          }
          delete window.gRecaptchaOnLoadCallbacks;
          delete window.gRecaptchaOnLoad;
        };

        const recaptchaScript = document.createElement('script');
        recaptchaScript.setAttribute(
          'src',
          'https://www.google.com/recaptcha/api.js?render=explicit&onload=gRecaptchaOnLoad'
        );
        recaptchaScript.setAttribute('id', 'gRecaptchaScript');
        recaptchaScript.async = true;
        recaptchaScript.defer = true;
        document.head.appendChild(recaptchaScript);
      } else if (!window.grecaptcha || !window.grecaptcha.render) {
        window.gRecaptchaOnLoadCallbacks.push(this.render);
      } else {
        this.render();
      }
    },

    render() {
      this.captchaId = window.grecaptcha.render(this.id, {
        sitekey: this.siteKey,
        badge: this.inline === true ? 'inline' : '',
        size: 'invisible'
      });

      this.execute();

      window.setInterval(this.execute, 800000);
    },

    execute() {
      window.grecaptcha
        .execute(this.captchaId, {
          action: this.action
        })
        .then(token => {
          this.$emit('input', token);
        });
    }
  }
};
</script>
