<template>
  <layout-base>
    <r-section class="bg-gray-100">
      <r-grid>
        <r-grid-item class="md:w-8/12">
          <h1 class="text-h1 text-primary">
            {{ lt(organisation.name, $page.props.locale, $page.props.fallback_locale) }}
          </h1>
          <div>
            <div class="flex flex-col flex-wrap -mx-4 text-lg font-semibold sm:flex-row skew-dividers">
              <div class="px-4">
                <span class="text-large text-secondary">
                  {{ repairers }}
                </span>
                {{ t('messages.stats_active_repairers_text') }}
              </div>
              <div class="px-4">
                <span class="text-large text-primary">
                  {{ devices }}
                </span>
                {{ t('messages.stats_fixed_devices_text') }}
              </div>
            </div>
          </div>
          <p
            class="mt-4 mb-6 text-large"
            v-html="lt(organisation.description, $page.props.locale, $page.props.fallback_locale)"
          />
          <ul class="pl-0 space-y-1">
            <li>
              <r-link
                v-if="organisation.telephone"
                :href="'tel:' + organisation.telephone"
                icon-before="mdiPhone"
                class="font-normal hover:text-black social-link"
              >
                {{ organisation.telephone }}
              </r-link>
            </li>
            <li>
              <r-link
                v-if="organisation.email"
                :href="'mailto:' + organisation.email"
                icon-before="mdiEmail"
                class="font-normal hover:text-black social-link"
              >
                {{ organisation.email }}
              </r-link>
            </li>
            <li>
              <r-link
                v-if="organisation.website"
                :href="organisation.website"
                icon-before="mdiEarth"
                target="_blank"
                rel="noopener noreferrer"
                class="font-normal hover:text-black social-link"
              >
                {{ organisation.website }}
              </r-link>
            </li>
            <li>
              <r-link
                v-if="organisation.facebook"
                :href="organisation.facebook"
                icon-before="mdiFacebook"
                target="_blank"
                rel="noopener noreferrer"
                class="font-normal hover:text-black social-link"
              >
                {{
                  t('messages.facebook', {
                    name: lt(organisation.name, $page.props.locale, $page.props.fallback_locale)
                  })
                }}
              </r-link>
            </li>
            <li>
              <r-link
                v-if="organisation.instagram"
                :href="organisation.instagram"
                icon-before="mdiInstagram"
                target="_blank"
                rel="noopener noreferrer"
                class="font-normal hover:text-black social-link"
              >
                {{
                  t('messages.instagram', {
                    name: lt(organisation.name, $page.props.locale, $page.props.fallback_locale)
                  })
                }}
              </r-link>
            </li>
          </ul>
          <!--          <div v-if="organisation.warranty_description && organisation.has_warranty" class="mt-6">
            <h2 class="text-h2 text-secondary">
              {{ t('repgui.warranty_title') }}
            </h2>
            {{ lt(organisation.warranty_description, $page.props.locale, $page.props.fallback_locale) }}
          </div>-->
        </r-grid-item>
        <r-grid-item class="md:w-4/12">
          <img :src="organisation.image" alt="" />
        </r-grid-item>
      </r-grid>
    </r-section>
    <r-section class="bg-gray-100">
      <stats-organisation-embed :organisation="organisation.uuid" :lang="$page.props.locale"></stats-organisation-embed>
    </r-section>
    <r-section v-if="events">
      <h2 class="text-h2 text-primary">
        {{ t('messages.future_events') }}
      </h2>
      <organisation-event-list></organisation-event-list>
    </r-section>
    <r-section>
      <r-grid>
        <r-grid-item class="md:w-5/12">
          <r-panel class="bg-gray-100">
            <h2 class="text-h3 text-primary">
              {{
                t('messages.home_register_device_title', {
                  name: lt(organisation.name, $page.props.locale, $page.props.fallback_locale)
                })
              }}
            </h2>
            <p>
              {{
                t('messages.home_register_device_text', {
                  location: lt(organisation.name, $page.props.locale, $page.props.fallback_locale)
                })
              }}
            </p>
            <r-button
              inertia
              :href="
                route('device_create', {
                  step: 2,
                  locationCode: lt(organisation.slug, $page.props.locale, $page.props.fallback_locale)
                })
              "
              icon-after="mdiChevronRight"
            >
              {{ t('messages.home_register_device') }}
            </r-button>
          </r-panel>
        </r-grid-item>
        <r-grid-item class="md:w-7/12">
          <r-panel class="bg-gray-100">
            <h2 class="text-h3 text-secondary">{{ t('messages.location_register_title') }}</h2>
            <p>
              {{
                t('messages.location_register_text', {
                  location: lt(organisation.name, $page.props.locale, $page.props.fallback_locale)
                })
              }}
            </p>
            <div class="mb-6 wysiwyg">
              <ol>
                <li>
                  {{
                    t('messages.location_register_list_0', {
                      location: lt(organisation.name, $page.props.locale, $page.props.fallback_locale)
                    })
                  }}
                </li>
                <li>{{ t('messages.location_register_list_1') }}</li>
                <li>{{ t('messages.location_register_list_2') }}</li>
              </ol>
            </div>
            <r-button
              inertia
              :href="
                route('repairer_register_index', {
                  locationCode: lt(organisation.slug, $page.props.locale, $page.props.fallback_locale)
                })
              "
              icon-after="mdiChevronRight"
              color="secondary"
            >
              {{
                t('messages.location_register_cta', {
                  location: lt(organisation.name, $page.props.locale, $page.props.fallback_locale)
                })
              }}
            </r-button>
          </r-panel>
        </r-grid-item>
      </r-grid>
    </r-section>
  </layout-base>
</template>

<script>
import OrganisationEventList from '../../components/OrganisationEventList';
import StatsOrganisationEmbed from '../../components/StatsOrganisationEmbed';

export default {
  components: { OrganisationEventList, StatsOrganisationEmbed },
  props: {
    organisation: {
      type: Object,
      default: () => null
    },
    repairers: {
      type: Number,
      default: 0
    },
    devices: {
      type: Number,
      default: 0
    },
    events: {
      type: Array,
      default: () => []
    },
    showOrganizer: {
      type: Boolean,
      default: () => false
    }
  }
};
</script>

<style lang="scss">
.social-link:hover {
  svg {
    transform: translateX(0) !important;
  }
}
</style>
