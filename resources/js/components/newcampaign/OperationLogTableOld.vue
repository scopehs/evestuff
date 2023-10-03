<template>
  <v-col cols="12">
    <v-card tile>
      <v-card-title class="d-flex justify-space-between align-center">
        <div>Log for this Operation</div>
      </v-card-title>
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="filteredItems"
          item-key="id"
          disable-pagination
          fixed-header
          :height="height"
          hide-default-footer
          :sort-by="['created_at']"
          :sort-desc="[true, false]"
          class="elevation-24"
          dense
        >
          <template slot="no-data"> No one is here </template>
          <!-- <template v-slot:[`item.created_at`]="{ item }">
            <div class="subtitle-1">
              {{ timestamp(item.created_at) }}
            </div>
          </template>
          <template v-slot:[`item.text`]="{ item }">
            <NewCampaignLogText :item="item" />
          </template> -->
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-col>
</template>

<script>
import moment from "moment";
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: { windowSize: Object },
  data() {
    return {
      headers: [
        { text: "Type", value: "log_name" },
        { text: "Event", value: "description" },
        { text: "Details", value: "text" },
        { text: "By", value: "causer.name" },
        { text: "Date", value: "created_at" },
        { text: "", value: "actions" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
    };
  },

  methods: {
    timestamp(time) {
      return moment.utc(time).format("YYYY-MM-DD HH:mm:ss");
    },
  },

  computed: {
    ...mapState(["loggingNewCampaign"]),

    filteredItems() {
      return this.loggingNewCampaign;
    },

    height() {
      let num = this.windowSize.y - 629;
      return num;
    },
  },
};
</script>

<style></style>
