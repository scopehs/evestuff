<template>
  <div class="d-inline-flex align-items-md-center pl-4">
    <div>
      <span class="d-inline-flex align-items-md-center pr-2">
        <span class="pl-2" v-show="showRcGsolButton()">
          {{ station.gsol.user.name }}
        </span>
      </span>
    </div>
    <div>
      <v-btn
        v-show="!showRcGsolButton()"
        :key="'gunnerbutton' + station.gunner_id"
        class=""
        color="blue"
        x-small
        outlined
        @click="gsolAdd()"
      >
        <font-awesome-icon icon="fa-solid fa-plus" size="2xl" pull="left" />
        GSOL</v-btn
      >
      <v-btn
        icon
        v-show="showRcGsolButton() && $can('edit_killsheet_remove_char')"
        color="orange darken-3"
        @click="gsolRemove()"
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
    showRcGsolButton() {
      if (this.station.gsol) {
        return true;
      } else {
        return false;
      }
    },

    async gsolAdd() {
      var data = {
        id: this.station.id,
        gsol_user_id: this.$store.state.user_id,
        gsol_name: this.$store.state.user_name,
      };
      this.$store.dispatch("updateChillStationCurrent", data);
      this.$store.dispatch("updateWelpStationCurrent", data);
      var data = {
        id: this.station.id,
        gsol: {
          user_id: this.$store.state.user_id,
          user: {
            id: this.$store.state.user_id,
            name: this.$store.state.user_name,
          },
        },
      };

      this.$store.dispatch("updateRcStationCurrent", data);
      var request = null;
      request = {
        user_id: this.$store.state.user_id,
      };

      await axios({
        method: "put",
        url: "/api/rcgsoluseradd/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async gsolRemove() {
      var data = {
        id: this.station.id,
        gsol_user_id: null,
        gsol_name: null,
      };
      this.$store.dispatch("updateChillStationCurrent", data);
      this.$store.dispatch("updateWelpStationCurrent", data);

      var data = {
        id: this.station.id,
        gsol: {
          user_id: this.$store.state.user_id,
          user: {
            id: this.$store.state.user_id,
            name: this.$store.state.user_name,
          },
        },
      };

      this.$store.dispatch("updateRcStationCurrent", data);
      await axios({
        method: "put",
        url: "/api/rcgsoluserremove/" + this.station.id,
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
