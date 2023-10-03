<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      persistent
      v-model="showDoneOverlay"
      @click:outside="close()"
      v-if="done"
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
          <font-awesome-icon icon="fa-solid fa-circle-check" />
        </v-btn>
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="1000px"
        class="d-flex flex-column"
      >
        <v-card-title class="justify-center">
          <p>What is the Status of {{ item.name }}</p>
        </v-card-title>

        <v-card-text class="d-inline-flex">
          <AddTimerFromDone
            v-if="showAddTimer()"
            @timeropen="close()"
            :item="item"
            :type="type"
          ></AddTimerFromDone>
          <v-btn color="orange darken-1" class="mx-4" @click="statusUpdate(4)">
            Repaired</v-btn
          >
          <v-btn color="red" class="mx-4" @click="softDestroyed()">
            Destoryed</v-btn
          >
          <v-btn color="brown lighten-2" class="mx-4" @click="statusUpdate(18)">
            Unknown</v-btn
          >
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn color="red" @click="close()"> Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-chip v-else pill small :color="pillColor()">
      {{ buttontext() }}
    </v-chip>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { EventBus } from "../../app";
import moment from "moment";

export default {
  props: {
    item: Object,
    type: Number,
  },

  async created() {
    this.setdone();
    EventBus.$on("timerDone", (data) => {
      if (this.item.id == data) {
        this.done = true;
      }
    });
  },
  data() {
    return {
      showDoneOverlay: false,
      done: 0,
    };
  },

  methods: {
    setdone() {
      var outTime = moment.utc(this.item.out_time);
      var now = moment.utc();
      if (outTime.isAfter(now)) {
        this.done = false;
      } else {
        this.done = true;
      }
    },

    pillColor() {
      if (this.item.station_status_id == 13) {
        return "red darken-4";
      }
      if (this.item.station_status_id == 5) {
        return "lime darken-4";
      }
      if (this.item.station_status_id == 14) {
        return "green accent-4";
      }
      if (this.item.station_status_id == 17) {
        return "#FF5EEA";
      }
    },
    buttontext() {
      var ret = this.item.status.name.replace("Upcoming - ", "");
      return ret;
    },

    async open() {},

    close() {
      this.showDoneOverlay = false;
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
      var data = {
        id: this.item.id,
        show_on_rc: 0,
      };
      this.$store.dispatch("updateChillStationCurrent", data);
      var data = {
        id: this.item.id,
        show_on_welp: 0,
      };
      this.$store.dispatch("updateWelpStationCurrent", data);

      var data = {
        id: this.item.id,
        show_on_rc: 0,
      };
      this.$store.dispatch("updateRcStationCurrent", data);

      var request = null;

      request = {
        station_status_id: statusID,
        show_on_rc: 0,
        show_on_coord: 1,
      };

      await axios({
        method: "put",
        url: "/api/updatestationnotification/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      request = {
        station_status_id: statusID,
        show_on_chill: 0,
        show_on_coord: 1,
      };

      await axios({
        method: "put",
        url: "/api/chillupdatestationnotification/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      request = {
        station_status_id: statusID,
        show_on_welp: 0,
        show_on_coord: 1,
      };

      await axios({
        method: "put",
        url: "/api/welpupdatestationnotification/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async softDestroyed() {
      var data = {
        id: this.item.id,
        show_on_rc: 0,
        show_on_coord: 1,
      };
      this.$store.dispatch("updateChillStationCurrent", data);
      var data = {
        id: this.item.id,
        show_on_welp: 0,
        show_on_coord: 1,
      };

      this.$store.dispatch("updateWelpStationCurrent", data);

      await axios({
        method: "put",
        url: "/api/softdestory/" + this.item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      var data = {
        id: this.item.id,
        show_on_chill: 0,
      };

      await axios({
        method: "delete",
        url: "/api/chilldelete/" + this.item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      var data = {
        id: this.item.id,
        show_on_welp: 0,
      };

      await axios({
        method: "delete",
        url: "/api/welpdelete/" + this.item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {},

  beforeDestroy() {},
};
</script>

<style></style>


