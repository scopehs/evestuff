<template>
  <div class="pr-16 pl-16" v-resize="onResize">
    <div class="d-flex align-items-center">
      <v-card-title>Campaigns</v-card-title>

      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>

      <v-card max-width="600px" min-width="600px" color="#121212" elevation="0">
        <v-card-text>
          <v-select
            class="pb-2"
            v-model="typePicked"
            :items="dropdown_search_list"
            label="Filter by Region"
            multiple
            chips
            deletable-chips
            hide-details
          ></v-select>
        </v-card-text>
      </v-card>

      <v-btn-toggle
        v-model="toggle_exclusive1"
        mandatory
        class="ml-4 mr-15"
        :value="2"
      >
        <v-btn :loading="loadingf" :disabled="loadingf" @click="itemFlag = 1">
          All
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="itemFlag = 2">
          IHUBs
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="itemFlag = 3">
          TCUs
        </v-btn>
      </v-btn-toggle>

      <v-btn-toggle v-model="toggle_exclusive" mandatory :value="1">
        <v-btn :loading="loadingf" :disabled="loadingf" @click="colorflag = 4">
          All
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="colorflag = 3">
          Goons
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="colorflag = 2">
          Friendly
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="colorflag = 1">
          Hostile
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="colorflag = 5">
          Active
        </v-btn>
        <v-btn :loading="loadingf" :disabled="loadingf" @click="colorflag = 6">
          Finished
        </v-btn>
      </v-btn-toggle>
    </div>
    <v-data-table
      :headers="_headers"
      :items="filter_end"
      item-key="id"
      :loading="loading"
      :height="height"
      fixed-header
      :items-per-page="50"
      :item-class="itemRowBackground"
      :footer-props="{
        'items-per-page-options': [10, 20, 30, 50, 100, -1],
      }"
      :sort-by="['start']"
      :search="search"
      multi-sort
      @click:row="rowClick($event)"
      class="elevation-1"
    >
      <template slot="no-data"> No Active or Upcoming Campaigns </template>
      <template v-slot:[`item.system`]="{ item }">
        <div class="text-no-wrap">{{ item.system }}</div>
      </template>
      <template v-slot:[`item.region`]="{ item }">
        <div class="text-no-wrap">{{ item.region }}</div>
      </template>
      <template v-slot:[`item.constellation`]="{ item }">
        <div class="text-no-wrap">{{ item.constellation }}</div>
      </template>
      <template v-slot:[`item.alliance`]="{ item }">
        <div class="d-flex flex-nowrap">
          <span v-if="item.priority.priority == 1" class="rainbow-2 pr-2">
            <font-awesome-icon
              icon="fa-solid fa-wand-magic-sparkles"
              size="xl"
              transform="flip-h"
              class="fa-bounce"
              style="
                --fa-bounce-start-scale-x: 1;
                --fa-bounce-start-scale-y: 1;
                --fa-bounce-jump-scale-x: 1;
                --fa-bounce-jump-scale-y: 1;
                --fa-bounce-land-scale-x: 1;
                --fa-bounce-land-scale-y: 1;
              "
              v-if="item.priority.priority == 1"
          /></span>

          <v-avatar size="35"><img :src="item.url" /></v-avatar>
          <span v-if="item.priority.priority == 0">
            <span v-if="item.standing > 0" class="blue--text pl-3"
              >{{ item.alliance }}
            </span>
            <span v-else-if="item.standing < 0" class="red--text pl-3"
              >{{ item.alliance }}
            </span>
            <span v-else class="pl-3">{{ item.alliance }}</span></span
          >
          <span v-else>
            <v-chip v-if="item.standing > 0" color="primary">{{
              item.alliance
            }}</v-chip>
            <v-chip
              v-else-if="item.standing < 0"
              color="red"
              text-color="white"
              >{{ item.alliance }}</v-chip
            >
            <v-chip v-else>{{ item.alliance }}</v-chip></span
          >
          <span v-if="item.priority.priority == 1" class="rainbow-2 pl-2">
            <font-awesome-icon
              icon="fa-solid fa-wand-magic-sparkles"
              size="xl"
              class="fa-bounce"
              bounce
              style="
                --fa-bounce-start-scale-x: 1;
                --fa-bounce-start-scale-y: 1;
                --fa-bounce-jump-scale-x: 1;
                --fa-bounce-jump-scale-y: 1;
                --fa-bounce-land-scale-x: 1;
                --fa-bounce-land-scale-y: 1;
              "
            />
          </span>
        </div>
      </template>

      <template v-slot:[`item.start`]="{ item }">
        <span v-if="item.status_id == 1"> {{ item.start }} </span>
        <span
          v-else-if="item.status_id != 3 && item.status_id != 4"
          class="d-flex full-width align-content-center"
        >
          <span>
            <span
              dark
              color="blue darken-4"
              v-if="
                item.defenders_score > item.defenders_score_old &&
                item.defenders_score_old > 0
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-up" pull="left"
            /></span>
            <span
              dark
              color="blue darken-4"
              v-if="
                item.defenders_score < item.defenders_score_old &&
                item.defenders_score_old > 0
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-down" pull="left"
            /></span>
            <span
              dark
              color="grey darken-3"
              v-if="
                item.defenders_score == item.defenders_score_old ||
                item.defenders_score_old === null
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-minus" pull="left"
            /></span>
          </span>

          <v-progress-linear
            :color="barColor(item)"
            :value="barScoure(item)"
            height="20"
            rounded
            :active="barActive(item)"
            :reverse="barReverse(item)"
            :background-color="barBgcolor(item)"
            background-opacity="0.2"
          >
            <strong>
              {{ item.defenders_score * 100 }} /
              {{ item.attackers_score * 100 }}
            </strong>
          </v-progress-linear>
          <span>
            <span
              dark
              color="red darken-4"
              v-if="
                item.attackers_score > item.attackers_score_old &&
                item.attackers_score_old > 0
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-up" pull="right"
            /></span>
            <span
              dark
              color="red darken-4"
              v-if="
                item.attackers_score < item.attackers_score_old &&
                item.attackers_score_old > 0
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-down" pull="right"
            /></span>
            <span
              dark
              color="grey darken-3"
              v-if="
                item.attackers_score == item.attackers_score_old ||
                item.attackers_score_old == null
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-minus" pull="right"
            /></span>
          </span>
        </span>
        <span v-else-if="item.status_id == 3 || item.status_id == 4">
          <p
            v-if="item.attackers_score == 0"
            class="text-md-center green--text"
          >
            {{ item.alliance }}
            <span class="font-weight-bold"> WON </span> the
            {{ item.item_name }} timer.
          </p>
          <p v-else class="text-md-center red--text">
            {{ item.alliance }}
            <span class="font-weight-bold"> LOST </span> the
            {{ item.item_name }} timer.
          </p>
        </span>
      </template>
      <template v-slot:[`header.webway`]="{ props }">
        <v-row no-gutters>
          <v-col>
            <span class="myFont">Webway</span>
          </v-col></v-row
        >
        <v-row no-gutters>
          <v-col>
            <v-menu
              v-if="webwayButton"
              v-model="menu"
              :close-on-content-click="false"
              offset-y
              :transition="false"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn text v-bind="attrs" v-on="on" x-small>
                  <span class="myFontSmall">{{
                    webwaySelectedStartSystem.text
                  }}</span>
                </v-btn>
              </template>

              <v-card>
                <v-list>
                  <v-list-item
                    v-for="(list, index) in webwayDropdownList(
                      webwaySelectedStartSystem.value
                    )"
                    :key="index"
                    @click="updateWebwaySelectedStartSystem(list)"
                  >
                    <v-list-item-title>{{ list.text }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-card>
            </v-menu>
            <span v-else class="myFontSmall">{{
              webwaySelectedStartSystem.text
            }}</span>
          </v-col>
        </v-row>
      </template>
      <template v-slot:[`item.webway`]="{ item }">
        <SoloCampaginWebWay
          v-if="webwayJumps(item) && webwayLink(item)"
          :jumps="webwayJumps(item)"
          :web="webwayLink(item)"
        ></SoloCampaginWebWay>
      </template>
      <template v-slot:[`item.count`]="{ item }">
        <div class="d-inline-flex align-center">
          <CountDowntimer
            v-if="item.status_id == 1"
            :start-time="moment.utc(item.start).unix()"
            :end-text="'Window Closed'"
            :interval="1000"
            @campaignStart="campaignStart(item)"
          >
            <template slot="countdown" slot-scope="scope">
              <span
                v-if="
                  scope.props.hours == 0 &&
                  scope.props.days == 0 &&
                  $can('access_campaigns')
                "
                class="red--text pl-3"
              >
                <v-chip
                  class="ma-2 ma"
                  filter
                  pill
                  :disabled="pillDisabled(item)"
                  color="light-blue lighten-1"
                >
                  {{ scope.props.minutes }}:{{ scope.props.seconds }}
                </v-chip>
              </span>
              <span v-else class="red--text pl-3"
                ><span v-if="item.priority.priority == 0"
                  >{{ scope.props.days }}:{{ scope.props.hours }}:{{
                    scope.props.minutes
                  }}:{{ scope.props.seconds }}</span
                ><span v-else
                  ><v-chip color="red"
                    >{{ scope.props.days }}:{{ scope.props.hours }}:{{
                      scope.props.minutes
                    }}:{{ scope.props.seconds }}</v-chip
                  ></span
                ></span
              >
            </template>
          </CountDowntimer>
          <div v-if="item.status_id > 1 && $can('access_campaigns')">
            <v-chip
              class="ma-2 ma"
              filter
              pill
              :disabled="pillDisabled(item)"
              :color="pillColor(item)"
            >
              {{ item.status_name }}
            </v-chip>
          </div>
          <div v-else-if="item.status_id > 1">
            <span class="pl-5">{{ item.status_name }}</span>
          </div>

          <div
            class="d-flex full-width align-content-center"
            v-if="item.status_id == 2"
          >
            <VueCountUptimer
              :start-time="moment.utc(item.start).unix()"
              :end-text="'Campaign Started'"
              :interval="1000"
            >
              <template slot="countup" slot-scope="scope">
                <span class="green--text pl-3"
                  >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                    scope.props.seconds
                  }}</span
                >
              </template>
            </VueCountUptimer>
          </div>
          <CampaignMap :system_name="item.system" :region_name="item.region">
          </CampaignMap>
          <VueCountUptimer
            v-if="item.Age != null"
            :start-time="moment.utc(item.Age).unix()"
            :end-text="'Window Closed'"
            :interval="1000"
            :leadingZero="false"
          >
            <template slot="countup" slot-scope="scope">
              <span class="green--text pl-3"
                ><span v-if="scope.props.days != 0">
                  {{ scope.props.days }} Days - </span
                >{{ scope.props.hours }} Hours</span
              >
            </template>
          </VueCountUptimer>
        </div>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <div @click.stop="onSingleCellClick()">
          <CampaginPriorityButton
            v-if="$can('edit_hack_priority')"
            :item="item.priority"
          />
        </div>
      </template>
    </v-data-table>
  </div>
</template>
<script>
import Axios from "axios";
import moment, { now, unix, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `EveStuff - Campaigns`;
  },
  data() {
    return {
      //timersAll: [869349],

      loadingr: true,
      loadingf: true,
      loading: true,
      endcount: "",
      search: "",
      componentKey: 0,
      toggle_exclusive: 0,
      toggle_exclusive1: 1,
      colorflag: 4,
      itemFlag: 2,
      name: "Timer",
      menu: false,
      typePicked: [],
      windowSize: {
        x: 0,
        y: 0,
      },
    };
  },

  async created() {
    await this.$store.dispatch("getWebwayStartSystems");
    Echo.private("campaigns").listen("CampaignChanged", (e) => {
      this.loadcampaigns();
    }),
      this.$store.dispatch("getCampaignsRegions");
    this.$store.dispatch("getCampaigns").then(() => {
      this.loadingf = false;
      this.loadingr = false;
      this.loading = false;
    });
  },

  async mounted() {
    // this.log();
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

    colNum(num) {
      if (num == 1) {
        return "10";
      } else {
        return "12";
      }
    },

    itemRowBackground: function (item) {
      if (item.priority.priority == 1) {
        return "style-2";
      }
    },

    updateWebwaySelectedStartSystem(item) {
      var data = {
        value: item.value,
        text: item.text,
      };

      this.menu = false;

      this.$store.dispatch("updateWebwaySelectedStartSystem", data);
    },

    async loadcampaigns() {
      this.loadingr = true;
      this.$store.dispatch("getCampaignsRegions");
      this.$store.dispatch("getCampaigns").then(() => {
        this.loadingr = false;
      });
    },

    campaignStart(item) {
      item.status_name = "Active";
      item.status_id = 2;
      var request = {
        status_id: 2,
      };

      this.$store.dispatch("updateCampaign", item);

      axios({
        method: "put", //you can set what request you want to be
        url: "api/campaigns/" + item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    fixTime(item) {
      return moment.utc(item.start).unix(); // retu  rn utc.unix()
    },

    rowClick(item) {
      if (this.$can("access_campaigns")) {
        var left = moment.utc(item.start).unix() - moment.utc().unix();
        if (left < 3600 && item.status_id < 3) {
          this.$router.push({ path: `/campaign/${item.link}` }); // -> /user/123
        }
      }
    },

    barScoure(item) {
      var d = item.defenders_score * 100;
      var a = item.attackers_score * 100;

      if (d > 50) {
        return d;
      }

      return a;
    },

    barBgcolor(item) {
      var d = item.defenders_score * 100;
      var a = item.attackers_score * 100;

      if (d > 50) {
        return "red darken-4";
      }

      return "blue darken-4";
    },

    barColor(item) {
      var d = item.defenders_score * 100;
      if (d > 50) {
        return "blue darken-4";
      }

      return "red darken-4";
    },

    barReverse(item) {
      var d = item.defenders_score * 100;
      if (d > 50) {
        return false;
      }

      return true;
    },

    webwayJumps(item) {
      if (item.webway) {
        var base = item.webway;
        var filter = base.filter(
          (f) => f.start_system_id == this.webwaySelectedStartSystem.value
        );
        if (filter.length > 0) {
          var jumps = filter[0].jumps;
          return jumps;
        } else {
          return null;
        }
      } else {
        return null;
      }
    },

    webwayLink(item) {
      if (item.webway) {
        var base = item.webway;
        var filter = base.filter(
          (f) => f.start_system_id == this.webwaySelectedStartSystem.value
        );
        if (filter.length > 0) {
          var link = filter[0].webway;
          return link;
        } else {
          return null;
        }
      } else {
        return null;
      }
    },

    barActive(item) {
      if (item.status_id > 1) {
        return true;
      }
      return false;
    },

    pillDisabled(item) {
      if (item.status_id == 3) {
        return true;
      }
      return false;
    },

    pillColor(item) {
      if (item.status_id == 3) {
        return "blue-grey darken-4";
      }

      return "green darken-3";
    },

    webwayDropdownList(value) {
      var list = this.webwayButtonList.filter((f) => f.value != value);
      return list;
    },

    onSingleCellClick() {},

    transform(props) {
      Object.entries(props).forEach(([key, value]) => {
        // Adds leading zero
        const digits = value < 10 ? `0${value}` : value;
        props[key] = `${digits}`;
      });

      return props;
    },
    handleCountdownEnd(item) {
      this.$store.dispatch("markOver", item);
    },
  },
  computed: {
    ...mapState([
      "campaigns",
      "campaignsRegion",
      "webwayStartSystems",
      "webwaySelectedStartSystem",
    ]),

    dropdown_search_list() {
      return this.campaignsRegion;
    },

    height() {
      let num = this.windowSize.y - 315;
      return num;
    },

    webwayButton() {
      if (this.webwayStartSystems.length > 0) {
        return true;
      } else {
        return false;
      }
    },

    webwayButtonList() {
      var list = this.webwayStartSystems;
      var data = {
        value: 30004759,
        text: "1DQ1-A",
      };
      list.push(data);
      list.sort(function (a, b) {
        return a.value - b.value || a.text.localeCompare(b.text);
      });

      return list;
    },
    filteredItems_start() {
      // var timers = this.$store.state.timers;
      if (this.colorflag == 1) {
        return this.campaigns.filter(
          (campaigns) =>
            campaigns.color == 1 &&
            campaigns.status_id != 3 &&
            campaigns.status_id != 4
        );
      }
      if (this.colorflag == 2) {
        return this.campaigns.filter(
          (campaigns) =>
            campaigns.color > 1 &&
            campaigns.status_id != 3 &&
            campaigns.status_id != 4
        );
      }
      if (this.colorflag == 3) {
        return this.campaigns.filter(
          (campaigns) =>
            campaigns.color == 3 &&
            campaigns.status_id != 3 &&
            campaigns.status_id != 4
        );
      }
      if (this.colorflag == 6) {
        return this.campaigns.filter(
          (campaigns) => campaigns.status_id == 3 || campaigns.status_id == 4
        );
      }
      if (this.colorflag == 5) {
        return this.campaigns.filter((campaigns) => campaigns.status_id == 2);
      } else {
        return this.campaigns.filter(
          (campaigns) => campaigns.status_id == 1 || campaigns.status_id == 2
        );
      }
    },

    filteredItems() {
      if (this.itemFlag == 1) {
        return this.filteredItems_start;
      } else if (this.itemFlag == 2) {
        return this.filteredItems_start.filter((c) => c.item_name == "Ihub");
      } else {
        return this.filteredItems_start.filter((c) => c.item_name == "TCU");
      }
    },

    filter_end() {
      let data = [];
      if (this.typePicked.length != 0) {
        this.typePicked.forEach((p) => {
          let pick = this.filteredItems.filter((f) => f.region_id == p);
          if (pick != null) {
            pick.forEach((pk) => {
              data.push(pk);
            });
          }
        });
        return data;
      }
      return this.filteredItems;
    },

    _headers() {
      if (this.$can("edit_hack_priority")) {
        var Headers = [
          { text: "WebWay", value: "webway", sortable: false },
          { text: "Region", value: "region", width: "7%" },
          { text: "Constellation", value: "constellation" },
          { text: "System", value: "system" },
          { text: "Alliance", value: "alliance" },
          { text: "Ticker", value: "ticker", align: "start" },
          { text: "ADM", value: "adm" },
          { text: "Structure", value: "item_name" },
          {
            text: "Start/Progress",
            value: "start",
            width: "25%",
            align: "center",
          },
          { text: "Countdown/Age", value: "count", sortable: false },
          {
            text: "",
            value: "actions",
            align: "end",
            sortable: false,
          },
        ];
      } else {
        var Headers = [
          { text: "WebWay", value: "webway", sortable: false },
          { text: "Region", value: "region", width: "7%" },
          { text: "Constellation", value: "constellation" },
          { text: "System", value: "system" },
          { text: "Alliance", value: "alliance" },
          { text: "Ticker", value: "ticker", align: "start" },
          { text: "ADM", value: "adm" },
          { text: "Structure", value: "item_name" },
          {
            text: "Start/Progress",
            value: "start",
            width: "20%",
            align: "center",
          },
          { text: "Countdown/Age", value: "count", sortable: false },
        ];
      }

      return Headers;
    },
  },
  beforeDestroy() {
    Echo.leave("campaigns");
  },
};
</script>

<style>
.style-2 {
  background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.follow {
  margin-top: 40px;
}

.follow a {
  color: black;
  padding: 8px 16px;
  text-decoration: none;
}

.rainbow-2 {
  animation: colorRotate 0.5s linear 0s infinite;
}

.style-1 {
  background-color: rgb(184, 22, 35);
}

@keyframes colorRotate {
  from {
    color: #6666ff;
  }
  10% {
    color: #0099ff;
  }
  50% {
    color: #00ff00;
  }
  75% {
    color: #ff3399;
  }
  100% {
    color: #6666ff;
  }
}
</style>
