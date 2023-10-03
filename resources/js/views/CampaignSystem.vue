<template>
  <div v-if="this.load == 1">
    <v-row
      no-gutters
      v-if="this.getCampaignsCount > 1"
      class="pt-5"
      justify="space-around"
    >
      <v-col md="10">
        <v-card class="pa-2" tile width="100%">
          <v-card-title align="center" class="justify-center">
            <h1>
              Campaign page for the
              {{ this.campaign.item_name }} in {{ this.campaign.system }} -
              <v-avatar size="35"><img :src="this.campaign.url" /></v-avatar>
              -
              {{ this.campaign.alliance }}
            </h1>
          </v-card-title>
          <div
            class="d-flex full-width align-content-center"
            v-if="this.campaign.status_id > 1"
          >
            <span
              dark
              color="blue darken-4"
              v-if="
                this.campaign.defenders_score >
                  this.campaign.defenders_score_old &&
                this.campaign.defenders_score_old > 0
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-up" pull="left"
            /></span>
            <span
              dark
              color="blue darken-4"
              v-if="
                this.campaign.defenders_score <
                  this.campaign.defenders_score_old &&
                this.campaign.defenders_score_old > 0
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-down" pull="left"
            /></span>
            <span
              dark
              color="grey darken-3"
              v-if="
                this.campaign.defenders_score ==
                  this.campaign.defenders_score_old ||
                this.campaign.defenders_score_old === null
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-minus" pull="left"
            /></span>

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
            <span
              dark
              color="red darken-4"
              v-if="
                this.campaign.attackers_score >
                  this.campaign.attackers_score_old &&
                this.campaign.attackers_score_old > 0
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-up" pull="right"
            /></span>
            <span
              dark
              color="red darken-4"
              v-if="
                this.campaign.attackers_score <
                  this.campaign.attackers_score_old &&
                this.campaign.attackers_score_old > 0
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-down" pull="right"
            /></span>
            <span
              dark
              color="grey darken-3"
              v-if="
                this.campaign.attackers_score ==
                  this.campaign.attackers_score_old ||
                this.campaign.attackers_score_old == null
              "
            >
              <font-awesome-icon icon="fa-solid fa-circle-minus" pull="right"
            /></span>
          </div>
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
                <span class="red--text pl-3 text-h3 justify-content"
                  >{{ scope.props.minutes }}:{{ scope.props.seconds }}</span
                >
              </template>
            </CountDowntimer>
          </div>
        </v-card>
      </v-col>
    </v-row>
    <v-row no-gutters v-if="this.getCampaignsCount > 1" justify="space-around">
      <v-col md="10">
        <v-card
          class="pa-2 d-flex justify-space-between full-width align-center"
          tile
          :key="'buttoncard' + campaign.id"
        >
          <div class="d-md-inline-flex">
            <v-tooltip
              color="#121212"
              bottom
              :open-delay="2000"
              :disabled="$store.state.tooltipToggle"
            >
              <template v-slot:activator="{ on: tooltip, attrs: atooltip }">
                <v-btn
                  class="mr-4"
                  :key="'showchartable' + campaign.id"
                  color="blue darken-2"
                  v-on="{ ...tooltip }"
                  v-bind="{ ...atooltip }"
                  v-show="showTable == false"
                  @click="showTable = true"
                  >Show Char table</v-btn
                >
              </template>
              <span
                >Show/Hide the table which shows info on all active characters
                in the campaign</span
              >
            </v-tooltip>
            <v-btn
              class="mr-4"
              color="orange darken-2"
              :key="'hidechartable' + campaign.id"
              v-show="showTable == true"
              @click="showTable = false"
              >Hide Char table</v-btn
            >

            <v-tooltip
              color="#121212"
              bottom
              :open-delay="2000"
              :disabled="$store.state.tooltipToggle"
            >
              <template v-slot:activator="{ on: tooltip, attrs: atooltip }">
                <div v-on="{ ...tooltip }" v-bind="{ ...atooltip }">
                  <UsersChars :campaign_id="campaign.id">
                  </UsersChars></div></template
              ><span>
                Where you will find all your saved characters. Or make new
                ones</span
              ></v-tooltip
            >
            <v-tooltip
              color="#121212"
              bottom
              :open-delay="2000"
              :disabled="$store.state.tooltipToggle"
            >
              <template v-slot:activator="{ on: tooltip, attrs: atooltip }">
                <div v-on="{ ...tooltip }" v-bind="{ ...atooltip }">
                  <CampaignMapSystem
                    :region_name="campaign.region"
                    :constellation="campaign.constellation"
                  ></CampaignMapSystem>
                </div>
              </template>
              <span>
                Will open a DotLan page highlighting all systems in the
                constellation
              </span>
            </v-tooltip>
            <WatchUserTable
              :campaign_id="campaign.id"
              v-if="$can('view_campaign_members')"
            >
            </WatchUserTable>

            <v-btn
              v-if="$can('view_campaign_logs')"
              @click="showLog = true"
              class="mr-4"
              color="blue"
            >
              Campaign Logs
            </v-btn>
            <v-btn v-if="$can('super')" @click="showNotes = !showNotes">
              test
            </v-btn>
            <v-tooltip bottom color="#121212">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-if="$can('access_campaigns')"
                  fab
                  dark
                  class="mr-4"
                  small
                  v-bind="attrs"
                  v-on="on"
                  @click="sendAddCharMessage()"
                >
                  <font-awesome-icon icon="fa-solid fa-bullhorn" />
                </v-btn>
              </template>
              <span> Send a message to all Users without a Char added </span>
            </v-tooltip>
            <v-tooltip bottom color="#121212">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-if="$can('view_campaign_members')"
                  dark
                  color="red"
                  class="mr-4"
                  v-bind="attrs"
                  v-on="on"
                  @click="finishCampaign()"
                >
                  Campaign Over
                </v-btn>
              </template>
              <span>
                This will kicked everyone (you also) from the page. Press when
                hack is over.
              </span>
            </v-tooltip>
          </div>
          <v-spacer></v-spacer>
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
                <span class="red--text pl-3"
                  >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                    scope.props.seconds
                  }}</span
                >
              </template>
            </VueCountUptimer>
          </div>
          <div
            class="ml-auto d-inline-flex align-center"
            v-if="nodeCountAll > 0"
          >
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
            class="ml-auto d-inline-flex align-center"
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
        </v-card>
      </v-col>
    </v-row>

    <v-row no-gutters justify="space-around" v-show="showTable == true">
      <UserTable :key="'chartable' + campaign.id" :campaign_id="campaign.id">
      </UserTable>
    </v-row>

    <v-row no-gutters justify="center" :v-if="systemLoaded == true">
      <SystemTable
        class="px-5 pt-5"
        v-for="(system, index) in systems"
        :system_name="system.system_name"
        :system_id="system.id"
        :campaign_id="campaign.id"
        :index="index"
        :key="system.id"
        @openAdd="openAdd($event)"
        @openSolaLog="openSolaLog($event)"
      >
      </SystemTable>
    </v-row>

    <v-overlay z-index="0" :value="bullhorn">
      <v-card>
        <v-card-title> MAKE SURE TO ADD A CHARACTER </v-card-title>
        <v-card-text>
          Remeber to add any chars you have in the campaign by pressing the
          green "CHARACTER" button</v-card-text
        >
        <v-card-actions>
          <v-btn
            class="white--text"
            color="teal"
            @click="(bullhorn = false), (overlay = true)"
          >
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-overlay>

    <v-overlay z-index="0" :value="showNotes">
      <ShowNotes :campaign_id="campaign.id" @closeNotes="showNotes = false">
      </ShowNotes>
    </v-overlay>
    <v-overlay z-index="5" :value="showLog">
      <CampaignLogging
        v-if="$can('view_campaign_logs')"
        @closeLog="showLog = false"
        :campaign_id="campaign.id"
        :name="campaignName"
      >
      </CampaignLogging>
    </v-overlay>
    <v-overlay z-index="0" :value="solalog">
      <SolaSystemLogging
        :solaID="solaid"
        :name="solaName"
        v-if="$can('view_campaign_logs')"
        @closeSolaLog="solalog = false"
      >
      </SolaSystemLogging>
    </v-overlay>
  </div>
</template>
<!-- {{ $route.params.id }} - {{ test }} -  -->
<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
import moment from "moment";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `EveStuff - ${this.campaign.system} - ${this.campaign.item_name}`;
  },
  data() {
    return {
      dropdown_roles: [
        { text: "Hacker", value: 1 },
        { text: "Support", value: 5 },
        { text: "Scout", value: 2 },
        { text: "FC", value: 3 },
        { text: "Command", value: 4 },
      ],
      dropdown_status: [
        { text: "None", value: 1 },
        { text: "On the way", value: 2 },
        { text: "Ready to go", value: 3 },
      ],

      addShown: false,

      bullhorn: false,

      campaignId: 0,
      channel: "",

      editCharName: null,
      editNameRules: [(v) => !!v || "Name is required"],
      editRole: null,
      editTextRole: null,
      editRoleRules: [(v) => !!v || "You need to pick a role"],
      editShip: null,
      editTextShip: null,
      editShipRules: [(v) => !!v || "Ship is required"],
      editLink: null,
      editTextLink: null,
      editLinkRules: [(v) => !!v || "T1 or T2?"],
      editUserForm: 1,
      editrole_name: null,
      editrole: 0,

      link: "",
      load: 0,

      newCharName: null,
      newNameRules: [(v) => !!v || "Name is required"],
      newRole: null,
      newRoleRules: [(v) => !!v || "You need to pick a role"],
      newShip: null,
      newShipRules: [(v) => !!v || "Ship is required"],
      newLink: null,
      newLinkRules: [(v) => !!v || "T1 or T2?"],

      nodeItem: null,
      nodeNoteItem: [],

      oldChar: [],
      overlay: false,

      removeShown: false,
      role: 0,

      showNotes: false,
      showTable: false,
      showUsers: false,
      showLog: false,
      showNodeNotes: false,
      solalog: false,
      solaid: 0,
      solaName: null,
      systemLoaded: false,
      systems: [],

      test: 1,
      test2: "",

      valid: false,
    };
  },

  async created() {
    if (this.$store.getters.getCampaignsCount == 0) {
      await this.$store.dispatch("getCampaigns");
    }

    if (this.$store.getters.getCampaignsCount == 0) {
      await this.$store.dispatch("getCampaigns");
    }

    this.campaignId = this.campaign.id;
    if (this.$can("view_campaign_logs")) {
      Echo.private("campaignlogs." + this.campaign.id).listen(
        "LoggingUpdate",
        (e) => {
          this.$store.dispatch("addLoggingCampaign", e.flag.message);
        }
      );
    }
    // this.joinlogchannel();
    Echo.private("campaignsystem." + this.campaign.id)
      .listen("CampaignSystemUpdate", (e) => {
        if (e.flag.message != null) {
          this.$store.dispatch("updateCampaignSystem", e.flag.message);
        }

        if (e.flag.flag == 2) {
          this.loadCampaignSystemRecords();
          this.loadCampaignNodeJoin();
        }
        if (e.flag.flag == 3) {
          this.loadCampaignSystemRecords();
          this.loadUsersRecords();
          this.loadCampaignNodeJoin();
        }
        if (e.flag.flag == 4) {
          this.loadcampaigns();
          this.loadCampaignSystemRecords();
          this.loadUsersRecords();
          this.loadCampaignNodeJoin();
        }
        if (e.flag.flag == 5) {
          this.checkAddUser();
        }

        if (e.flag.flag == 7) {
          this.$router.push("/campaignfinished");
        }

        if (e.flag.flag == 8) {
          this.loadCampaignSolaSystems();
        }

        if (e.flag.flag == 9) {
          this.loadCampaignSolaSystems();
          this.loadCampaignSystemRecords();
          this.loadCampaignNodeJoin();
        }

        if (e.flag.flag == 10) {
          this.loadCampaignlogs();
        }
      })
      .listen("CampaignUserNew", (e) => {
        this.$store.dispatch("addCampaignUserNew", e.flag.message);
      })
      .listen("CampaignUserDelete", (e) => {
        this.$store.dispatch("deleteCampaignUser", e.flag.userid);
      })
      .listen("KickUserFromCampaign", (e) => {
        if (this.$store.state.user_id == e.flag.user_id) {
          this.$router.push("/campaignkick");
        }
      })
      .listen("CampaignSolaSystemUpdate", (e) => {
        this.$store.dispatch("updateCampaignSolaSystem", e.flag.message);
      })
      .listen("CampaignUserUpdate", (e) => {
        if (e.flag.message != null) {
          this.$store.dispatch("updateCampaignUsers", e.flag.message);
        }
      })
      .listen("NodeJoinDelete", (e) => {
        this.$store.dispatch("deleteNodeJoin", e.flag.joinNodeID);
      })
      .listen("NodeJoinNew", (e) => {
        this.$store.dispatch("addNodeJoin", e.flag.message);
      })
      .listen("NodeJoinUpdate", (e) => {
        this.$store.dispatch("updateNodeJoin", e.flag.message);
      })
      .listen("CampaignSystemDelete", (e) => {
        this.$store.dispatch("deleteCampaignSystem", e.flag.campSysID);
      })
      .listen("CampaignSystemNew", (e) => {
        this.$store.dispatch("addCampaignSystem", e.flag.message);
      })
      .listen("CampaignUpdate", (e) => {
        this.$store.dispatch("updateCampaign", e.flag.message);
      });

    window.addEventListener("beforeunload", this.leaving);
    this.channel = "campaignsystem." + this.campaign.id;
    this.logchannel = "campaignlogs." + this.campaign.id;
    this.test = 2;
    this.test2 = 1;
    this.navdrawer = true;
    this.addMember();
  },

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {
    this.log();
    let payload = {
      campaign_id: this.$route.params.id,
      user_id: this.$store.state.user_id,
      type: 1,
    };
    await this.$store.dispatch("loadCampaignSystemData", payload);
    await this.getSystems();
  },
  methods: {
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

    checkAddUser() {
      if (this.userCount == 0) {
        this.bullhorn = true;
      }
    },

    openSolaLog(item) {
      this.solaid = item.solaID;
      this.solaName = item.solaName;
      this.solalog = true;
    },

    closeNodeMessage() {
      this.showNodeNotes = false;
    },

    async finishCampaign() {
      await axios({
        method: "get",
        url: "/api/campaignsystemfinished/" + this.campaign.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      this.$router.push("/campaignfinished");
    },

    async loadCampaigns() {
      await this.$store.dispatch("getCampaigns");
    },

    async loadUsersRecords() {
      await this.$store.dispatch("getCampaignUsersRecords", this.campaign.id);
    },

    async loadCampaignSolaSystems() {
      await this.$store.dispatch("getCampaignSolaSystems");
    },

    async loadCampaignlogs() {
      if (this.$can("view_campaign_logs")) {
        await this.$store.dispatch("getLoggingCampaign", this.campaign.id);
      }
    },

    async loadCampaignSystemRecords() {
      await this.$store.dispatch("getCampaignSystemsRecords");
    },

    async loadCampaignNodeJoin() {
      await this.$store.dispatch("getNodeJoinByCampaignId", this.campaign.id);
    },

    async loadcampaigns() {
      this.loadingr = true;
      await this.$store.dispatch("getCampaigns").then(() => {
        this.loadingr = false;
      });
    },

    async sendAddCharMessage() {
      await axios({
        method: "get",
        url: "/api/campaignsystemcheckaddchar/" + this.campaign.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async getSystems() {
      let res = await axios({
        method: "get",
        url: "/api/systemsinconstellation/" + this.$route.params.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      this.systems = res.data.systems;
      this.systemLoaded = true;
    },

    joinlogchannel() {
      if (this.$can("view_campaign_logs")) {
        Echo.private("campaignlogs." + this.campaign.id);
      }
    },

    async addMember() {
      let user_id = this.$store.state.user_id;
      if (user_id == 0) {
        await sleep(1000);
        user_id = this.$store.state.user_id;
        if (user_id == 0) {
          await sleep(1000);
          user_id = this.$store.state.user_id;
        }
      }
      var request = {
        user_id: user_id,
        campaign_id: this.$route.params.id,
        type: 1,
      };

      await axios({
        method: "POST",
        url: "/api/campaignsystemusers/" + this.$route.params.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      await axios({
        method: "GET",
        url:
          "/api/checkjoinleavecampaign/" +
          this.campaign.id +
          "/" +
          user_id +
          "/4",
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async leaving() {
      Echo.leave(this.channel);
      Echo.leave(this.logchannel);
      await axios({
        method: "delete",
        url:
          "/api/campaignsystemusers/" +
          this.$store.state.user_id +
          "/" +
          this.campaignId,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      await axios({
        method: "GET",
        url:
          "/api/checkjoinleavecampaign/" +
          this.campaignId +
          "/" +
          this.$store.state.user_id +
          "/5",
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    roleForm(a) {
      this.role = a;
    },

    newCharFormClose() {
      this.addShown = false;
      this.newCharName = null;
      this.newRole = null;
      this.newShip = null;
      this.newLink = null;
    },

    roleEditForm(a) {
      this.editrole = a;
    },

    charEditForm($event) {
      this.oldChar = this.userCharsDrop.find((user) => user.id == $event);
      this.editRole = this.oldChar.role_id;
      this.editTextShip = this.oldChar.ship;
      this.editTextLink = this.oldChar.link;
      if (this.oldChar.role_id == 1) {
        this.editrole = 1;
      } else {
        this.editrole = 0;
      }
    },

    editFormClose() {
      this.removeShown = false;
      this.editCharName = null;
      this.editRole = null;
      this.editTextRole = null;
      this.editShip = null;
      this.editTextShip = null;
      this.editLink = null;
      this.editTextLink = null;
      this.editrole = null;
    },

    async newCharForm() {
      var request = {
        site_id: this.$store.state.user_id,
        campaign_id: this.campaign.id,
        char_name: this.newCharName,
        link: this.newLink,
        ship: this.newShip,
        campaign_role_id: this.newRole,
      };

      await axios({
        method: "POST",
        url: "/api/campaignusers/" + this.campaign.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.$store.dispatch("getCampaignUsersRecords", this.campaign.id);
      this.role = null;
      this.newCharName = null;
      this.newLink = null;
      this.newShip = null;
      this.newRole = null;
      this.addShown = false;
    },

    editCharForm() {
      this.removeShown = false;

      var link = this.oldChar.link;
      var ship = this.oldChar.ship;
      var role = this.oldChar.role_id;
      var role_name = this.oldChar.role_name;

      if (this.oldChar.role_id != this.editRole) {
        var role = this.editRole;
        var role_name = this.dropdown_roles.find(
          (droprole) => droprole.value == role
        ).text;
      }
      if (this.oldChar.ship != this.editShip) {
        var ship = this.editShip;
      }
      if (this.oldChar.link != this.editLink) {
        var link = this.editLink;
      }
      var request = {
        link: link,
        ship: ship,
        campaign_role_id: role,
      };

      var item = {
        id: this.oldChar.id,
        link: link,
        ship: ship,
        role_id: role,
        role_name: role_name,
      };

      this.$store.dispatch("updateCampaignUsers", item);

      axios({
        method: "PUT",
        url: "/api/campaignusers/" + this.oldChar.id + "/" + this.campaign.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      this.editCharName = null;
      this.editRole = null;
      this.editTextRole = null;
      this.editShip = null;
      this.editTextShip = null;
      this.editLink = null;
      this.editTextLink = null;
    },

    async editFormRemove() {
      await axios({
        method: "DELETE",
        url:
          "/api/campaignusers/" +
          this.oldChar.id +
          "/" +
          this.campaign.id +
          "/" +
          this.$store.state.user_id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.removeShown = false;
      this.editCharName = null;
      this.editRole = null;
      this.editTextRole = null;
      this.editShip = null;
      this.editTextShip = null;
      this.editLink = null;
      this.editTextLink = null;

      this.$store.dispatch("getCampaignUsersRecords", this.campaign.id);
      this.$store.dispatch("getCampaignSystemsRecords");
    },
    campaignStart() {
      var data = {
        id: this.campaign.id,
        status_id: 2,
        status_name: "Active",
      };
      this.$store.dispatch("updateCampaignSystem", data);
      this.$store.dispatch("updateCampaign", data);
    },
  },

  computed: {
    ...mapGetters([
      "getCampaignById",
      "getCampaignByLink",
      "getActiveCampaigns",
      "getCampaignsCount",
      "getCampaignUsersByUserId",
      "getCampaignUsersByUserIdCount",
      "getTotalNodeCountByCampaign",
      "getHackingNodeCountByCampaign",
      "getRedHackingNodeCountByCampaign",
      "getLoggingCampaignByCampaign",
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
      var d = this.getCampaignByLink(this.$route.params.id);
      this.load = 1;
      return d;
    },

    campaignName() {
      return this.campaign.item_name + " in " + this.campaign.system;
    },

    userCharsDrop() {
      // let payload = {
      //     id: this.$store.state.user_id,
      //     campaignID: this.$route.params.id}
      return this.getCampaignUsersByUserId(this.$store.state.user_id);
    },

    userCount() {
      return this.getCampaignUsersByUserIdCount(this.$store.state.user_id);
    },
    barScoure() {
      var d = this.getCampaignById(this.campaign.id).defenders_score * 100;
      var a = this.getCampaignById(this.campaign.id).attackers_score * 100;

      if (d > 50) {
        return d;
      }

      return a;
    },

    barBgcolor() {
      var d = this.getCampaignById(this.campaign.id).defenders_score * 100;
      var a = this.getCampaignById(this.campaign.id).attackers_score * 100;

      if (d > 50) {
        return "red darken-4";
      }

      return "blue darken-4";
    },

    barColor() {
      var d = this.getCampaignById(this.campaign.id).defenders_score * 100;
      if (d > 50) {
        return "blue darken-4";
      }

      return "red darken-4";
    },

    barReverse() {
      var d = this.getCampaignById(this.campaign.id).defenders_score * 100;
      if (d > 50) {
        return false;
      }

      return true;
    },

    barActive() {
      if (this.getCampaignById(this.campaign.id).status_id > 1) {
        return true;
      }
      return false;
    },
    nodeCountAll() {
      return this.getTotalNodeCountByCampaign(this.campaign.id);
    },

    nodeCountHackingCountAll() {
      return this.getHackingNodeCountByCampaign(this.campaign.id);
    },

    nodeRedCountHackingCountAll() {
      return this.getRedHackingNodeCountByCampaign(this.campaign.id);
    },
  },
  beforeDestroy() {
    this.leaving();
    window.removeEventListener("beforeunload", this.leaving);
  },
};
</script>
