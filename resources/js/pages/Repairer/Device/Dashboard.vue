<template>
  <layout-base>
    <r-section class="bg-gray-100">
      <h1 class="text-h1 text-secondary">{{ t('messages.my_devices') }}</h1>
    </r-section>
    <r-section>
      <r-grid>
        <r-grid-item class="md:w-8/12">
          <h2 class="text-h2 text-secondary">{{ t('messages.devices_in_my_groups') }}</h2>
          <div v-if="organisations">
            <div class="mb-6">
              <div v-for="organisation in organisations" :key="organisation.id" class="mb-6">
                <r-link
                  :href="`#${organisation.slug[$page.props.locale]}`"
                  icon-after="mdiChevronRight"
                  color="secondary"
                >
                  {{ organisation.name[$page.props.locale] }}
                </r-link>
              </div>
            </div>
            <div>
              <div v-for="organisation in organisations" :key="organisation.id" class="mb-9">
                <h3 class="text-h2 text-secondary">
                  <a :id="organisation.slug[$page.props.locale]" class="relative top-[-120px]"></a>
                  {{ organisation.name[$page.props.locale] }}
                </h3>
                <h4 class="text-h3 text-secondary">{{ t('messages.device_working_on') }}</h4>
                <div v-if="hasDevices">
                  <device-list :endpoint="`/api/devices/repairer/${organisation.uuid}`" />
                </div>
                <div v-else class="mb-6">
                  {{ t('messages.no_devices_found') }}
                </div>
                <r-link
                  inertia
                  :href="
                    route('location_repairer_fixed_overview', { locationCode: organisation.slug[$page.props.locale] })
                  "
                  icon-after="mdiChevronRight"
                  color="secondary"
                >
                  {{ t('messages.route_repairer_personal_overview') }}
                </r-link>

                <h4 class="text-h3 text-secondary">{{ t('messages.devices_repairable') }}</h4>
                <device-list :endpoint="`/api/devices/torepair/${organisation.uuid}`" />
                <r-link
                  inertia
                  :href="route('location_devices_overview', { locationCode: organisation.slug[$page.props.locale] })"
                  icon-after="mdiChevronRight"
                  color="secondary"
                >
                  {{
                    t('messages.route_repairer_general_overview', {
                      location: organisation.name[$page.props.locale]
                    })
                  }}
                </r-link>
              </div>
            </div>
          </div>
          <div v-else>
            <p>{{ t('messages.no_repairer_locations') }}</p>
          </div>
        </r-grid-item>
        <r-grid-item class="md:sticky md:top-[120px] md:self-start md:w-4/12">
          <div v-if="statuses" class="py-6">
            <div v-for="(status, key) in statuses" :key="key" class="flex items-center mb-2">
              <span class="w-4 h-4 mr-2 rounded-full text" :class="`bg-status-${status.status}`"></span>
              <span>{{ status.text }}</span>
            </div>
          </div>
        </r-grid-item>
      </r-grid>
    </r-section>
  </layout-base>
</template>

<script>
import DeviceList from '@/js/components/DeviceList';

export default {
  components: {
    DeviceList
  },
  props: {
    hasDevices: {
      type: Boolean,
      default: false
    },
    organisations: {
      type: Array,
      default: () => []
    },
    statuses: {
      type: Array,
      default: () => []
    }
  }
};
</script>
