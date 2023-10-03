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
        <AddStation
          v-if="$can('edit_chill_timers')"
          :type="2"
          :type2="1"
        ></AddStation
      ></v-col>
      <v-col cols="4" justify="end" align="end" class="d-inline-flex">
        <v-spacer></v-spacer>
        <!-- <AdminLoggingSheet
                    v-if="$can('view_admin_logs')"
                    class="pt-2 pl-2"
                >
                </AdminLoggingSheet> -->
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
            :sort-by="['end_time']"
            :sort-desc="[false, true]"
            multi-sort
            :items-per-page="50"
            :footer-props="{
              'items-per-page-options': [10, 20, 30, 50, 100, -1],
            }"
            class="elevation-5"
          >
            <template
              v-slot:[`item.alliance_ticker`]="{ item }"
              class="d-inline-flex align-center"
            >
              <div class="d-inline-flex">
                <span v-if="item.url">
                  <v-avatar size="35"><img :src="item.url" /></v-avatar>
                  <span class="red--text pl-3"
                    >{{ item.alliance_ticker }}
                  </span>
                </span>
                <!-- <span v-else-if="$can('super')">
                                    <AddCorpTicker
                                        :station="item"
                                    ></AddCorpTicker
                                    ><AddAllianceTicker
                                        :station="item"
                                    ></AddAllianceTicker>
                                </span> -->
              </div>
            </template>
            <template v-slot:[`item.name`]="{ item }">
              <span> {{ item.name }}</span>
            </template>
            <template v-slot:[`item.end_time`]="{ item }">
              <span> {{ item.end_time }}</span>
            </template>
            <template
              v-slot:[`item.system_name`]="{ item }"
              class="d-inline-flex align-center"
            >
              <div class="d-inline-flex">
                <v-btn :href="link(item)" target="_blank" icon color="green">
                  <font-awesome-icon icon="fa-solid fa-map" size="2xl" />
                </v-btn>
                <button
                  v-clipboard="item.system_name"
                  v-clipboard:success="Systemcopied"
                >
                  <span class="pt-2 caption"> {{ item.system_name }}</span>
                </button>
              </div>
            </template>
            <template v-slot:[`item.count`]="{ item }">
              <CountDowntimer
                v-if="showCountDown(item)"
                :start-time="countDownStartTime(item)"
                :end-text="'OUT'"
                :interval="1000"
                :day-text="'Days'"
                @campaignStart="campaignStart(item)"
              >
                <template slot="countdown" slot-scope="scope">
                  <span v-if="scope.props.days == 0"
                    >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                      scope.props.seconds
                    }}</span
                  >
                  <span v-if="scope.props.days != 0"
                    >{{ numberDay(scope.props.days) }}
                    {{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                      scope.props.seconds
                    }}</span
                  >
                </template>
              </CountDowntimer>
              <VueCountUptimer
                v-else
                :start-time="countDownStartTime(item)"
                :end-text="'Window Closed'"
                :interval="1000"
                ><template slot="countup" slot-scope="scope"
                  ><span
                    v-if="scope.props.minutes < 5 && scope.props.hours == 0"
                    class="green--text pl-2 pr-2"
                    >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                      scope.props.seconds
                    }}</span
                  >
                  <span v-else class="red--text pl-2 pr-2"
                    >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                      scope.props.seconds
                    }}</span
                  >
                </template>
              </VueCountUptimer>
            </template>
            <template v-slot:[`item.fc_name`]="{ item }">
              <RcFCButton
                class="mr-2"
                :station="item"
                v-if="showFC(item)"
                :type="2"
              ></RcFCButton>
              <RcFCAdd
                v-if="!item.fc_user_id && $can('view_killsheet_add_fc')"
                :station="item"
                class="pl-6"
                :type="2"
              ></RcFCAdd>
            </template>

            <template v-slot:[`item.region_name`]="{ item }">
              <span> {{ item.region_name }}</span>
            </template>

            <template v-slot:[`item.constellation_name`]="{ item }">
              <span> {{ item.constellation_name }}</span>
            </template>

            <template v-slot:[`item.recon_name`]="{ item }">
              <RcReconButton
                class="mr-2"
                :station="item"
                :type="2"
              ></RcReconButton>
            </template>

            <template v-slot:[`item.status_name`]="{ item }">
              <DoneButton
                v-if="item.out == 1"
                :item="item"
                :type="2"
              ></DoneButton>

              <v-chip v-else pill small :color="pillColor(item)">
                {{ buttontext(item) }}
              </v-chip>
            </template>

            <template v-slot:[`item.gsol_name`]="{ item }">
              <RcGsolButton
                class="mr-2"
                :station="item"
                :type="2"
              ></RcGsolButton>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <div class="d-inline-flex">
                <RcStationMessage
                  class="mr-2"
                  :station="item"
                  :type="2"
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
import { stringify } from "querystring";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `EveStuff - Chill Times`;
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
    // if (this.$can("super")) {
    //     await this.$store.dispatch("getAllianceTickList");
    //     await this.$store.dispatch("getTickList");
    // }

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
    await this.$store.dispatch("getChillRegions");
    await this.$store.dispatch("getChillStationRecords");
    await this.$store.dispatch("getRcFcs");
    await this.$store.dispatch("getChillItems");
    await this.$store.dispatch("getChillStatus");
    this.loadingt = false;
    Echo.private("chillsheet").listen("ChillSheetUpdate", (e) => {
      if (e.flag.message != null) {
        this.$store.dispatch("updateChillStation", e.flag.message);
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
  },

  async mounted() {
    this.log();
    this.onResize();
  },

  methods: {
    log() {
      var request = {
        url: this.$route.path,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/url",
        data: request,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
    showCountDown(item) {
      if (item.out == 0) {
        return true;
      }

      return false;
    },

    buttontext(item) {
      var ret = item.status_name.replace("Upcoming - ", "");
      return ret;
    },

    link(item) {
      if (item.region_name == "Black Rise") {
        return (
          "https://evemaps.dotlan.net/map/Black_Rise/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "The Bleak Lands") {
        return (
          "https://evemaps.dotlan.net/map/The_Bleak_Lands/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "The Citadel") {
        return (
          "https://evemaps.dotlan.net/map/The_Citadel/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Cloud Ring") {
        return (
          "https://evemaps.dotlan.net/map/Cloud_Ring/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Cobalt Edge") {
        return (
          "https://evemaps.dotlan.net/map/Cobalt_Edge/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Etherium Reach") {
        return (
          "https://evemaps.dotlan.net/map/Etherium_Reach/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "The Forge") {
        return (
          "https://evemaps.dotlan.net/map/The_Forge/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "The Kalevala Expanse") {
        return (
          "https://evemaps.dotlan.net/map/The_Kalevala_Expanse/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Molden Heath") {
        return (
          "https://evemaps.dotlan.net/map/Molden_Heath/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Outer Passage") {
        return (
          "https://evemaps.dotlan.net/map/Outer_Passage/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Outer Ring") {
        return (
          "https://evemaps.dotlan.net/map/Outer_Ring/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Paragon Soul") {
        return (
          "https://evemaps.dotlan.net/map/Paragon_Soul/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Period Basis") {
        return (
          "https://evemaps.dotlan.net/map/Period_Basis/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Perrigen Falls") {
        return (
          "https://evemaps.dotlan.net/map/Perrigen_Falls/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Pure Blind") {
        return (
          "https://evemaps.dotlan.net/map/Pure_Blind/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Scalding Pass") {
        return (
          "https://evemaps.dotlan.net/map/Scalding_Pass/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Sinq Laison") {
        return (
          "https://evemaps.dotlan.net/map/Sinq_Laison/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "The Spire") {
        return (
          "https://evemaps.dotlan.net/map/The_Spire/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Vale of the Silent") {
        return (
          "https://evemaps.dotlan.net/map/Vale_of_the_Silent/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Verge Vendor") {
        return (
          "https://evemaps.dotlan.net/map/Verge_Vendor/" +
          item.system_name +
          "#const"
        );
      }
      if (item.region_name == "Wicked Creek") {
        return (
          "https://evemaps.dotlan.net/map/Wicked_Creek/" +
          item.system_name +
          "#const"
        );
      }
      return (
        "https://evemaps.dotlan.net/map/" +
        item.region_name +
        "/" +
        item.system_name +
        "#const"
      );
    },

    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    async freshUpdate() {
      await this.$store.dispatch("getChillRegions");
      await this.$store.dispatch("getChillStationRecords");
      await this.$store.dispatch("getRcFcs");
      await this.$store.dispatch("getChillItems");
      await this.$store.dispatch("getChillStatus");
    },

    async fcupdate() {
      await this.$store.dispatch("getRcFcs");
    },

    async sheetupdate() {
      await this.$store.dispatch("getChillStationRecords");
    },

    campaignStart(item) {
      var data = {
        id: item.id,
        out: 1,
      };
      this.$store.dispatch("updateChillStation", data);
    },
    countDownStartTime(item) {
      return moment.utc(item.end_time).unix();
    },
    pillColor(item) {
      if (item.status_id == 13) {
        return "red darken-4";
      }
      if (item.status_id == 5) {
        return "lime darken-4";
      }
      if (item.status_id == 14) {
        return "green accent-4";
      }
      if (item.status_id == 17) {
        return "#FF5EEA";
      }
    },
    numberDay(day) {
      return parseInt(day, 10) + "d";
    },

    async stationdone(item) {
      await axios({
        method: "put",
        url: "/api/finishrcstationchill/" + item.id,
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
      if (item.status_id == 540) {
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
        if (item.fitted == "Fitted") {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    },
  },

  computed: {
    ...mapState([
      "chillstations",
      "chillsheetRegion",
      "chillsheetItem",
      "chillsheetStatus",
    ]),

    ...mapGetters(["getActiveChillStations"]),
    filteredItems() {
      // return this.rcstations.filter(f => f.show_on_rc == 1);
      return this.getActiveChillStations;
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
          let pick = this.filter_fc.filter((f) => f.status_id == p);
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
          let pick = this.filter_mid.filter((f) => f.region_id == p);
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
      return this.chillsheetRegion;
    },

    dropdown_type_list() {
      return this.chillsheetItem;
    },

    dropdown_status_list() {
      return this.chillsheetStatus.filter((l) => l.text != null);
    },

    _headers() {
      if (this.$can("view_gsol_killsheet")) {
        var Headers = [
          { text: "System", value: "system_name" },
          { text: "Const", value: "constellation_name" },
          { text: "Region", value: "region_name" },
          { text: "Name", value: "name" },
          { text: "Type", value: "item_name" },
          { text: "Status", value: "status_name", align: "center" },
          { text: "Ticker", value: "alliance_ticker" },
          { text: "Expires", value: "end_time" },
          { text: "CountDown", value: "count", sortable: false },
          { text: "FC", value: "fc_name", align: "center" },
          { text: "Cyno", value: "recon_name" },
          { text: "GSOL", value: "gsol_name" },
          { text: "", value: "actions" },
        ];
      } else {
        var Headers = [
          { text: "System", value: "system_name" },
          { text: "Const", value: "constellation_name" },
          { text: "Region", value: "region_name" },
          { text: "Name", value: "name" },
          { text: "Type", value: "item_name" },
          { text: "Status", value: "status_name", align: "center" },
          { text: "Ticker", value: "alliance_ticker" },
          { text: "Expires", value: "end_time" },
          { text: "CountDown", value: "count", sortable: false },
          { text: "FC", value: "fc_name", align: "center" },
          { text: "Cyno", value: "recon_name" },
          { text: "", value: "actions" },
        ];
      }

      return Headers;
    },
  },
  beforeDestroy() {
    Echo.leave("chillsheet");
    Echo.leave("stationlogs");
  },
};
</script>
<style scoped></style>
