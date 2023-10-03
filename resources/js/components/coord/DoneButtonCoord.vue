<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      persistent
      v-model="showDoneCoordOverlay"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          small
          v-bind="attrs"
          v-on="on"
          @click="open()"
          :color="pillColor()"
        >
          {{ buttontext() }}

          <font-awesome-icon :icon="icons" pull="right" />
        </v-btn>
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="1000px"
        class="d-flex flex-column justify-center"
      >
        <v-card-title class="justify-center" elevation="24">
          <p>What is the Status of {{ item.station_name }}</p>
        </v-card-title>
      </v-card>
      <v-card>
        <v-card-text class="d-inline-flex justify-center">
          <AddTimerFromDoneCoord
            @timeropen="close()"
            :item="item"
            class="mx-4"
          ></AddTimerFromDoneCoord>
          <v-btn color="orange darken-1" class="mx-4" @click="statusUpdate(16)">
            Online</v-btn
          >
          <v-btn color="red" class="mx-4" @click="destroyed()"> DEAD</v-btn>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn color="red" @click="close()"> Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";

export default {
  props: {
    item: Object,
  },

  async created() {},
  data() {
    return {
      showDoneCoordOverlay: false,
    };
  },

  watch: {},

  methods: {
    pillColor() {
      if (this.item.station_status_id == 4) {
        return "orange darken-1";
      }
      if (this.item.station_status_id == 18) {
        return "brown lighten-2";
      }
      if (this.item.station_status_id == 16) {
        return "green";
      }
      if (this.item.station_status_id == 7) {
        return "red";
      }
    },

    buttontext() {
      var ret = this.item.station_status_name.replace("Upcoming - ", "");
      return ret;
    },

    async open() {},

    close() {
      this.showDoneCoordOverlay = false;
    },

    showAddTimer() {
      if (
        this.item.station_status_id == 5 ||
        this.item.station_status_id == 8
      ) {
        return true;
      } else {
        return false;
      }
    },

    async statusUpdate(statusID) {
      var request = null;
      request = {
        station_status_id: statusID,
        show_on_rc: 0,
        show_on_coord: 1,
      };

      await axios({
        method: "put",
        url: "/api/updatestationnotification/" + this.item.id,
        data: request,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.showDoneCoordOverlay = false;
    },

    async destroyed() {
      await axios({
        method: "delete",
        url: "/api/rcmovedonebad/" + this.item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    icons() {
      if (this.item.station_status_id == 4) {
        return "fa-solid fa-check-circle";
      }
      if (this.item.station_status_id == 18) {
        return "fa-solid fa-circle-question";
      }
      if (this.item.station_status_id == 16) {
        return "fa-solid fa-triangle-exclamation";
      }
      if (this.item.station_status_id == 7) {
        return "fa-solid fa-skull-crossbones";
      }
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
