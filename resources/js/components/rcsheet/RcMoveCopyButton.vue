<template>
  <div>
    <button v-clipboard="() => button">
      {{ buttontext }}
    </button>
    <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
      {{ snackText }}

      <template v-slot:action="{ attrs }">
        <v-btn v-bind="attrs" text @click="snack = false"> Copied </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    item: Object,
    type: String,
  },
  data() {
    return {
      snack: false,
      snackColor: "",
      snackText: "",
    };
  },

  methods: {},

  computed: {
    button() {
      if (this.type == "outtime") {
        var str = this.item.out_time.replace(/\s+/g, "");
        str = str.replace(/[:]/g, "");
        str = str.replace(/[-]/g, "");
        str = str.substring(2);
        this.snack = true;
        this.snackColor = "success";
        this.snackText = "Absolute Time Copied";
        return str;
      }

      if (this.type == "status") {
        this.snack = true;
        this.snackColor = "success";
        this.snackText = "Station Status Copied";

        var ret = this.item.station_status_name.replace("Upcoming - ", "");
        return ret;
      }

      if (this.type == "name") {
        this.snack = true;
        this.snackColor = "success";
        this.snackText = "Station Name Copied";

        return this.item.station_name;
      }

      if (this.type == "corp") {
        this.snack = true;
        this.snackColor = "success";
        this.snackText = "Corp Ticker Copied";

        return this.item.corp_ticker;
      }

      if (this.type == "system") {
        this.snack = true;
        this.snackColor = "success";
        this.snackText = "System Name Copied";

        return this.item.system_name;
      }

      if (this.type == "station") {
        this.snack = true;
        this.snackColor = "success";
        this.snackText = "Station Type Copied";

        return this.item.item_name;
      }

      if (this.type == "alliance") {
        this.snack = true;
        this.snackColor = "success";
        this.snackText = "Alliance ticker Copied";

        return this.item.alliance_ticker;
      }
    },

    buttontext() {
      if (this.type == "outtime") {
        return this.item.out_time;
      }

      if (this.type == "status") {
        var ret = this.item.station_status_name.replace("Upcoming - ", "");
        return ret;
      }

      if (this.type == "name") {
        return this.item.station_name;
      }

      if (this.type == "corp") {
        return this.item.corp_ticker;
      }

      if (this.type == "system") {
        return this.item.system_name;
      }

      if (this.type == "station") {
        return this.item.item_name;
      }

      if (this.type == "alliance") {
        return this.item.alliance_ticker;
      }
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
