<template>
  <div class="pr-16 pl-16" v-resize="onResize">
    <div class="d-flex align-items-center">
      <v-card-title>Towers</v-card-title>

      <AddTower class="pr-3"></AddTower>

      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>

      <v-btn-toggle
        v-model="toggle_exclusive1"
        mandatory
        class="ml-4 mr-15"
        :value="2"
      >
        <v-btn
          v-if="$can('view_towers_all')"
          :loading="loadingf"
          :disabled="loadingf"
          @click="standingFlag = 1"
        >
          All
        </v-btn>
        <v-btn
          :loading="loadingf"
          :disabled="loadingf"
          @click="standingFlag = 2"
        >
          Hostile
        </v-btn>
        <v-btn
          v-if="$can('view_towers_all')"
          :loading="loadingf"
          :disabled="loadingf"
          @click="standingFlag = 3"
        >
          Friendly
        </v-btn>
      </v-btn-toggle>

      <v-btn-toggle right-align v-model="toggle_exclusive" mandatory :value="0">
        <v-btn :loading="loadingf" :disabled="loadingf" @click="statusflag = 1">
          All
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="statusflag = 2">
          Scouting
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="statusflag = 3">
          Anchoring
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="statusflag = 4">
          Online
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="statusflag = 5">
          Reffed
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="statusflag = 6">
          Dead
        </v-btn>
      </v-btn-toggle>
    </div>
    <v-data-table
      :headers="headers"
      :items="filter_end"
      :expanded.sync="expanded"
      item-key="id"
      :height="height"
      fixed-header
      :loading="loadingt"
      :items-per-page="50"
      :footer-props="{
        'items-per-page-options': [10, 20, 30, 50, 100, -1],
      }"
      :sort-by="['timestamp']"
      :search="search"
      :sort-desc="[true, false]"
      multi-sort
      class="elevation-1"
    >
      >

      <template slot="no-data">
        No one is shooting our stuff atm, which I would say is a good thing?
      </template>
      <template v-slot:[`item.count`]="{ item }">
        <VueCountUptimer
          :start-time="moment.utc(item.timestamp).unix()"
          :end-text="'Window Closed'"
          :interval="1000"
          @timecheck="timecheck(item)"
        >
          <template slot="countup" slot-scope="scope">
            <span class="red--text pl-3"
              >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                scope.props.seconds
              }}</span
            >
          </template>
        </VueCountUptimer>
      </template>

      <template
        v-slot:[`item.alliance_name`]="{ item }"
        class="d-inline-flex align-center"
      >
        <v-avatar size="35"><img :src="item.url" /></v-avatar>
        <span v-if="item.standing > 0" class="blue--text pl-3"
          >{{ item.alliance_name }}
        </span>
        <span v-else-if="item.standing < 0" class="red--text pl-3"
          >{{ item.alliance_name }}
        </span>
        <span v-else class="pl-3">{{ item.alliance_name }}</span>
      </template>

      <template
        v-slot:[`item.tower_status_name`]="{ item }"
        class="align-items-center"
      >
        <div class="align-items-center d-inline-flex">
          <v-menu offset-y>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                class="ma-2"
                v-bind="attrs"
                v-on="on"
                tile
                outlined
                :color="pillColor(item.tower_status_id)"
              >
                <font-awesome-icon
                  :icon="pillIcon(item.tower_status_id)"
                  pull="left"
                />
                {{ item.tower_status_name }}
              </v-btn>
            </template>

            <v-list>
              <v-list-item
                v-for="(list, index) in dropdown_edit"
                :key="index"
                @click="click(item, list)"
              >
                <v-list-item-title>{{ list.title }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>

          <!-- EXTRA BUTTON -->
          <v-fab-transition group>
            <v-tooltip
              color="#121212"
              :key="'tooltip' + item.id"
              bottom
              :open-delay="2000"
              :disabled="$store.state.tooltipToggle"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-chip
                  :key="'chip' + item.id"
                  pill
                  outlined
                  v-bind="attrs"
                  v-on="on"
                  small
                  @click="(expanded = [item]), (expanded_id = item.id)"
                  v-show="
                    item.tower_status_id != 6 &&
                    item.tower_status_id != 1 &&
                    !expanded.includes(item)
                  "
                  :color="adashColor(item)"
                  >adash</v-chip
                >
              </template>
              <span>
                Where to add/view aDash of tower (click the "more" on aDash to
                see Structures)</span
              >
            </v-tooltip>
            <v-btn
              :key="'button' + item.id"
              icon
              @click="(expanded = []), (expanded_id = 0)"
              v-show="
                (item.tower_status_id != 6 || item.tower_status_id != 1) &&
                expanded.includes(item)
              "
              color="error"
              ><font-awesome-icon icon="fa-solid fa-minus"
            /></v-btn>
          </v-fab-transition>
          <v-tooltip
            color="#121212"
            bottom
            :open-delay="2000"
            :disabled="$store.state.tooltipToggle"
          >
            <template v-slot:activator="{ on: tooltip, attrs: atooltip }">
              <div v-on="{ ...tooltip }" v-bind="{ ...atooltip }">
                <TowerMessage
                  class="pl-2"
                  :key="'towermessage' + item.id"
                  :tower="item"
                ></TowerMessage>
              </div>
            </template>
            <span> Where to enter notes about the Tower </span>
          </v-tooltip>
          <v-slide-x-transition>
            <TowerRefTimer
              :key="'towerreftimer' + item.id"
              :item="item"
              v-show="item.tower_status_id == 5"
            ></TowerRefTimer>
          </v-slide-x-transition>
          <v-slide-x-transition>
            <TowerOnlineTimer
              :key="'TowerOnlineTimer' + item.id"
              v-show="item.tower_status_id == 3"
              :item="item"
            ></TowerOnlineTimer>
          </v-slide-x-transition>
        </div>
      </template>
      <template
        v-slot:expanded-item="{ headers, item }"
        class="align-center"
        height="100%"
      >
        <td :colspan="headers.length" align="center">
          <div>
            <v-col class="align-center">
              <v-text-field
                v-bind:value="item.text"
                label="aDash Board Link - needs to be a link to a scan, making a new scan from where will not save"
                outlined
                shaped
                @change="(payload = $event), updatetext(payload, item)"
              ></v-text-field>
            </v-col>
          </div>
          <div
            v-if="
              item.text != null &&
              item.text.includes('https://adashboard.info/intel/dscan/')
            "
          >
            <v-card class="mx-auto" elevation="24">
              <iframe
                :name="'ifram' + item.id"
                :src="item.text"
                style="
                  left: 0;
                  bottom: 0;
                  right: 0;
                  width: 100%;
                  height: 600px;
                  border: none;
                  margin: 0;
                  padding: 0;
                  overflow: hidden;
                  z-index: 999999;
                "
              >
              </iframe>
            </v-card>
          </div>
          <div>
            {{ item.text }}
          </div>
        </td>
      </template>
    </v-data-table>
    <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
      {{ snackText }}

      <template v-slot:action="{ attrs }">
        <v-btn v-bind="attrs" text @click="snack = false">Close</v-btn>
      </template>
    </v-snackbar>
  </div>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
import ApiL from "../service/apil";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {
    return `EveStuff - Towers`;
  },
  data() {
    return {
      atime: null,
      check: "not here",
      componentKey: 0,
      dialog1: false,
      dialog2: false,
      dialog3: false,
      diff: 0,
      delve: 0,
      endcount: "",
      expanded: [],
      expanded_id: 0,
      icon: "justify",
      loadingt: true,
      loadingf: true,
      loadingr: true,
      loading3: true,
      name: "Timer",
      poll: null,
      periodbasis: 0,
      search: "",
      statusflag: 1,
      snack: false,
      snackColor: "",
      snackText: "",
      toggle_exclusive: 0,
      today: 0,
      anchoringClass: "",
      text: "center",
      toggle_none: null,
      querious: 0,
      toggle_exclusive1: 1,
      standingFlag: 2,
      windowSize: {
        x: 0,
        y: 0,
      },

      dropdown_edit: [
        { title: "Scouted", value: 2 },
        { title: "Anchoring", value: 3 },
        { title: "Anchored", value: 9 },
        { title: "Online", value: 4 },
        { title: "Reffed", value: 5 },
        { title: "Dead", value: 6 },
        { title: "New", value: 1 },
      ],

      headers: [
        { text: "Region", value: "region_name", width: "3%" },
        {
          text: "Constellation",
          value: "constellation_name",
          width: "3%",
        },
        { text: "System", value: "system_name", width: "3%" },
        { text: "Alliance", value: "alliance_name", width: "12%" },
        { text: "Moon", value: "moon_name", width: "8%" },
        {
          text: "Type",
          value: "item_name",
          align: "center",
          width: "10%",
        },
        { text: "Time", value: "timestamp", width: "8%" },

        { text: "Status", value: "tower_status_name", width: "15%" },
        {
          text: "Edited By",
          value: "user_name",
          width: "5%",
          align: "start",
        },
      ],
    };
  },

  created() {
    Echo.private("towers")
      .listen("TowerChanged", (e) => {
        this.checkexpanded(e.flag.message);
        this.$store.dispatch("updateTowers", e.flag.message);
      })

      .listen("TowerNew", (e) => {
        this.loadtowers();
      })
      .listen("TowerDelete", (e) => {
        this.$store.dispatch("deleteTower", e.flag.id);
      });

    this.$store.dispatch("getTowerData").then(() => {
      this.loadingt = false;
      this.loadingf = false;
      this.loadingr = false;
      this.loading3 = false;
    });
  },

  async mounted() {
    this.log();
    this.onResize();
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
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
    checkexpanded(towers) {
      if (towers.tower_status_id == 1 || towers.tower_status_id == 6) {
        if (towers.id == this.expanded_id) {
          this.expanded = [];
          this.expanded_id = 0;
        }
      }
    },

    updatetext(payload, item) {
      if (item.text != payload) {
        item.text = payload;
        if (item.text == "") {
          item.text == null;
        }
        var request = {
          text: item.text,
        };
        this.$store.dispatch("updateTowers", item);
        axios({
          method: "put", //you can set what request you want to be
          url: "api/towerrecords/" + item.id,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }
    },

    adashColor(item) {
      if (item.text != null) {
        return "green";
      } else {
        return "red";
      }
    },

    pillIcon(id) {
      if (id == 1) {
        return "faSvg fa-plus";
      }
      if (id == 2) {
        return "faSvg fa-search";
      }
      if (id == 3) {
        return "faSvg fa-anchor";
      }
      if (id == 4) {
        return "faSvg fa-broadcast-tower";
      }
      if (id == 5) {
        return "faSvg fa-shield-alt";
      }
      if (id == 6) {
        return "faSvg fa-skull-crossbones";
      }
      if (id == 7) {
        return "faSvg fa-clock";
      }
      if (id == 8) {
        return "faSvg fa-life-ring";
      }

      if (id == 9) {
        return "faSvg fa-anchor";
      }
    },

    loadtowers() {
      this.loadingr = true;
      this.$store.dispatch("getTowerData").then(() => {
        this.loadingr = false;
        this.loading3 = false;
      });
    },

    pillColor(statusId) {
      if (statusId == 1) {
        return "light-green accent-3";
      }
      if (statusId == 2) {
        return "light-blue darken-2";
      }
      if (statusId == 3) {
        return "deep-orange darken-2";
      }
      if (statusId == 4) {
        return "red accent-4";
      }
      if (statusId == 5) {
        return "blue darken-4";
      }
      if (statusId == 6) {
        return "light-green accent-3";
      }
      if (statusId == 7) {
        return "dark-orange";
      }
      if (statusId == 8) {
        return "primary";
      }

      if (statusId == 8) {
        return "primary";
      }

      if (statusId == 9) {
        return "teal darken-3";
      }
    },

    save() {
      this.snack = true;
      this.snackColor = "success";
      this.snackText = "Data saved";
    },
    cancel() {
      this.snack = true;
      this.snackColor = "error";
      this.snackText = "Canceled";
    },
    open() {
      this.snack = true;
      this.snackColor = "info";
      this.snackText = "Dialog opened";
    },
    close() {},

    test(item) {
      if (item.tower_status_id == 3) {
        return "animate__animated animate__zoomIn animate__faster";
      } else {
        return "animate__animated animate__zoomOut animate__faster";
      }
    },

    async click(item, list) {
      var request = {};

      item.tower_status_id = list.value;
      item.tower_status_name = list.title;
      item.user_name = this.user_name;
      if (item.tower_status_id == 1 || item.tower_status_id == 6) {
        this.expanded = [];
        item.text = null;
      }

      if (item.tower_status_id != 3 && item.tower_status_id != 5) {
        var request = {
          tower_status_id: item.tower_status_id,
          user_id: this.$store.state.user_id,
          out_time: null,
        };
      } else {
        var request = {
          tower_status_id: item.tower_status_id,
          user_id: this.$store.state.user_id,
        };
      }

      axios({
        method: "put", //you can set what request you want to be
        url: "api/towerrecords/" + item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    sec(item) {
      var a = moment.utc();
      var b = moment(item.timestamp);
      this.diff = a.diff(b);
      return this.diff;
    },
  },

  computed: {
    ...mapState(["towers"]),

    filteredItems() {
      if (this.statusflag == 2) {
        return this.towers.filter((towers) => towers.tower_status_id == 2);
      }
      if (this.statusflag == 3) {
        return this.towers.filter((towers) => towers.tower_status_id == 3);
      }
      if (this.statusflag == 4) {
        return this.towers.filter((towers) => towers.tower_status_id == 3);
      }
      if (this.statusflag == 5) {
        return this.towers.filter((towers) => towers.tower_status_id == 3);
      }
      if (this.statusflag == 6) {
        return this.towers.filter((towers) => towers.tower_status_id == 3);
      } else {
        return this.towers.filter((towers) => towers.tower_status_id != 10);
      }
    },

    height() {
      let num = this.windowSize.y - 277;
      return num;
    },

    filter_end() {
      if (this.standingFlag == 1) {
        return this.filteredItems;
      }
      if (this.standingFlag == 2) {
        return this.filteredItems.filter((s) => s.standing < 0.1);
      }
      if (this.standingFlag == 3) {
        return this.filteredItems.filter((s) => s.standing > 0);
      }
    },

    user_name() {
      return this.$store.state.user_name;
    },
  },
  beforeDestroy() {
    Echo.leave("towers");
  },
};
</script>
