<template>
  <v-row
    no-gutters
    v-if="this.getCampaignsCount > 1 && campaign.status_id < 3"
    class="pb-2"
    justify="space-around"
  >
    <v-col md="10">
      <v-card class="pr-2 pb-2 pl-2" tile width="100%">
        <v-card-title align="center" class="justify-center align-center">
          <p class="pt-5">
            Campaign page for the
            {{ this.campaign.item_name }} in {{ this.campaign.system }} -
            <v-avatar size="35"><img :src="this.campaign.url" /></v-avatar>
            -
            {{ this.campaign.alliance }} :
          </p>
          <div
            class="d-flex full-width align-content-center"
            v-if="this.campaign.status_id == 1"
          >
            <CountDowntimer
              :start-time="moment.utc(this.campaign.start).unix()"
              :end-text="'Campaign Started'"
              :interval="1000"
              @campaignStart="campaignStart()"
            >
              <template slot="countdown" slot-scope="scope">
                <span
                  class="red--text pl-3 text-h5 justify-content align-center"
                  >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                    scope.props.seconds
                  }}</span
                >
              </template>
            </CountDowntimer>
          </div>
          <div
            class="d-flex full-width align-content-center"
            v-if="this.campaign.status_id == 2"
          >
            <VueCountUptimer
              :start-time="moment.utc(this.campaign.start).unix()"
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
          <div class="d-inline-flex align-center" v-if="nodeCountAll > 0">
            <v-divider class="mx-4 my-0" vertical></v-divider>
            <p class="pt-4 pr-3">Active Nodes -</p>
            <v-progress-circular
              class="pr-3"
              :transitionDuration="5000"
              :radius="25"
              :strokeWidth="5"
              :value="
                (nodeCountHackingCountAll / nodeCountAll) * 100 || 0.000001
              "
            >
              <div class="caption">
                {{ nodeCountHackingCountAll }} /
                {{ nodeCountAll }}
              </div></v-progress-circular
            >
            <v-progress-circular
              :transitionDuration="5000"
              :radius="25"
              :strokeWidth="5"
              strokeColor="#FF3D00"
              :value="
                (nodeRedCountHackingCountAll / nodeCountAll) * 100 || 0.000001
              "
            >
              <div class="caption">
                {{ nodeRedCountHackingCountAll }} /
                {{ nodeCountAll }}
              </div></v-progress-circular
            >
          </div>
          <div
            v-if="campaign.total_node > 0"
            class="d-inline-flex align-center"
          >
            <v-divider class="mx-4 my-0" vertical></v-divider>
            <p class="pt-4 pr-3">Finished Nodes -</p>
            <v-progress-circular
              class="pr-3"
              :transitionDuration="5000"
              :radius="25"
              :strokeWidth="5"
              :value="(campaign.b_node / campaign.total_node) * 100 || 0.000001"
            >
              <div class="caption">
                {{ campaign.b_node }} /
                {{ campaign.total_node }}
              </div></v-progress-circular
            >
            <v-progress-circular
              class="pr-3"
              :transitionDuration="5000"
              :radius="25"
              :strokeWidth="5"
              strokeColor="#FF3D00"
              :value="(campaign.r_node / campaign.total_node) * 100 || 0.000001"
            >
              <div class="caption">
                {{ campaign.r_node }} /
                {{ campaign.total_node }}
              </div></v-progress-circular
            >
          </div>
        </v-card-title>
        <div
          class="d-flex full-width align-content-center"
          v-if="this.campaign.status_id > 1"
        >
          <v-btn
            icon
            dark
            v-if="
              this.campaign.defenders_score >
                this.campaign.defenders_score_old &&
              this.campaign.defenders_score_old > 0
            "
            color="blue darken-4"
          >
            <font-awesome-icon icon="fa-solid fa-circle-up" pull="left"
          /></v-btn>
          <v-btn
            icon
            dark
            color="blue darken-4"
            v-if="
              this.campaign.defenders_score <
                this.campaign.defenders_score_old &&
              this.campaign.defenders_score_old > 0
            "
          >
            <font-awesome-icon icon="fa-solid fa-circle-down" pull="left"
          /></v-btn>
          <v-btn
            icon
            dark
            color="grey darken-3"
            v-if="
              this.campaign.defenders_score ==
                this.campaign.defenders_score_old ||
              this.campaign.defenders_score_old === null
            "
          >
            <font-awesome-icon icon="fa-solid fa-circle-minus" pull="left"
          /></v-btn>

          <v-progress-linear
            :color="this.barColor"
            :value="this.barScoure"
            height="20"
            rounded
            :active="this.barActive"
            :reverse="this.barReverse"
            :background-color="this.barBgcolor"
            background-opacity="0.2"
          >
            <strong>
              {{ this.campaign.defenders_score * 100 }} ({{ nodesToLose }}) /
              {{ this.campaign.attackers_score * 100 }} ({{ nodesToWin }})
            </strong>
          </v-progress-linear>
          <v-btn
            icon
            dark
            color="red darken-4"
            v-if="
              this.campaign.attackers_score >
                this.campaign.attackers_score_old &&
              this.campaign.attackers_score_old > 0
            "
          >
            <font-awesome-icon icon="fa-solid fa-circle-up" pull="right"
          /></v-btn>
          <v-btn
            icon
            dark
            color="red darken-4"
            v-if="
              this.campaign.attackers_score <
                this.campaign.attackers_score_old &&
              this.campaign.attackers_score_old > 0
            "
          >
            <font-awesome-icon icon="fa-solid fa-circle-down" pull="right"
          /></v-btn>
          <v-btn
            icon
            dark
            color="grey darken-3"
            v-if="
              this.campaign.attackers_score ==
                this.campaign.attackers_score_old ||
              this.campaign.attackers_score_old == null
            "
          >
            <font-awesome-icon icon="fa-solid fa-circle-minus" pull="right"
          /></v-btn>
        </div>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  props: {
    sCampaignID: Number,
  },
  data() {
    return {};
  },

  async created() {
    this.campaignId = this.sCampaignID;
    Echo.private("campaignsystem." + this.sCampaignID).listen(
      "CampaignSystemUpdate",
      (e) => {
        if (e.flag.flag == 4) {
          this.$store.dispatch("getCampaigns");
          this.$store.dispatch("getCampaignSystemsRecords");
          this.$store.dispatch("getCampaignJoinData");
        }
      }
    );
    this.channel = "campaignsystem." + this.campaignId;
  },

  methods: {
    async leaving() {
      Echo.leave(this.channel);
    },

    loadCampaignSystemRecords() {
      this.$store.dispatch("getCampaignSystemsRecords");
    },

    async campaignStart() {
      var request = {
        status_id: 2,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/campaigns/" + this.sCampaignID,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      this.$store.dispatch("getCampaigns");

      // this.loadcampaigns()
      // this.$emit("updateNow");
    },
  },

  computed: {
    ...mapGetters([
      "getCampaignById",
      "getActiveCampaigns",
      "getCampaignsCount",
      "getCampaignUsersByUserId",
      "getCampaignUsersByUserIdCount",
      "getTotalNodeCountByCampaign",
      "getHackingNodeCountByCampaign",
      "getRedHackingNodeCountByCampaign",
    ]),

    nodesToWin() {
      var needed = 1 - this.campaign.attackers_score;
      var need = needed / 0.07;
      return Math.ceil(need);
    },

    nodesToLose() {
      var needed = 1 - this.campaign.defenders_score;
      var need = needed / 0.07;
      return Math.ceil(need);
    },

    campaign() {
      return this.getCampaignById(this.sCampaignID);
    },
    barColor() {
      var d = this.getCampaignById(this.sCampaignID).defenders_score * 100;
      if (d > 50) {
        return "blue darken-4";
      }

      return "red darken-4";
    },

    barScoure() {
      var d = this.getCampaignById(this.sCampaignID).defenders_score * 100;
      var a = this.getCampaignById(this.sCampaignID).attackers_score * 100;

      if (d > 50) {
        return d;
      }

      return a;
    },

    barActive() {
      if (this.getCampaignById(this.sCampaignID).status_id > 1) {
        return true;
      }
      return false;
    },

    barBgcolor() {
      var d = this.getCampaignById(this.sCampaignID).defenders_score * 100;
      var a = this.getCampaignById(this.sCampaignID).attackers_score * 100;

      if (d > 50) {
        return "red darken-4";
      }

      return "blue darken-4";
    },

    nodeCountAll() {
      return this.getTotalNodeCountByCampaign(this.sCampaignID);
    },

    nodeCountHackingCountAll() {
      return this.getHackingNodeCountByCampaign(this.sCampaignID);
    },

    nodeRedCountHackingCountAll() {
      return this.getRedHackingNodeCountByCampaign(this.sCampaignID);
    },

    barReverse() {
      var d = this.getCampaignById(this.sCampaignID).defenders_score * 100;
      if (d > 50) {
        return false;
      }

      return true;
    },
  },
  beforeDestroy() {
    this.leaving();
  },
};
</script>
