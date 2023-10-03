<template>
  <div class="d-inline-flex align-items-md-center pl-4">
    <div>
      <span class="d-inline-flex align-items-md-center pr-2">
        <span class="pl-2" v-show="!showGunnerButton">
          {{ gunnerName }}
        </span>
      </span>
    </div>
    <div>
      <v-tooltip
        color="#121212"
        bottom
        :key="'gunnertooltip' + station.gunner_id"
        :open-delay="2000"
        :disabled="$store.state.tooltipToggle"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            v-show="showGunnerButton"
            :key="'gunnerbutton' + station.gunner_id"
            class=""
            color="blue"
            x-small
            outlined
            @click="gunnerAdd()"
            v-bind="attrs"
            v-on="on"
          >
            <font-awesome-icon icon="fa-solid fa-plus" size="2xl" />
            Gunner</v-btn
          >
        </template>
        <span> Gunners can assign themselfs here </span>
      </v-tooltip>
      <v-btn
        icon
        @click="gunnerRemove()"
        v-show="!showGunnerButton"
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
  },
  data() {
    return {
      gunnerName: null,
    };
  },

  watch: {
    station: {
      handler() {
        this.showName;
        this.showGunnerButton;
      },
      deep: true,
    },
  },

  mounted() {
    this.showName;
  },

  methods: {
    async gunnerAdd() {
      var data = {
        id: this.station.id,
        gunner_id: this.$store.state.user_id,
        gunner_name: this.$store.state.user_name,
      };

      this.$store.dispatch("updateStationNotification", data);

      var request = null;
      request = {
        gunner_id: this.$store.state.user_id,
      };

      await axios({
        method: "put",
        url: "/api/updatestationnotification/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async gunnerRemove() {
      var request = {
        gunner_id: null,
      };

      await axios({
        method: "put",
        url: "/api/updatestationnotification/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    showGunnerButton() {
      if (this.station.gunner_id == null) {
        return true;
      } else {
        return false;
      }
    },

    showName() {
      if (this.station.standing > 0) {
        this.gunnerName = this.station.gunner_name;
      } else {
        this.gunnerName = "Has Gunner";
      }
    },
  },
};
</script>

<style></style>
