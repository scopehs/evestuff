<template>
  <v-row no-gutters class="align-items-center">
    <v-col cols="12">
      <transition
        name="custom-classes"
        enter-active-class="animate__animated animate__heartBeat animate__repeat-2"
        leave-active-class="animate__animated animate__flash animate__faster"
        mode="out-in"
      >
        <v-row
          no-gutters
          v-if="showScore"
          :key="`${item.id}-score`"
          class="align-items-center"
          justify="space-between"
        >
          <v-col cols="auto">
            <span :class="textColor">
              {{ item.system.system_name }} - {{ eventType }}:
              {{ this.item.alliance.ticker }}
              <v-avatar size="50"
                ><img :src="this.item.alliance.url" /></v-avatar
            ></span>
          </v-col>
          <v-col
            cols="5"
            class="
              d-flex
              justify-content-center
              align-content-center align-items-center
            "
          >
            <div icon dark :color="IconDColor" :class="IconDClass">
              <font-awesome-icon :icon="IconD" pull="left" />
            </div>
            <!-- // TODO Active (only show if campaign is active) -->
            <v-progress-linear
              :color="barColor"
              :value="barScoure"
              height="20"
              rounded
              :active="true"
              :reverse="barReverse"
              :background-color="barBgcolor"
              background-opacity="0.2"
            >
              <strong>
                {{ item.defenders_score * 100 }} ({{ nodesToLose }}) /
                {{ item.attackers_score * 100 }} ({{ nodesToWin }})
              </strong>
            </v-progress-linear>
            <div dark :color="IconAColor" :class="IconAClass">
              <font-awesome-icon :icon="IconD" size="sm" pull="left" />
            </div>
          </v-col>
          <v-col
            cols="auto"
            class="d-flex justify-content-end align-items-center"
          >
            <span class="text-caption"> Active Nodes -</span>
            <Vep
              :progress="blueProgress"
              :size="50"
              :legend-value="blueNode"
              fontSize="0.80rem"
              color="#00ff00"
              :thickness="4"
              :emptyThickness="1"
              emptyColor="#a4fca4"
            >
              <template v-slot:legend-value>
                <span slot="legend-value"> /{{ totalNode }}</span>
              </template>
            </Vep>
            <Vep
              :progress="redProgress"
              :size="50"
              :legend-value="redNode"
              fontSize="0.80rem"
              color="#ff0000"
              :thickness="4"
              :emptyThickness="1"
              emptyColor="#f08d8d"
            >
              <template v-slot:legend-value>
                <span slot="legend-value"> /{{ totalNode }}</span>
              </template>
            </Vep>
            <span class="ml-2"> Completed Nodes -</span>
            <Vep
              :progress="totalBlueProgress"
              :size="50"
              :legend-value="totalBlueNodeDone"
              fontSize="0.80rem"
              color="#00ff00"
              :thickness="4"
              :emptyThickness="1"
              emptyColor="#a4fca4"
            >
              <template v-slot:legend-value>
                <span slot="legend-value"> /{{ totalNodeDone }}</span>
              </template>
            </Vep>
            <Vep
              :progress="totalRedProgress"
              :size="50"
              :legend-value="totalRedNodeDone"
              fontSize="0.80rem"
              color="#ff0000"
              :thickness="4"
              :emptyThickness="1"
              emptyColor="#f08d8d"
            >
              <template v-slot:legend-value>
                <span slot="legend-value"> /{{ totalNodeDone }}</span>
              </template>
            </Vep>
          </v-col>
        </v-row>
        <v-row
          no-gutters
          v-else-if="item.status_id != 3 && item.status_id != 4"
          :key="`${item.id}-timer`"
        >
          <v-col cols="2">
            <span :class="textColor">
              {{ item.system.system_name }} - {{ eventType }}:
              {{ this.item.alliance.ticker }}
              <v-avatar size="50"
                ><img :src="this.item.alliance.url" /></v-avatar
            ></span>
          </v-col>
          <v-col cols="3" class="d-flex align-items-center">
            <CountDowntimer
              :start-time="moment.utc(item.start_time).unix()"
              :end-text="'Campaign Over'"
              :interval="1000"
            >
              <template slot="countdown" slot-scope="scope">
                Warm up -
                <span class="red--text pl-3">
                  <span v-if="scope.props.hours > 1">
                    {{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                      scope.props.seconds
                    }}
                  </span>

                  <span v-else>
                    {{ scope.props.minutes }}:{{ scope.props.seconds }}
                  </span>
                </span>
              </template>
            </CountDowntimer>
          </v-col>
        </v-row>
        <v-row no-gutters v-else :key="`${item.id}-timer`">
          <v-col cols="2">
            <span :class="textColor">
              {{ item.system.system_name }} - {{ eventType }}:
              {{ this.item.alliance.ticker }}
              <v-avatar size="50"
                ><img :src="this.item.alliance.url" /></v-avatar
            ></span>
          </v-col>
          <v-col cols="3" class="d-flex align-items-center">
            <span class="red--text"> Campaign Over</span>
          </v-col>
        </v-row>
      </transition>
    </v-col>
  </v-row>
</template>



<script>
import Axios from "axios";
// import { EventBus } from "../event-bus";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    item: [Object, Array],
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapGetters([
      "getRedCampaignNodesInfo",
      "getBlueCampaignNodesInfo",
      "getTotalCampaignNodesInfo",
    ]),

    ...mapState([]),

    totalNode() {
      return this.getTotalCampaignNodesInfo(this.item.id);
    },

    redNode() {
      return this.getRedCampaignNodesInfo(this.item.id);
    },

    blueNode() {
      return this.getBlueCampaignNodesInfo(this.item.id);
    },

    eventType() {
      if (this.item.event_type == "32458") {
        return "Ihub";
      } else {
        return "TCU";
      }
    },

    blueProgress() {
      if (this.totalNode) {
        var num = (this.blueNode / this.totalNode) * 100;
        return num;
      } else {
        return 0;
      }
    },

    redProgress() {
      if (this.totalNode) {
        var num = (this.redNode / this.totalNode) * 100;
        return num;
      } else {
        return 0;
      }
    },

    barScoure() {
      var d = this.item.defenders_score * 100;
      var a = this.item.attackers_score * 100;

      if (d > 50) {
        return d;
      }

      return a;
    },

    barReverse() {
      var d = this.item.defenders_score * 100;
      if (d > 50) {
        return false;
      }

      return true;
    },

    barColor() {
      var d = this.item.defenders_score * 100;
      if (d > 50) {
        return "blue darken-4";
      }

      return "red darken-4";
    },

    barBgcolor() {
      var d = this.item.defenders_score * 100;

      if (d > 50) {
        return "red darken-4";
      }
      return "blue darken-4";
    },

    nodesToLose() {
      var needed = 1 - this.item.defenders_score;
      var need = needed / 0.07;
      return Math.ceil(need);
    },

    nodesToWin() {
      var needed = 1 - this.item.attackers_score;
      var need = needed / 0.07;
      return Math.ceil(need);
    },

    IconDColor() {
      if (
        this.item.defenders_score == this.item.defenders_score_old ||
        this.item.defenders_score_old === null
      ) {
        return "grey darken-3";
      } else {
        return "blue darken-4";
      }
    },

    IconAColor() {
      if (
        this.item.attackers_score == this.item.attackers_score_old ||
        this.item.attackers_score_old === null
      ) {
        return "grey darken-3";
      } else {
        return "red darken-4";
      }
    },

    IconD() {
      if (
        this.item.attackers_score == this.item.attackers_score_old ||
        this.item.attackers_score_old === null
      ) {
        return "fa-solid fa-circle-minus";
      } else {
        return "fa-solid fa-circle-up";
      }
    },

    IconDClass() {
      if (
        this.item.defenders_score > this.item.defenders_score_old &&
        this.item.defenders_score_old > 0
      ) {
        return "rotate mr-2";
      } else {
        return "rotate down mr-2";
      }
    },

    IconAClass() {
      if (
        this.item.attackers_score > this.item.attackers_score_old &&
        this.item.attackers_score_old > 0
      ) {
        return "rotate";
      } else {
        return "rotate down ml-2";
      }
    },

    totalNodeDone() {
      return this.totalRedNodeDone + this.totalBlueNodeDone;
    },

    totalRedNodeDone() {
      return this.item.r_node;
    },

    totalBlueNodeDone() {
      return this.item.b_node;
    },

    totalBlueProgress() {
      if (this.totalNodeDone) {
        var num = (this.totalBlueNodeDone / this.totalNodeDone) * 100;
        return num;
      } else {
        return 0;
      }
    },

    totalRedProgress() {
      if (this.totalNodeDone) {
        var num = (this.totalRedNodeDone / this.totalNodeDone) * 100;
        return num;
      } else {
        return 0;
      }
    },

    textColor() {
      if (this.item.alliance.color >= 2) {
        return "blue--text";
      }

      if (this.item.alliance.color < 0) {
        return "red--text";
      }
    },

    showScore() {
      if (this.item.status_id == 2) {
        return true;
      } else {
        return false;
      }
    },
  },
  beforeDestroy() {},
};
</script>
<style scoped>
.rotate {
  -moz-transition: all 1s linear;
  -webkit-transition: all 1s linear;
  transition: all 1s linear;
}

.rotate.down {
  -ms-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  -webkit-transform: rotate(180deg);
  transform: rotate(180deg);
}
</style>
