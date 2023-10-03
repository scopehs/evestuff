<template>
  <v-card
    tile
    max-width="1500px"
    max-height="1000px"
    min-width="800px"
    class="d-flex flex-column"
  >
    <v-card-title class="d-lg-inline-block justify-space-between align-center">
      <div>
        Logs for <strong>{{ name }}</strong>
      </div>
      <div>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </div>
    </v-card-title>
    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="logging"
        item-key="id"
        disable-pagination
        height="700"
        :sort-by="['created_at']"
        :sort-desc="[true, false]"
        :fixed-header="true"
        hide-default-footer
        :search="search"
        class="elevation-24"
      >
        <template v-slot:[`item.created_at`]="{ item }">
          <div class="subtitle-1">
            {{ item.created_at }}
          </div>
        </template>
        <template v-slot:[`item.logging_type_name`]="{ item }">
          <div class="subtitle-1">
            {{ item.logging_type_name }}
          </div>
        </template>
        <template v-slot:[`item.user_name`]="{ item }">
          <div class="subtitle-1">
            {{ item.user_name }}
          </div>
        </template>
        <template v-slot:[`item.text`]="{ item }">
          <div class="subtitle-1">
            {{ item.text }}
          </div>
        </template>
        <template slot="no-data"> No Logs </template>
      </v-data-table>
    </v-card-text>
    <v-spacer></v-spacer
    ><v-card-actions
      ><v-btn class="white--text" color="teal" @click="close()">
        Close
      </v-btn></v-card-actions
    >
  </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    solaID: Number,
    name: String,
  },
  data() {
    return {
      overlay: true,
      headers: [
        { text: "Time Stamp", value: "created_at" },
        { text: "Type", value: "logging_type_name" },
        { text: "User", value: "user_name" },
        { text: "Text", value: "text" },
        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      search: "",
    };
  },

  methods: {
    close() {
      this.$emit("closeSolaLog", "yo");
    },
  },

  computed: {
    ...mapGetters(["getLoggingCampaignBySola"]),
    logging() {
      if (this.$can("view_campaign_logs")) {
        return this.getLoggingCampaignBySola(this.solaID);
      }
    },
  },
};
</script>

<style></style>
