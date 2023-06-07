<template>
  <a
    :href="route('device_show', { slug: data.slug })"
    :title="`${data.brand_name} - ${data.model_name}`"
    class="relative items-stretch block overflow-hidden no-underline transition-colors bg-gray-100 rounded-lg xl:flex text-main group hover:bg-gray-200"
  >
    <div class="relative aspect-w-16 aspect-h-9 xl:aspect-none xl:w-[200px] xl:min-h-[200px] flex-shrink-0">
      <div
        class="bg-gray-200 bg-center bg-no-repeat bg-cover xl:absolute xl:inset-0"
        :style="
          data.image && {
            backgroundImage: `url(${data.image})`
          }
        "
      >
        <r-icon
          v-if="!data.image"
          name="mdiWrench"
          class="absolute text-gray-400 transform translate-x-1/2 translate-y-1/2 right-1/2 bottom-1/2 text-huge"
        />
      </div>
    </div>
    <div
      class="xl:relative flex p-3 border-t-[8px] xl:border-t-0 xl:border-l-[8px] border-solid"
      :class="[
        `border-status-${data.latest_status}`,
        {
          'xl:pt-8': hasEvent && !$page.props.history
        }
      ]"
    >
      <div
        v-if="hasEvent && !$page.props.history"
        class="absolute top-0 right-0 flex items-center px-2 text-white rounded-bl-lg bg-secondary text-tiny"
      >
        <r-icon name="mdiCalendar" class="mr-1" />
        <span class="font-semibold"> {{ data.event.locale_name }} - {{ data.event.date_formatted }}</span>
      </div>
      <div class="flex-grow">
        <span class="px-3 py-1 font-bold bg-white rounded-full text-tiny text-secondary">
          {{ data.device_type_name }}
        </span>
        <h3 class="my-2 font-black transition-colors text-h4 text-secondary group-hover:text-secondary-dark">
          {{ data.brand_name }} - {{ data.model_name }}
        </h3>
        <div class="text-tiny">
          #{{ data.id }}
          <span class="font-bold">
            {{ data.device_description }}
          </span>
          -
          {{
            t('messages.device_created_at', {
              date: data.created_timestamp
            })
          }}
          <span v-if="data.show_postal_code && data.postal_code">
            -
            {{
              t('messages.device_postal_code', {
                postalcode: data.postal_code
              })
            }}
          </span>
        </div>
        <div class="flex items-baseline italic text-tiny">
          <span
            class="flex-shrink-0 inline-block w-2 h-2 mr-2 rounded-full"
            :class="`bg-status-${data.latest_status}`"
          ></span>
          <span v-if="data.status_last_updated_at">
            <span v-if="data.latest_status === 'done'">
              {{
                t('messages.device_status_since', {
                  status,
                  date: data.status_last_updated_at
                })
              }}
            </span>
            <span v-else>
              {{
                t('messages.device_status_since_by', {
                  status,
                  date: data.status_last_updated_at,
                  name: data.repairer_name
                })
              }}
            </span>
          </span>
          <span v-else>
            {{ status }}
          </span>
        </div>
        <div v-if="teaser" v-html="teaser" class="mt-2 text-small teaser" />
      </div>
      <!-- <r-icon class="text-[30px] text-secondary hidden xl:block" name="mdiArrowRight" /> -->
    </div>
  </a>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  computed: {
    status() {
      const { t, data } = this;

      return t(`messages.status_${data.latest_status}`);
    },
    teaser() {
      const issueDescription = this.data.issue_description;
      const maxLength = 120;

      if (issueDescription.length > maxLength) {
        return `${issueDescription.slice(0, maxLength)}&hellip;`;
      }

      return issueDescription;
    },
    hasEvent() {
      return this.data.event /*&& this.data.has_future_event*/;
    }
  }
};
</script>
