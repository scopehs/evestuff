<template>
  <div class="d-inline-flex align-items-md-center pl-4">
    <div v-if="showRcFCButton()">
      <span class="d-inline-flex align-items-md-center pr-2">
        <span class="pl-2">
          {{ station.fc.user.name }}
        </span>
      </span>
    </div>
    <div>
      <v-btn
        v-if="!showRcFCButton()"
        class=""
        color="blue"
        x-small
        outlined
        @click="fcAdd()"
      >
        <font-awesome-icon icon="fa-solid fa-plus" size="2xl" pull="left" />
        FC</v-btn
      >
      <v-btn
        @click="fcRemove()"
        icon
        v-if="
          showRcFCButton() &&
          ($can('edit_killsheet_remove_char') ||
            this.station.fc.user.id == this.$store.state.user_id)
        "
        color="orange darken-3"
      >
        <font-awesome-icon icon="fa-solid fa-trash-can"
      /></v-btn>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    station: Object,
    type: Number,
  },
  data() {
    return {};
  },

  mounted() {
    this.showName;
  },

  methods: {
    showRcFCButton() {
      if (this.station.fc) {
        return true;
      } else {
        return false;
      }
    },

    async fcAdd() {
      var data = {
        id: this.station.id,
        fc_user_id: this.$store.state.user_id,
        fc_name: this.$store.state.user_name,
      };

      this.$store.dispatch("updateChillStationCurrent", data);
      this.$store.dispatch("updateWelpStationCurrent", data);

      var request = null;
      request = {
        user_id: this.$store.state.user_id,
      };

      await axios({
        method: "put",
        url: "/api/rcfcuseradd/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async fcRemove() {
      var data = {
        id: this.station.id,
        fc_user_id: null,
        fc_name: null,
      };
      this.$store.dispatch("updateChillStationCurrent", data);
      this.$store.dispatch("updateWelpStationCurrent", data);

      await axios({
        method: "put",
        url: "/api/rcfcuserremove/" + this.station.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {},
};
</script>

<style></style>
