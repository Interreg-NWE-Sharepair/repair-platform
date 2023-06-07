<template>
  <layout-base>
    <v-container>
      <div class="text-center">
        <v-heading color="secondary"> {{ device.brand_name }} {{ device.model_name }} </v-heading>
        <p>
          {{ t('messages.registered_by_person', { owner: device.owner_name }) }}
          {{ t('messages.at_time', { at: device.created_timestamp }) }}
          <span v-if="device.postal_code">
            - {{ t('messages.device_postal_code', { postalcode: device.postal_code }) }}</span
          >
        </p>
        <v-card v-if="repairerAssigned || repairerStarted" class="mx-auto">
          <v-card-text>
            <strong :class="`${statusColor}--text`">
              {{ t('messages.you_are_repairing') }}
            </strong>
            <p class="title">
              {{
                t('messages.contact_device_person', {
                  name: device.first_name
                })
              }}
            </p>
            <div class="text--primary">
              <div v-if="device.telephone">
                <v-icon>mdi-phone</v-icon>
                <a :href="'tel:' + device.telephone">{{ device.telephone }}</a>
              </div>
              <div v-if="device.email">
                <v-icon>mdi-email</v-icon>
                <a :href="'mailto:' + device.email">{{ device.email }}</a>
              </div>
            </div>
          </v-card-text>
          <v-card-actions v-if="repairerAssigned || repairerStarted">
            <v-btn
              v-if="repairerAssigned"
              @click="
                visitRoute('device_start_repair', {
                  slug: device.slug
                })
              "
              class="mx-auto"
              color="primary"
            >
              {{ t('messages.button_repair_start') }}
            </v-btn>
            <v-btn
              v-if="repairerStarted"
              @click="
                visitRoute('device_log_repaired_reopen', {
                  uuid: log.uuid
                })
              "
              class="mx-auto"
              color="primary"
            >
              {{ t('messages.button_repair_reopen') }}
            </v-btn>
            <v-btn
              v-if="repairerStarted"
              @click="
                visitRoute('device_log_repaired_close', {
                  uuid: log.uuid
                })
              "
              class="mx-auto"
              color="primary"
            >
              {{ t('messages.button_repair_complete') }}
            </v-btn>
          </v-card-actions>
        </v-card>
        <v-chip
          v-else-if="log && !repairerAssigned && !repairerStarted && needsRepair"
          v-text="log.repairer.first_name + ' ' + log.repairer.last_name.charAt(0) + ' ' + t('messages.is_repairing')"
          :color="statusColor"
          class="ma-2"
          label
        />
        <v-chip v-else-if="needsRepair" :color="statusColor" class="ma-2" label>
          {{ t('messages.you_can_repair') }}
        </v-chip>
        <v-chip v-else-if="!needsRepair && log" :color="statusColor" class="ma-2" label>
          {{ t('messages.repair_finished', { status: repairStatus }) }}
        </v-chip>
      </div>
      <div class="my-4">
        <v-card>
          <v-card-title>
            {{ t('messages.device_details') }}
          </v-card-title>
          <v-image-gallery :images="images" />
          <v-card-text>
            <p>
              <strong>
                {{ t('messages.form_brand_name') }}
              </strong>
              <br />
              {{ device.brand_name }}
            </p>
            <p>
              <strong>
                {{ t('messages.form_model_name') }}
              </strong>
              <br />
              {{ device.model_name }}
            </p>
            <p>
              <strong>
                {{ t('messages.form_device_type') }}
              </strong>
              <br />
              {{ deviceType }}
            </p>
            <p v-if="device.manufacture_year">
              <strong>
                {{ t('messages.form_manufacture_year') }}
              </strong>
              <br />
              {{ device.manufacture_year }}
            </p>
            <p>
              <strong>
                {{ t('messages.form_device_issue') }}
              </strong>
              <br />
              {{ device.issue_description }}
            </p>
          </v-card-text>
          <v-card-actions v-if="repairerAssigned || repairerStarted">
            <v-btn
              v-if="repairerAssigned"
              @click="
                visitRoute('device_start_repair', {
                  slug: device.slug
                })
              "
              class="mx-auto"
              color="primary"
            >
              {{ t('messages.button_repair_start') }}
            </v-btn>
          </v-card-actions>
          <v-card-actions v-if="!repairerAssigned && !repairerStarted && needsRepair">
            <v-btn
              @click="
                visitRoute('device_select_repair', {
                  slug: device.slug
                })
              "
              class="mx-auto"
              color="primary"
            >
              {{ t('messages.button_repair_device') }}
            </v-btn>
          </v-card-actions>
          <v-card-actions v-if="canEdit">
            <v-btn
              @click="
                visitRoute('device_log_repaired_edit', {
                  uuid: log.uuid
                })
              "
              class="mx-auto"
              color="primary"
            >
              {{ t('messages.button_details_edit') }}
            </v-btn>
          </v-card-actions>
        </v-card>
      </div>
      <v-card v-if="!needsRepair || repairerStarted" class="my-2" tile>
        <v-card-title>{{ t('messages.repair_solutions') }}</v-card-title>
        <v-image-gallery :images="repairImages" />
        <v-card-text>
          <p>
            <strong>
              {{ t('messages.log_fixed_by') }}
            </strong>
            <br />
            {{ log.repairer.first_name }}
            {{ log.repairer.last_name }}
          </p>
          <div v-if="repairNotes.length">
            <strong>
              {{ t('messages.form_device_fix') }}
            </strong>
            <div v-for="(note, key) in repairNotes" :key="key" class="my-3">
              <v-card elevation="2">
                <v-card-text>
                  <small>{{ note.created_timestamp }}</small>
                  <br /><span v-html="note.content"></span
                ></v-card-text>
                <v-card-actions>
                  <inertia-link
                    :href="
                      route('device_log_note_edit', {
                        uuid: log.uuid,
                        id: note.id
                      })
                    "
                  >
                    {{ t('messages.edit') }}
                  </inertia-link>
                </v-card-actions>
              </v-card>
            </div>
          </div>
          <v-expansion-panels class="my-2">
            <v-expansion-panel>
              <v-expansion-panel-header>
                {{ t('messages.note_add') }}
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <form @submit.prevent="submit" enctype="multipart/form-data">
                  <v-ckeditor
                    v-model="form.note_content"
                    :label="t('messages.form_device_fix')"
                    :placeholder="t('messages.form_device_fix_placeholder')"
                    :error-messages="getErrorMessages('note_content')"
                    class="mt-6"
                  />
                  <br />
                  <v-btn @click="submit" class="mb-4" color="primary">
                    {{ t('messages.form_add') }}
                  </v-btn>
                </form>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
          <div v-if="barriers">
            <p>
              <strong>{{ t('messages.form_repair_barriers') }}</strong>
              <br />
              {{ barriers }}
            </p>
          </div>
          <div v-if="repairLinks && repairLinks.length">
            <strong>
              {{ t('messages.aide_links') }}
            </strong>
            <ul v-for="(link, key) in repairLinks" :key="key">
              <li>
                <a :href="link.url">{{ link.url }}</a>
              </li>
            </ul>
          </div>
        </v-card-text>
        <v-card-actions v-if="canEdit">
          <v-btn
            @click="
              $inertia.visit('device_log_repaired_edit', {
                uuid: log.uuid
              })
            "
            class="mx-auto"
            color="primary"
          >
            {{ t('messages.button_details_repair_edit') }}
          </v-btn>
        </v-card-actions>
        <v-card-actions v-if="repairerStarted && !canEdit">
          <v-btn @click="visitRoute('device_log_draft', { uuid: log.uuid })" class="mx-auto" color="primary">
            {{ t('messages.button_repair_draft') }}
          </v-btn>
        </v-card-actions>
      </v-card>
      <v-card v-if="repairLogs && repairLogs.length">
        <v-card-title v-if="needsRepair || !repairerStarted">
          {{ t('messages.repair_history') }}
        </v-card-title>
        <div v-for="(log, key) in repairLogs" :key="key">
          <v-image-gallery :images="log.images" />
          <v-card-text>
            <p>
              <strong>{{ t('messages.log_added_at') }}</strong
              ><br />
              {{ log.timestamp }}
            </p>
            <p>
              <strong>
                {{ t('messages.log_fixed_by') }}
              </strong>
              <br />
              {{ log.repairer.first_name }}
              {{ log.repairer.last_name }}
            </p>
            <p v-if="log.diagnosis">
              <strong>
                {{ t('messages.repair_diagnosis') }}
              </strong>
              <br />
              {{ log.diagnosis }}
            </p>
            <p v-if="log.root_cause">
              <strong>
                {{ t('messages.repair_root_cause') }}
              </strong>
              <br />
              {{ log.root_cause }}
            </p>
            <p v-if="log.used_materials">
              <strong>
                {{ t('messages.repair_used_materials') }}
              </strong>
              <br />
              {{ log.used_materials }}
            </p>
            <p v-if="log.used_links">
              <strong>
                {{ t('messages.aide_links') }}
              </strong>
              <br />
              <span v-html="log.used_links">{{ log.used_links }}></span>
            </p>
            <div v-if="log.notes && log.notes.length">
              <strong>
                {{ t('messages.form_device_fix') }}
              </strong>
              <v-expansion-panels class="ma-2">
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ t('messages.view_notes') }}
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <div v-for="(note, key) in log.notes" :key="key" class="my-3">
                      <v-card elevation="2">
                        <v-card-text>
                          <small>{{ note.created_timestamp }}</small>
                          <br />
                          <span v-html="note.content"></span>
                        </v-card-text>
                      </v-card>
                    </div>
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>
            </div>
          </v-card-text>
          <v-divider />
        </div>
      </v-card>
    </v-container>
  </layout-base>
</template>

<script>
import moment from 'moment';

export default {
  props: {
    device: {
      type: Object,
      default: () => null
    },
    deviceType: {
      type: String,
      default: () => null
    },
    barriers: {
      type: String,
      default: () => null
    },
    images: {
      type: Array,
      default: () => null
    },
    log: {
      type: Object,
      default: () => null
    },
    repairImages: {
      type: Array,
      default: () => null
    },
    repairer: {
      type: Object,
      default: () => null
    },
    repairerAssigned: {
      type: Boolean,
      default: false
    },
    repairerStarted: {
      type: Boolean,
      default: false
    },
    needsRepair: {
      type: Boolean,
      default: false
    },
    canEdit: {
      type: Boolean,
      default: false
    },
    repairStatus: {
      type: String,
      default: null
    },
    repairLinks: {
      type: Array,
      default: () => null
    },
    repairNotes: {
      type: Array,
      default: () => null
    },
    status: {
      type: String,
      default: () => null
    },
    repairing: {
      type: String,
      default: () => null
    },
    selected: {
      type: String,
      default: () => null
    },
    repairLogs: {
      type: Array,
      default: () => null
    }
  },
  data() {
    return {
      form: {
        note_content: ''
      }
    };
  },
  computed: {
    statusColor() {
      return `status_${this.log.repair_status.color}`;
    }
  },
  methods: {
    moment() {
      return moment();
    },
    async submit() {
      await this.$inertia.post(
        this.route('device_log_note_add', {
          uuid: this.log.uuid
        }),
        this.createFormData(this.form)
      );

      this.form.note_content = '';
    }
  }
};
</script>
