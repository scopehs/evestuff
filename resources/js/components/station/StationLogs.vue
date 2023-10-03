<template>
  <div class="pr-16 pl-16 pt-3 pb-3">
    <div class="d-flex align-items-center"></div>
    <v-data-table
      :headers="headers"
      :items="filteredItems"
      item-key="id"
      :items-per-page="10"
      class="elevation-24"
      :sort-by="['created_at']"
      :sort-desc="[true, false]"
    >
      >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Logs for {{ station.name }}</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
      </template>

      <template slot="no-data"> No logs for this </template>
    </v-data-table>
  </div>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  props: {
    station: Object,
  },
  title() {
    return `EveStuff - Chilled Timers`;
  },
  data() {
    return {
      headers: [
        {
          text: "Logs",
          value: "text",
          width: "80%",
        },
        {
          text: "Date",
          value: "created_at",
          align: "end",
        },
      ],
    };
  },

  async created() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapGetters(["getStationLogsByID"]),

    filteredItems() {
      return this.getStationLogsByID(this.station.id);
    },
  },
  beforeDestroy() {},
};
</script>

<style></style>
