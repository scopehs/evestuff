<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      v-model="showStationTimer"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-chip
          color="green"
          pill
          outlined
          dark
          small
          v-bind="attrs"
          v-on="on"
          @click="open()"
        >
          Add Timer
        </v-chip>
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="1000px"
        class="d-flex flex-column"
      >
        <v-card-title class="justify-center">
          Enter Timer for {{ station.station_name }}
        </v-card-title>
        <v-card-text>
          <v-fade-transition>
            <div>
              <div>
                <v-text-field
                  v-model="stationName"
                  label="Structure Type"
                  readonly
                ></v-text-field>
              </div>
              <div class="d-inline-flex justify-content-around">
                <v-text-field
                  v-model="systemName"
                  label="System Name"
                  readonly
                ></v-text-field>
                <v-text-field
                  v-model="alliance_ticker"
                  label="Alliance Ticker"
                  readonly
                ></v-text-field>
              </div>
              <div>
                <h5><strong>Timer Type</strong></h5>
                <v-radio-group v-model="refType" row>
                  <v-radio label="Anchoring" value="14"></v-radio>
                  <v-radio label="Armor" value="5"></v-radio>
                  <v-radio label="Hull" value="13"></v-radio>
                </v-radio-group>
              </div>
              <div>
                <v-text-field
                  v-model="refTime"
                  label="Ref Time d hh:mm:ss"
                  v-mask="'#d ##:##:##'"
                  placeholder="d:hh:mm:ss"
                  @keyup.enter="(timerShown = false), addHacktime()"
                  @keyup.esc="(timerShown = false), (hackTime = null)"
                ></v-text-field>
              </div>
            </div>
          </v-fade-transition>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn>
          <v-btn
            class="white--text"
            color="green"
            :disabled="showSubmit"
            @click="submit()"
          >
            Submit
          </v-btn></v-card-actions
        >
      </v-card>

      <!-- <showStationTimer
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showStationTimer = false"
            >
            </showStationTimer> -->
    </v-dialog>
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
      showStationTimer: false,
      stationName: null,
      refType: null,
      refTime: {
        d: "",
        hh: "",
        mm: "",
        ss: "",
      },
      systemName: null,
      alliance_ticker: null,
    };
  },

  watch: {
    station: {
      handler() {
        this.showPannel;
      },
      deep: true,
    },
  },

  mounted() {
    this.setValues();
  },

  methods: {
    setValues() {
      this.systemName = this.station.system_name;
      this.alliance_ticker = this.station.alliance_ticker;
      this.stationName = this.station.station_name;
    },

    close() {
      this.refType = null;
      this.refTime = null;
      this.showStationTimer = false;
    },

    async submit() {
      var d = parseInt(this.refTime.substr(0, 1));
      var h = parseInt(this.refTime.substr(3, 2));
      var m = parseInt(this.refTime.substr(6, 2));
      var s = parseInt(this.refTime.substr(9, 2));
      var ds = d * 24 * 60 * 60;
      var hs = h * 60 * 60;
      var ms = m * 60;
      var sec = ds + hs + ms + s;
      var outTime = moment
        .utc()
        .add(sec, "seconds")
        .format("YYYY-MM-DD HH:mm:ss");

      var request = {
        station_status_id: this.refType,
        out_time: outTime,
        show_on_coord: 0,
        status_update: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "api/updatestationnotification/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then(
        (this.refType = null),
        (this.refTime = null),
        (this.showStationTimer = false)
      );
    },

    async open() {},
  },

  computed: {
    ...mapGetters([]),
    ...mapState([]),

    showPannel() {
      if (
        this.station.out_time == null &&
        (this.station.station_status_id == 8 ||
          this.station.station_status_id == 9 ||
          this.station.station_status_id == 14) &&
        this.$can("edit_notifications")
      ) {
        this.showStationTimer = true;
      } else {
        this.showStationTimer = false;
      }
    },

    showSubmit() {
      if (this.refType != null && this.refTime != null) {
        return false;
      } else {
        return true;
      }
    },
  },

  beforeDestroy() {},
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
