<template>
  <v-navigation-drawer
    v-model="isOpen"
    :permanent="!!$page.props.user && $vuetify.breakpoint.mdAndUp"
    app
    color="grey lighten-4"
    disable-resize-watcher
    clipped
  >
    <!-- nav when not logged in -->
    <div v-if="!$page.props.user">
      <v-list nav>
        <v-list-item-group>
          <v-list-item @click="visitRoute('about')">
            <v-list-item-content>
              <v-list-item-title>
                {{ t('messages.route_about') }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item @click="visitRoute('location_index')">
            <v-list-item-content>
              <v-list-item-title>
                {{ t('messages.locations') }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item @click="visitRoute('contact_index')">
            <v-list-item-content>
              <v-list-item-title>
                {{ t('messages.route_contact_index') }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
      <v-sheet color="grey lighten-4">
        <v-list-item @click="visitRoute('repairer_login_index')" dense>
          <v-list-item-content>
            <v-list-item-title class="d-flex align-center">
              <v-icon small left>mdi-account</v-icon>
              <span>
                {{ t('messages.route_repairer_login_index') }}
              </span>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-sheet>
    </div>
    <!-- nav when logged in -->
    <div v-else>
      <v-list nav dense class="py-0">
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>
              <div class="text--black">
                {{
                  t('messages.greeting', {
                    user: $page.props.user.name
                  })
                }}
              </div>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item v-if="$page.props.user.hasRepairer" @click="visitRoute('person_profile_show')">
          <v-list-item-content>
            <v-list-item-title>
              <div>
                <v-icon small left>
                  mdi-account-outline
                </v-icon>
                <span>{{ t('messages.person_profile') }}</span>
              </div>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="handleLogout()">
          <v-list-item-content>
            <v-list-item-title>
              <v-icon small left>mdi-exit-to-app</v-icon>
              <span>
                {{ t('messages.route_logout') }}
              </span>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-divider class="divider" />
      <v-list class="py-0">
        <v-list-item v-if="$page.props.user.hasRepairer" @click="visitRoute('repairer_dashboard')">
          <v-list-item-content>
            <v-list-item-title>
              {{ t('messages.route_repairer_dashboard') }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          v-for="organisation in $page.props.user.organisations"
          :key="organisation.id"
          @click="visitRoute('location_general_overview', { locationCode: organisation.slug[$page.props.locale] })"
        >
          <v-list-item-content>
            <v-list-item-title
              v-html="
                t('messages.overview_devices', {
                  location: organisation.name[$page.props.locale]
                })
              "
            />
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="visitRoute('history_repair_log_overview')">
          <v-list-item-content>
            <v-list-item-title>
              {{ t('messages.route_repair_history_overview') }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="visitRoute('contact_index')">
          <v-list-item-content>
            <v-list-item-title>
              {{ t('messages.route_contact_index') }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-divider class="divider" />
      <v-container>
        <v-row>
          <v-col cols="12" class="px-4">
            <app-lang-switch />
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-navigation-drawer>
</template>

<script>
import AppLangSwitch from '@/js/components/AppLangSwitch';

export default {
  components: {
    AppLangSwitch
  },
  props: {
    user: {
      type: Object,
      default: () => null
    },
    value: {
      validator: prop => typeof prop === 'boolean' || prop === null,
      required: true
    }
  },
  methods: {
    async handleLogout() {
      await axios.post('/logout', {});
      window.location.reload();
    }
  },
  computed: {
    isOpen: {
      get() {
        return this.value;
      },
      set(newValue) {
        this.$emit('input', newValue);
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.v-navigation-drawer {
  z-index: 10;
}
.divider {
  border: 2px solid;
  border-color: #ffffff !important;
}
</style>
