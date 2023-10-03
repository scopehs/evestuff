<template>
  <div>
    <v-dialog
      max-width="700px"
      persistent
      z-index="0"
      v-model="showStationTimer"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          icon
          color="cyan darken-1"
          dark
          v-bind="attrs"
          v-on="on"
          @click="open()"
        >
          <font-awesome-icon icon="fa-solid fa-pen-to-square"
        /></v-btn>
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="1000px"
        class="d-flex flex-column"
      >
        <v-card-title class="justify-center">
          <p>Edit Details for {{ item.station_name }}</p>
          <p>ONLY ENTER WHAT NEEDS CHANGED</p>
          <p></p
        ></v-card-title>

        <v-card-text>
          <v-fade-transition>
            <div>
              <div>
                <v-text-field
                  v-model="stationName"
                  :loading="structLoading"
                  label="Station Name"
                  clearable
                  outlined
                ></v-text-field>
              </div>
              <div>
                <v-autocomplete
                  v-model="structSelect"
                  :loading="structLoading"
                  :items="structItems"
                  :search-input.sync="structSearch"
                  clearable
                  label="Structure Type"
                  outlined
                ></v-autocomplete>
              </div>
              <div class="d-inline-flex justify-content-around">
                <v-autocomplete
                  v-model="sysSelect"
                  :loading="sysLoading"
                  clearable
                  :items="sysItems"
                  :search-input.sync="sysSearch"
                  label="System Name"
                  outlined
                ></v-autocomplete>
                <v-autocomplete
                  class="ml-2"
                  v-model="tickSelect"
                  :loading="tickLoading"
                  clearable
                  :items="tickItems"
                  :search-input.sync="tickSearch"
                  label="Corp Ticker"
                  outlined
                ></v-autocomplete>
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
                <h5><strong>Image Link 2</strong></h5>
                <v-img src="../image/info.png"> </v-img>
                <v-text-field
                  v-model="imageLink"
                  label="Selected Items Screen Shot"
                ></v-text-field>
              </div>
              <div>
                <h5><strong>Station Timer</strong></h5>
                <v-text-field
                  v-model="refTime"
                  label="Reinforced unit YYYY.MM.DD hh:mm:ss"
                  v-mask="'####.##.## ##:##:##'"
                  placeholder="YYYY.MM.DD HH:mm:ss"
                  @keyup.enter="(timerShown = false), addHacktime()"
                  @keyup.esc="(timerShown = false), (hackTime = null)"
                ></v-text-field>
                <v-alert
                  :value="showWarning"
                  dark
                  type="warning"
                  border="top"
                  icon="mdi-home"
                  transition="scale-transition"
                >
                  <span class="text-center">
                    TIMER IS NOT VAILD OR INCORRECT FORMAT
                  </span>
                </v-alert>
              </div>
            </div>
          </v-fade-transition>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn>
          <v-btn class="white--text" color="green" @click="submit()">
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
    item: Object,
  },

  async created() {},
  data() {
    return {
      imageLink: null,
      showStationTimer: false,
      systems: [],
      sysItems: [],
      sysLoading: false,
      sysSearch: null,
      sysSelect: null,
      stationName: null,
      structItems: [],
      structSearch: null,
      structSelect: null,
      structLoading: false,
      tickItems: [],
      tickSearch: null,
      tickSelect: null,
      tickLoading: false,
      refType: null,
      refTime: {
        d: "",
        hh: "",
        mm: "",
        ss: "",
      },
    };
  },

  watch: {
    sysSearch(val) {
      val && val !== this.sysSelect && this.sysQuerySelections(val);
    },

    tickSearch(val) {
      val && val !== this.tickSelect && this.tickQuerySelections(val);
    },

    structSearch(val) {
      val && val !== this.structSelect && this.structQuerySelections(val);
    },
  },

  methods: {
    tickQuerySelections(v) {
      this.tickLoading = true;
      // Simulated ajax query
      setTimeout(() => {
        this.tickItems = this.tickList.filter((e) => {
          return (
            (e.text || "").toLowerCase().indexOf((v || "").toLowerCase()) > -1
          );
        });
        this.tickLoading = false;
      }, 500);
    },

    structQuerySelections(v) {
      this.structLoading = true;
      // Simulated ajax query
      setTimeout(() => {
        this.structItems = this.structureList.filter((e) => {
          return (
            (e.text || "").toLowerCase().indexOf((v || "").toLowerCase()) > -1
          );
        });
        this.structLoading = false;
      }, 500);
    },

    sysQuerySelections(v) {
      this.sysLoading = true;
      // Simulated ajax query
      setTimeout(() => {
        this.sysItems = this.systemList.filter((e) => {
          return (
            (e.text || "").toLowerCase().indexOf((v || "").toLowerCase()) > -1
          );
        });
        this.sysLoading = false;
      }, 500);
    },

    close() {
      this.showStationTimer = false;
      this.refType = null;
      this.refTime = null;
      this.structItems = [];
      this.structSearch = null;
      this.structSelect = null;
      this.sysItems = [];
      this.sysSearch = null;
      this.sysSelect = null;
      this.systems = [];
      this.tickItems = [];
      this.tickSearch = null;
      this.tickSelect = null;
      this.stationName = null;
      this.state = 1;
      this.sysLoading = false;
      this.showStationTimer = false;
      this.imageLink = null;
    },

    async submit() {
      var outTime = null;
      var editText = "Edited Timer";

      editText = editText + "\n";
      if (this.notes == null) {
        var note =
          moment.utc().format("HH:mm:ss") +
          " - " +
          this.$store.state.user_name +
          ": " +
          editText;
      } else {
        var note =
          moment.utc().format("HH:mm:ss") +
          " - " +
          this.$store.state.user_name +
          ": " +
          editText +
          this.station.notes;
      }

      if (this.vaildDate == true) {
        var full = this.refTime.replace(".", "-");
        full = full.replace(".", "-");
        var outTime = moment(full).format("YYYY-MM-DD HH:mm:ss");
      }

      if (this.sysSelect != null && this.sysSelect != this.item.system_id) {
        var system_id = this.sysSelect;
      } else {
        var system_id = this.item.system_id;
      }

      if (this.tickSelect != null && this.tickSelect != this.item.corp_id) {
        var corp_id = this.tickSelect;
      } else {
        var corp_id = this.item.corp_id;
      }

      if (this.structSelect != null && this.structSelect != this.item.item_id) {
        var item_id = this.structSelect;
      } else {
        var item_id = this.item.item_id;
      }

      if (this.refType != null && this.refType != this.item.station_status_id) {
        var station_status_id = this.refType;
      } else {
        var station_status_id = this.item.station_status_id;
      }

      if (
        this.imageLink != null &&
        this.imageLink != this.item.timer_image_link
      ) {
        var timer_image_link = this.imageLink;
      } else {
        var timer_image_link = this.item.timer_image_link;
      }

      if (
        this.stationName != null &&
        this.stationName != this.item.station_name
      ) {
        var name = this.stationName;
      } else {
        var name = this.stationName;
      }

      if (this.vaildDate == true) {
        var request = {
          system_id: system_id,
          corp_id: corp_id,
          item_id: item_id,
          station_status_id: station_status_id,
          out_time: outTime,
          timer_image_link: timer_image_link,
          notes: note,
        };
      } else {
        var request = {
          system_id: system_id,
          corp_id: corp_id,
          item_id: item_id,
          station_status_id: station_status_id,
          timer_image_link: timer_image_link,
          notes: note,
        };
      }

      await axios({
        method: "put", //you can set what request you want to be
        url: "api/updatetimerinfo/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      if (this.stationName != null || this.stationName == "") {
        var request = {
          stationName: name,
          show_on_rc_move: 0,
        };

        await axios({
          method: "put", //you can set what request you want to be
          url: "api/editstationname/" + this.item.id,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }

      (this.showStationTimer = false),
        (this.refType = null),
        (this.refTime = null),
        (this.structItems = []),
        (this.structSearch = null),
        (this.structSelect = null),
        (this.sysItems = []),
        (this.sysSearch = null),
        (this.sysSelect = null),
        (this.systems = []),
        (this.tickItems = []),
        (this.tickSearch = null),
        (this.tickSelect = null),
        (this.state = 1),
        (this.showStationTimer = false);
    },

    async open() {
      await this.$store.dispatch("getSystemList");
      await this.$store.dispatch("getTickList");
      await this.$store.dispatch("getStructureList");
    },
  },

  computed: {
    ...mapGetters([]),
    ...mapState(["systemlist", "ticklist", "structurelist"]),

    systemList() {
      return this.systemlist;
    },

    stationReadonly() {
      if (this.state == 1) {
        return false;
      } else {
        return true;
      }
    },
    stationOutlined() {
      if (this.state == 1) {
        return true;
      } else {
        return false;
      }
    },

    structureList() {
      return this.structurelist;
    },

    stationLable() {
      if (this.state == 1) {
        return "Enter FULL Structure Name here";
      } else {
        return "";
      }
    },

    count() {
      if (this.refTime != null) {
        return this.refTime.length;
      } else {
        return 0;
      }
    },

    vaildDate() {
      if (this.count == 19) {
        var full = this.refTime.replace(".", "-");
        full = full.replace(".", "-");
        var vaild = moment(full).format("YYYY-MM-DD HH:mm:ss", true);
        if (vaild == "Invalid date") {
          return false;
        } else {
          if (vaild > moment.utc().format("YYYY-MM-DD HH:mm:ss")) {
            return true;
          } else {
            return false;
          }
        }
      } else {
        return false;
      }
    },

    showWarning() {
      if (this.count == 19 && this.vaildDate == false) {
        return true;
      } else {
        return false;
      }
    },

    tickList() {
      return this.ticklist;
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
