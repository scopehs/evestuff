<template>
  <div class="d-inline-flex align-items-md-center pl-4">
    <div>
      <span class="d-inline-flex align-items-md-center pr-2">
        <span class="pl-2" v-show="showRcReconButton()">
          {{ station.recon.user.name }}
        </span>
      </span>
    </div>
    <div>
      <v-btn
        v-show="!showRcReconButton()"
        :key="'gunnerbutton' + station.gunner_id"
        class=""
        color="blue"
        x-small
        outlined
        @click="reconAdd()"
      >
        <font-awesome-icon icon="fa-solid fa-plus" size="2xl" pull="left" />
        CYNO</v-btn
      >
      <v-btn
        icon
        v-show="
          showRcReconButton() &&
          ($can('edit_killsheet_remove_char') ||
            this.station.recon.user.id == this.$store.state.user_id)
        "
        @click="reconRemove()"
        color="orange darken-3 "
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
    showRcReconButton() {
      if (this.station.recon) {
        return true;
      } else {
        return false;
      }
    },

    async reconAdd() {
      var data = {
        id: this.station.id,
        recon_user_id: this.$store.state.user_id,
        recon_name: this.$store.state.user_name,
      };
      this.$store.dispatch("updateChillStationCurrent", data);
      this.$store.dispatch("updateWelpStationCurrent", data);

      var data = {
        id: this.station.id,
        recon: user_id,
        user: {
          id: this.$store.state.user_id,
          name: this.$store.state.user_name,
        },
      };
      this.$store.dispatch("updateRcStationCurrent", data);

      var request = null;
      request = {
        user_id: this.$store.state.user_id,
      };

      await axios({
        method: "put",
        url: "/api/rcreconuseradd/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async reconRemove() {
      var data = {
        id: this.station.id,
        recon_user_id: null,
        recon_name: null,
      };
      this.$store.dispatch("updateChillStationCurrent", data);
      this.$store.dispatch("updateWelpStationCurrent", data);

      var data = {
        id: this.station.id,
        recon: user_id,
        user: {
          id: this.$store.state.user_id,
          name: this.$store.state.user_name,
        },
      };

      this.$store.dispatch("updateRcStationCurrent", data);

      await axios({
        method: "put",
        url: "/api/rcreconuserremove/" + this.station.id,
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
