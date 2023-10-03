<template>
  <div class="pr-3 pl-3" v-resize="onResize">
    <v-row no-gutters justify="space-between" align="center">
      <v-col cols="4" align="start">
        <v-card tile flat color="#121212" width="500px">
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            :loading="loadingt"
            filled
            hide-details
          ></v-text-field>
        </v-card>
      </v-col>

      <v-col cols="4" align="center">
        <AddStation :type="3" :type2="0" class="pt-2 pl-2"></AddStation
      ></v-col>
      <v-col cols="4" justify="end" align="end" class="d-inline-flex">
        <v-spacer></v-spacer>
        <AdminLoggingSheet v-if="$can('view_admin_logs')" class="pt-2 pl-2">
        </AdminLoggingSheet>
        <v-card width="150px" min-height="60px">
          <v-switch
            class="pl-2 pr-2 pt-1"
            v-model="toggleFC"
            label="No FC"
            color="pink"
            hide-details
          ></v-switch>
        </v-card>
      </v-col>
    </v-row>
    <v-row no-gutters justify="center">
      <v-col class="d-inline-flex" cols="4">
        <v-card
          max-width="600px"
          min-width="600px"
          color="#121212"
          elevation="0"
        >
          <v-card-text>
            <v-select
              class="pb-2"
              v-model="regionPicked"
              :items="dropdown_region_list"
              label="Filter by Region"
              multiple
              :loading="loadingt"
              chips
              deletable-chips
              hide-details
            ></v-select>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col class="d-inline-flex" cols="4">
        <v-card
          max-width="600px"
          min-width="600px"
          color="#121212"
          elevation="0"
        >
          <v-card-text>
            <v-select
              class="pb-2"
              v-model="itemPicked"
              :items="dropdown_type_list"
              label="Filter by Type"
              :loading="loadingt"
              multiple
              chips
              deletable-chips
              hide-details
            ></v-select>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col class="d-inline-flex" cols="4">
        <v-card
          max-width="600px"
          min-width="600px"
          color="#121212"
          elevation="0"
        >
          <v-card-text>
            <v-select
              class="pb-2"
              v-model="statusPicked"
              :items="dropdown_status_list"
              label="Filter by Status"
              :loading="loadingt"
              multiple
              chips
              deletable-chips
              hide-details
            ></v-select>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row no-gutters justify="center">
      <v-col class="d-inline-flex justify-content-center w-auto" cols="12">
        <v-card width="100%">
          <v-data-table
            :search="search"
            :expanded.sync="expanded"
            :headers="_headers"
            :items="filter_end"
            :loading="loadingt"
            :height="height"
            fixed-header
            id="table"
            item-key="id"
            :sort-by="['out_time']"
            :sort-desc="[false, true]"
            multi-sort
            :items-per-page="50"
            :footer-props="{
              'items-per-page-options': [10, 20, 30, 50, 100, -1],
            }"
            class="elevation-5"
          >
            <template
              v-slot:[`item.corp.alliance.ticker`]="{ item }"
              class="d-inline-flex align-center"
            >
              <div class="d-inline-flex">
                <span v-if="item.corp.alliance.url">
                  <v-avatar size="35"
                    ><img :src="item.corp.alliance.url"
                  /></v-avatar>
                  <span :class="tickColor(item.corp.alliance.standing)"
                    >{{ item.corp.alliance.ticker }}
                  </span>
                </span>
              </div>
            </template>

            <template
              v-slot:[`item.corp.ticker`]="{ item }"
              class="d-inline-flex align-center"
            >
              <div class="d-inline-flex">
                <span v-if="item.corp.url">
                  <v-avatar size="35"><img :src="item.corp.url" /></v-avatar>
                  <span :class="tickColor(item.corp.standing)"
                    >{{ item.corp.ticker }}
                  </span>
                </span>
              </div>
            </template>
            <template v-slot:[`item.name`]="{ item }">
              <span> {{ item.name }}</span>
            </template>
            <template v-slot:[`item.out_time`]="{ item }">
              <span> {{ item.out_time }}</span>
            </template>
            <template
              v-slot:[`item.system.system_name`]="{ item }"
              class="d-inline-flex align-center"
            >
              <div class="d-inline-flex">
                <v-btn :href="link(item)" target="_blank" icon color="green">
                  <font-awesome-icon icon="fa-solid fa-map" size="2xl" />
                </v-btn>
                <SoloCampaginWebWay
                  v-if="item.system.webway[0]"
                  :jumps="item.system.webway[0].jumps"
                  :web="item.system.webway[0].webway"
                ></SoloCampaginWebWay>
                <button
                  v-clipboard="item.system.system_name"
                  v-clipboard:success="Systemcopied"
                >
                  <span class="pt-2 caption">
                    {{ item.system.system_name }}</span
                  >
                </button>
              </div>
            </template>
            <template v-slot:[`item.count`]="{ item }">
              <RcTimer :station="item" @timerdone="timerDone()"></RcTimer>
            </template>
            <template v-slot:[`item.fc.user.name`]="{ item }">
              <RcFCButton
                class="mr-2"
                :station="item"
                v-if="showFC(item)"
                :type="1"
              ></RcFCButton>
              <RcFCAdd
                v-if="!item.rc_fc_id && $can('view_killsheet_add_fc')"
                :station="item"
                class="pl-6"
                :type="1"
              ></RcFCAdd>
            </template>

            <template v-slot:[`item.system.region.region_name`]="{ item }">
              <span> {{ item.system.region.region_name }}</span>
            </template>

            <template
              v-slot:[`item.system.constellation.constellation_name`]="{ item }"
            >
              <span> {{ item.system.constellation.constellation_name }}</span>
            </template>

            <template v-slot:[`item.recon.user.name`]="{ item }">
              <RcReconButton
                class="mr-2"
                :station="item"
                :type="1"
              ></RcReconButton>
            </template>

            <template v-slot:[`item.status.name`]="{ item }">
              <DoneButton :item="item" :type="1"></DoneButton>

              <!-- <v-chip pill small :color="pillColor(item)">
                {{ buttontext(item) }}
              </v-chip> -->
            </template>

            <template v-slot:[`item.gsol.user.name`]="{ item }">
              <RcGsolButton
                class="mr-2"
                :station="item"
                :type="1"
              ></RcGsolButton>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <div class="d-inline-flex">
                <RcStationMessage
                  class="mr-2"
                  :station="item"
                  :type="1"
                ></RcStationMessage>
                <div>
                  <Info :station="item" v-if="showInfo(item)"></Info>
                </div>
                <div v-if="$can('view_station_logs')">
                  <v-btn
                    @click="(expanded = [item]), (expanded_id = item.id)"
                    v-show="!expanded.includes(item)"
                    icon
                    class="pb-3"
                    color="blue"
                  >
                    <font-awesome-icon icon="fa-solid fa-clock-rotate-left" />
                  </v-btn>
                  <v-btn
                    @click="(expanded = []), (expanded_id = 0)"
                    v-show="expanded.includes(item)"
                    icon
                    class="pb-3"
                    color="error"
                  >
                    <font-awesome-icon icon="fa-solid fa-clock-rotate-left" />
                  </v-btn>
                </div>
              </div>
            </template>
            <template
              v-slot:expanded-item="{ headers, item }"
              inset
              class="align-center"
              height="100%"
            >
              <td :colspan="headers.length" align="center">
                <StationLogs :station="item"></StationLogs>
              </td>
            </template>
            <template slot="no-data">
              No Active or Upcoming Campaigns
            </template>
          </v-data-table>
        </v-card>
      </v-col>
      <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
        {{ snackText }}

        <template v-slot:action="{ attrs }">
          <v-btn v-bind="attrs" text @click="snack = false"> Copied </v-btn>
        </template>
      </v-snackbar>
    </v-row>
    <!-- <v-row v-if="$can('super')" align="center" juvvvstify="center">
            <v-subheader>Window Size</v-subheader>
            {{ windowSize }} abd {{ highthtest }}
        </v-row> -->
  </div>
</template>
<script>
import Axios from "axios";
import moment, { now, utc } from "moment";
import { EventBus } from "../app";
import { stringify } from "querystring";

import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `EveStuff - Nats Health`;
  },
  data() {
    return {
      regionPicked: [],
      itemPicked: [],
      statusPicked: [],
      search: "",
      toggleFC: false,
      logs: false,
      snack: false,
      snackColor: "",
      snackText: "",
      loadingt: true,
      windowSize: {
        x: 0,
        y: 0,
      },
      expanded: [],
      expanded_id: 0,
    };
  },

  async created() {
    if (this.$can("super")) {
      await this.$store.dispatch("getAllianceTickList");
      await this.$store.dispatch("getTickList");
    }

    if (this.$can("view_station_info_killsheet")) {
      await this.$store.dispatch("loadStationInfo");
    }
    if (this.$can("view_station_logs")) {
      this.$store.dispatch("getLoggingStations");
      Echo.private("stationlogs").listen("StationLogUpdate", (e) => {
        if (e.flag.message != null) {
          this.$store.dispatch("addLoggingStation", e.flag.message);
        }
      });
    }
    await this.$store.dispatch("getRcRegions");
    await this.$store.dispatch("getRcStationRecords");
    await this.$store.dispatch("getRcFcs");
    await this.$store.dispatch("getRcItems");
    await this.$store.dispatch("getRcStatus");
    this.loadingt = false;
    Echo.private("rcsheet").listen("RcSheetUpdate", (e) => {
      if (e.flag.message != null) {
        this.$store.dispatch("updateRcStation", e.flag.message);
      }

      if (e.flag.flag == 2) {
        this.freshUpdate();
      }

      if (e.flag.flag == 3) {
        this.fcupdate();
      }

      if (e.flag.flag == 4) {
        this.sheetupdate();
      }
    });

    if (this.$can("view_admin_logs")) {
      this.$store.dispatch("getLoggingRcSheet");
      Echo.private("rcsheetadminlogs").listen("RcSheetAddLogging", (e) => {
        this.$store.dispatch("addLoggingRcSheet", e.flag.message);
      });
    }
  },

  async mounted() {
    this.log();
    this.onResize();
  },

  methods: {
    timerDone() {
      bus.$emit("customEventName", optionalParameter);
    },
    log() {
      var request = {
        url: this.$route.path,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/url",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    link(item) {
      if (item.system.region.region_name == "Black Rise") {
        return (
          "https://evemaps.dotlan.net/map/Black_Rise/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "The Bleak Lands") {
        return (
          "https://evemaps.dotlan.net/map/The_Bleak_Lands/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "The Citadel") {
        return (
          "https://evemaps.dotlan.net/map/The_Citadel/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Cloud Ring") {
        return (
          "https://evemaps.dotlan.net/map/Cloud_Ring/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Cobalt Edge") {
        return (
          "https://evemaps.dotlan.net/map/Cobalt_Edge/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Etherium Reach") {
        return (
          "https://evemaps.dotlan.net/map/Etherium_Reach/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "The Forge") {
        return (
          "https://evemaps.dotlan.net/map/The_Forge/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "The Kalevala Expanse") {
        return (
          "https://evemaps.dotlan.net/map/The_Kalevala_Expanse/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Molden Heath") {
        return (
          "https://evemaps.dotlan.net/map/Molden_Heath/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Outer Passage") {
        return (
          "https://evemaps.dotlan.net/map/Outer_Passage/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Outer Ring") {
        return (
          "https://evemaps.dotlan.net/map/Outer_Ring/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Paragon Soul") {
        return (
          "https://evemaps.dotlan.net/map/Paragon_Soul/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Period Basis") {
        return (
          "https://evemaps.dotlan.net/map/Period_Basis/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Perrigen Falls") {
        return (
          "https://evemaps.dotlan.net/map/Perrigen_Falls/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Pure Blind") {
        return (
          "https://evemaps.dotlan.net/map/Pure_Blind/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Scalding Pass") {
        return (
          "https://evemaps.dotlan.net/map/Scalding_Pass/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Sinq Laison") {
        return (
          "https://evemaps.dotlan.net/map/Sinq_Laison/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "The Spire") {
        return (
          "https://evemaps.dotlan.net/map/The_Spire/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Vale of the Silent") {
        return (
          "https://evemaps.dotlan.net/map/Vale_of_the_Silent/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Verge Vendor") {
        return (
          "https://evemaps.dotlan.net/map/Verge_Vendor/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Wicked Creek") {
        return (
          "https://evemaps.dotlan.net/map/Wicked_Creek/" +
          item.system.system_name +
          "#const"
        );
      }
      return (
        "https://evemaps.dotlan.net/map/" +
        item.system.region.region_name +
        "/" +
        item.system.system_name +
        "#const"
      );
    },

    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    async freshUpdate() {
      await this.$store.dispatch("getRcRegions");
      await this.$store.dispatch("getRcStationRecords");
      await this.$store.dispatch("getRcFcs");
      await this.$store.dispatch("getRcItems");
      await this.$store.dispatch("getRcStatus");
    },

    async fcupdate() {
      await this.$store.dispatch("getRcFcs");
    },

    async sheetupdate() {
      await this.$store.dispatch("getRcStationRecords");
    },

    numberDay(day) {
      return parseInt(day, 10) + "d";
    },

    async stationdone(item) {
      await axios({
        method: "put",
        url: "/api/finishrcstation/" + item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    Systemcopied() {
      this.snack = true;
      this.snackColor = "success";
      this.snackText = "System Copied";
    },

    showFC(item) {
      if (item.station_status_id == 540) {
        return false;
      }
      return true;
    },
    //
    showInfo(item) {
      if (this.$can("view_station_info_killsheet")) {
        if (
          item.item_id == 37534 ||
          item.item_id == 35841 ||
          item.item_id == 35840
        ) {
          return false;
        }
        if (item.fit) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    },

    tickColor(num) {
      if (num > 0) {
        return "blue--text pl-3";
      } else {
        return "red--text pl-3";
      }
    },
  },

  computed: {
    ...mapState([
      "rcstations",
      "rcsheetRegion",
      "rcsheetItem",
      "rcsheetStatus",
    ]),

    ...mapGetters(["getActiveRcStations"]),
    filteredItems() {
      //   return this.rcstations.filter(f => f.show_on_rc == 1);
      return this.getActiveRcStations;
    },

    filter_fc() {
      if (this.toggleFC) {
        return this.filteredItems.filter((s) => s.rc_fc_id == null);
      } else {
        return this.filteredItems;
      }
    },

    height() {
      let num = this.windowSize.y - 375;
      return num;
    },

    filter_start() {
      let data = [];
      if (this.statusPicked.length != 0) {
        this.statusPicked.forEach((p) => {
          let pick = this.filter_fc.filter((f) => f.station_status_id == p);
          if (pick != null) {
            pick.forEach((pk) => {
              data.push(pk);
            });
          }
        });
        return data;
      }
      return this.filter_fc;
    },

    filter_mid() {
      let data = [];
      if (this.itemPicked.length != 0) {
        this.itemPicked.forEach((p) => {
          let pick = this.filter_start.filter((f) => f.item_id == p);
          if (pick != null) {
            pick.forEach((pk) => {
              data.push(pk);
            });
          }
        });
        return data;
      }
      return this.filter_start;
    },

    filter_end() {
      let data = [];
      if (this.regionPicked.length != 0) {
        this.regionPicked.forEach((p) => {
          let pick = this.filter_mid.filter((f) => f.system.region.id == p);
          if (pick != null) {
            pick.forEach((pk) => {
              data.push(pk);
            });
          }
        });
        return data;
      }
      return this.filter_mid;
    },

    dropdown_region_list() {
      return this.rcsheetRegion;
    },

    dropdown_type_list() {
      return this.rcsheetItem;
    },

    dropdown_status_list() {
      return this.rcsheetStatus.filter((l) => l.text != null);
    },

    _headers() {
      if (this.$can("view_gsol_killsheet")) {
        var Headers = [
          { text: "System", value: "system.system_name" },
          { text: "Const", value: "system.constellation.constellation_name" },
          { text: "Region", value: "system.region.region_name" },
          { text: "Name", value: "name" },
          { text: "Type", value: "item.item_name" },
          { text: "Status", value: "status.name", align: "center" },
          { text: "Ticker", value: "corp.alliance.ticker" },
          { text: "Expires", value: "out_time" },
          { text: "CountDown", value: "count", sortable: false },
          { text: "FC", value: "fc.user.name", align: "center" },
          { text: "Cyno", value: "recon.user.name" },
          { text: "GSOL", value: "gsol.user.name" },
          { text: "", value: "actions" },
        ];
      } else {
        var Headers = [
          { text: "System", value: "system.system_name" },
          { text: "Const", value: "system.constellation.constellation_name" },
          { text: "Region", value: "system.region.region_name" },
          { text: "Name", value: "name" },
          { text: "Type", value: "item.item_name" },
          { text: "Status", value: "status.name", align: "center" },
          { text: "Ticker", value: "corp.alliance.ticker" },
          { text: "Expires", value: "out_time" },
          { text: "CountDown", value: "count", sortable: false },
          { text: "FC", value: "fc.user.name", align: "center" },
          { text: "Cyno", value: "recon.user.name" },
          { text: "", value: "actions" },
        ];
      }

      return Headers;
    },
  },
  beforeDestroy() {
    Echo.leave("rcsheet");
    Echo.leave("stationlogs");
  },
};
</script>
<style scoped></style>
