<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-data-table
        :headers="_headers"
        :items="filteredItems"
        fixed-header
        :height="height"
        :search="search"
        item-key="id"
        :items-per-page="50"
        multi-sort
        :item-class="itemRowBackground"
        :sort-by="['campaign[0].start_time']"
        @click:row="rowClick($event)"
        :footer-props="{
          'items-per-page-options': [10, 20, 30, 50, 100, -1],
        }"
        class="elevation-24 rounded-xl full-width"
      >
        <template slot="no-data"> No Active or Upcoming Campaigns </template>
        <!-- <template v-slot:[`item.campaign[0].system.system_name`]="{ item }">
          <div class="text-no-wrap">
            {{ item.campaign[0].system.system_name }}
          </div>
        </template> -->
        <!-- <template v-slot:[`item.campaign[0].event_type`]="{ item }">
          {{ eventTypeName(item.campaign[0].event_type) }}
        </template> -->

        <!-- <template v-slot:[`item.campaign[0].constellation.region.region_name`]="{ item }">
          <div class="text-no-wrap">
            {{ item.campaign[0].constellation.region.region_name }}
          </div>
        </template> -->
        <!-- <template v-slot:[`item.campaign[0].constellation.constellation_name`]="{ item }">
          <div class="text-no-wrap">
            {{ item.campaign[0].constellation.constellation_name }}
          </div>
        </template> -->
        <template v-slot:[`item.campaign[0].alliance.name`]="{ item }">
          <div class="d-flex flex-nowrap">
            <span v-if="item.priority == 1" class="rainbow-2 pr-2">
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
                v-if="item.priority == 1"
            /></span>

            <!-- <v-avatar size="35"
              ><img :src="item.campaign[0].alliance.url"
            /></v-avatar>
            <span v-if="item.priority == 0">
              <span
                v-if="item.campaign[0].alliance.standing > 0"
                class="blue--text pl-3"
                >{{ item.campaign[0].alliance.name }}
              </span>
              <span
                v-else-if="item.campaign[0].alliance.standing < 0"
                class="red--text pl-3"
                >{{ item.campaign[0].alliance.name }}
              </span>
              <span v-else class="pl-3">{{
                item.campaign[0].alliance.name
              }}</span></span
            >
            <span v-else>
              <v-chip
                v-if="item.campaign[0].alliance.standing > 0"
                color="primary"
                >{{ item.campaign[0].alliance.name }}</v-chip
              >
              <v-chip
                v-else-if="item.campaign[0].alliance.standing < 0"
                color="red"
                text-color="white"
                >{{ item.campaign[0].alliance.name }}</v-chip
              >
              <v-chip v-else>{{ item.campaign[0].alliance.name }}</v-chip></span
            > -->
            <span v-if="item.priority == 1" class="rainbow-2 pl-2">
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

        <template v-slot:[`item.campaign[0].start_time`]="{ item }">
          <!-- <span v-if="item.campaign[0].status_id == 1 || item.campaign[0].status_id == 5">
            {{ item.campaign[0].start_time }}
          </span>
          <span
            v-else-if="item.campaign[0].status_id == 2"
            class="d-flex full-width align-content-center"
          >
            <span>
              <span
                dark
                color="blue darken-4"
                v-if="
                  item.campaign[0].defenders_score >
                    item.campaign[0].defenders_score_old &&
                  item.campaign[0].defenders_score_old > 0
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-up" pull="left"
              /></span>
              <span
                dark
                color="blue darken-4"
                v-if="
                  item.campaign[0].defenders_score <
                    item.campaign[0].defenders_score_old &&
                  item.campaign[0].defenders_score_old > 0
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-down" pull="left"
              /></span>
              <span
                dark
                color="grey darken-3"
                v-if="
                  item.campaign[0].defenders_score ==
                    item.campaign[0].defenders_score_old ||
                  item.campaign[0].defenders_score_old === null
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
                {{ item.campaign[0].defenders_score * 100 }} /
                {{ item.campaign[0].attackers_score * 100 }}
              </strong>
            </v-progress-linear>
            <span>
              <span
                dark
                color="red darken-4"
                v-if="
                  item.campaign[0].attackers_score >
                    item.campaign[0].attackers_score_old &&
                  item.campaign[0].attackers_score_old > 0
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-up" pull="right"
              /></span>
              <span
                dark
                color="red darken-4"
                v-if="
                  item.campaign[0].attackers_score <
                    item.campaign[0].attackers_score_old &&
                  item.campaign[0].attackers_score_old > 0
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-down" pull="right"
              /></span>
              <span
                dark
                color="grey darken-3"
                v-if="
                  item.campaign[0].attackers_score ==
                    item.campaign[0].attackers_score_old ||
                  item.campaign[0].attackers_score_old == null
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-minus" pull="right"
              /></span>
            </span>
          </span>
          <span
            v-else-if="item.campaign[0].status_id == 3 || item.campaign[0].status_id == 4"
          >
            <p
              v-if="item.campaign[0].attackers_score == 0"
              class="text-md-center green--text"
            >
              {{ item.campaign[0].alliance.name }}
              <span class="font-weight-bold"> WON </span> the
              {{ itemType(item.campaign[0].event_type) }} timer.
            </p>
            <p v-else class="text-md-center red--text">
              {{ item.campaign[0].alliance.name }}
              <span class="font-weight-bold"> LOST </span> the
              {{ itemType(item.campaign[0].event_type) }} timer.
            </p>
          </span> -->
        </template>
        <template v-slot:[`header.webwayCol`]="{ props }">
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
                    <span class="myFontSmall">{{ webwaySelectedStartSystem.text }}</span>
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
              <span v-else class="myFontSmall">{{ webwaySelectedStartSystem.text }}</span>
            </v-col>
          </v-row>
        </template>
        <template v-slot:[`item.webwayCol`]="{ item }">
          <SoloCampaginWebWay
            v-if="webwayJumps(item) && webwayLink(item)"
            :jumps="webwayJumps(item)"
            :web="webwayLink(item)"
          ></SoloCampaginWebWay>
        </template>
        <template v-slot:[`item.count`]="{ item }">
          <div class="d-inline-flex align-center">
            <CountDowntimer
              v-if="item.campaign[0].status_id == 1 || item.campaign[0].status_id == 5"
              :start-time="moment.utc(item.campaign[0].start_time).unix()"
              :end-text="'Window Closed'"
              :interval="1000"
            >
              <template slot="countdown" slot-scope="scope">
                <span
                  v-if="
                    scope.props.hours <= 1 &&
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
                    <span v-if="scope.props.hours == 1">
                      {{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                        scope.props.seconds
                      }}</span
                    >
                    <span v-else>
                      {{ scope.props.minutes }}:{{ scope.props.seconds }}</span
                    >
                  </v-chip>
                </span>
                <span v-else class="red--text pl-3"
                  ><span v-if="item.priority == 0"
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
            <div
              v-if="
                (item.campaign[0].status_id == 2 ||
                  item.campaign[0].status_id == 3 ||
                  item.campaign[0].status_id == 4) &&
                $can('access_campaigns')
              "
            >
              <v-chip
                class="ma-2 ma"
                filter
                pill
                :disabled="pillDisabled(item)"
                :color="pillColor(item)"
              >
                {{ item.campaign[0].status.name }}
              </v-chip>
            </div>
            <div
              v-else-if="
                item.campaign[0].status_id == 2 ||
                item.campaign[0].status_id == 3 ||
                item.campaign[0].status_id == 4
              "
            >
              <span v-if="item.priority == 0" class="pl-5">{{
                item.campaign[0].status.name
              }}</span>
              <v-chip v-else class="pl-5">{{ item.campaign[0].status.name }}</v-chip>
            </div>

            <div
              class="d-flex full-width align-content-center"
              v-if="item.campaign[0].status_id == 2"
            >
              <VueCountUptimer
                :start-time="moment.utc(item.campaign[0].start_time).unix()"
                :end-text="'Campaign Started'"
                :interval="1000"
              >
                <template slot="countup" slot-scope="scope">
                  <span v-if="item.priority == 0" class="green--text pl-3"
                    >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                      scope.props.seconds
                    }}</span
                  >
                  <v-chip v-else color="green darken-3"
                    >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                      scope.props.seconds
                    }}</v-chip
                  >
                </template>
              </VueCountUptimer>
            </div>
            <CampaignMap
              :system_name="item.campaign[0].system.system_name"
              :region_name="item.campaign[0].constellation.region.region_name"
            >
            </CampaignMap>
            <VueCountUptimer
              v-if="item.campaign[0].structure != null"
              :start-time="moment.utc(item.campaign[0].structure.age).unix()"
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
            <NewCampaginPriorityButton v-if="$can('edit_hack_priority')" :item="item" />
          </div>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import { mapGetters, mapState } from "vuex";
import moment, { now, unix, utc } from "moment";
export default {
  props: {
    windowSize: Object,
    filteredItems: Array,
    search: String,
  },
  data() {
    return {
      menu: false,
    };
  },
  mounted() {},
  methods: {
    barColor(item) {
      var d = item.campaign[0].defenders_score * 100;
      if (d > 50) {
        return "blue darken-4";
      }

      return "red darken-4";
    },

    rowClick(item) {
      if (this.$can("access_campaigns")) {
        if (item.campaign[0].status_id == 2 || item.campaign[0].status_id == 5) {
          this.$router.push({ path: `/op/${item.link}` }); // -> /user/123
        }
      }
    },

    webwayJumps(item) {
      if (item.campaign[0].system.webway.length > 0) {
        var base = item.campaign[0].system.webway;
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

    eventTypeName(type) {
      if (type == "32226") {
        return "TCU";
      } else {
        return "Ihub";
      }
    },

    barScoure(item) {
      var d = item.campaign[0].defenders_score * 100;
      var a = item.campaign[0].attackers_score * 100;

      if (d > 50) {
        return d;
      }

      return a;
    },

    barActive(item) {
      if (item.campaign[0].status_id > 1) {
        return true;
      }
      return false;
    },

    onSingleCellClick() {},

    webwayDropdownList(value) {
      var list = this.webwayButtonList.filter((f) => f.value != value);
      return list;
    },

    updateWebwaySelectedStartSystem(item) {
      var data = {
        value: item.value,
        text: item.text,
      };

      this.menu = false;

      this.$store.dispatch("updateWebwaySelectedStartSystem", data);
    },

    barReverse(item) {
      var d = item.campaign[0].defenders_score * 100;
      if (d > 50) {
        return false;
      }

      return true;
    },

    barBgcolor(item) {
      var d = item.campaign[0].defenders_score * 100;
      var a = item.campaign[0].attackers_score * 100;

      if (d > 50) {
        return "red darken-4";
      }

      return "blue darken-4";
    },

    pillDisabled(item) {
      if (item.campaign[0].status_id == 3) {
        return true;
      }
      return false;
    },

    pillColor(item) {
      if (item.campaign[0].status_id == 3) {
        return "blue-grey darken-4";
      }

      return "green darken-3";
    },

    itemRowBackground: function (item) {
      if (item.priority == 1) {
        return "style-2";
      }
    },

    webwayLink(item) {
      if (item.campaign[0].system.webway.length > 0) {
        var base = item.campaign[0].system.webway;
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
    itemType(typeID) {
      if (typeID == 32458) {
        return "Ihub";
      } else {
        return "TCU";
      }
    },
  },
  computed: {
    ...mapState(["webwayStartSystems", "webwaySelectedStartSystem"]),

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

    height() {
      let num = this.windowSize.y - 350;
      return num;
    },

    _headers() {
      if (this.$can("edit_hack_priority")) {
        var Headers = [
          {
            text: "WebWay",
            value: "webwayCol",
            sortable: false,
          },
          {
            text: "Region",
            value: "campaign[0].constellation.region.region_name",
            width: "7%",
          },

          {
            text: "Constellation",
            value: "campaign[0].constellation.constellation_name",
          },

          {
            text: "System",
            value: "campaign[0].system.system_name",
          },
          {
            text: "Alliance",
            value: "campaign[0].alliance.name",
          },

          {
            text: "Ticker",
            value: "campaign[0].alliance.ticker",
          },
          {
            text: "ADM",
            value: "campaign[0].system.adm",
          },

          {
            text: "Structure",
            value: "campaign[0].event_type",
          },

          {
            text: "Start/Progress",
            value: "campaign[0].start_time",
            width: "25%",
            align: "center",
          },

          {
            text: "Countdown/Age",
            value: "count",
            sortable: false,
          },
          {
            text: "",
            value: "actions",
            align: "end",
            sortable: false,
          },
        ];
      } else {
        var Headers = [
          {
            text: "WebWay",
            value: "webwayCol",
            sortable: false,
          },
          {
            text: "Region",
            value: "campaign[0].constellation.region.region_name",
            width: "7%",
          },

          {
            text: "Constellation",
            value: "campaign[0].constellation.constellation_name",
          },

          {
            text: "System",
            value: "campaign[0].system.system_name",
          },
          {
            text: "Alliance",
            value: "campaign[0].alliance.name",
          },

          {
            text: "Ticker",
            value: "campaign[0].alliance.ticker",
            align: "start",
          },
          {
            text: "ADM",
            value: "campaign[0].system.adm",
          },
          {
            text: "Structure",
            value: "campaign[0].event_type",
          },

          {
            text: "Start/Progress",
            value: "campaign[0].start_time",
            width: "20%",
            align: "center",
          },

          {
            text: "Countdown/Age",
            value: "count",
            sortable: false,
          },
        ];
      }
      return Headers;
    },
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
