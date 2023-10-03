<template>
  <div>
    <v-row
      no-gutters
      v-if="this.getCampaignsCount > 1"
      class="pb-2"
      justify="space-around"
    >
      <v-col md="10" v-if="campaignNameCount > 0">
        <v-card class="pr-2 pb-2 pl-2" tile width="100%">
          <v-card-title align="center" class="justify-center align-center"
            >MultiCampaign - {{ campaignName[0]["name"] }}
          </v-card-title>
        </v-card>
      </v-col>
    </v-row>
    <span v-for="(sCampaign, index) in sCampaigns" :key="index">
      <TitleBar
        :sCampaignID="sCampaign.campaign_id"
        :sCampaign="sCampaigns"
        @updateNow="updateBar()"
      >
      </TitleBar>
    </span>

    <v-row no-gutters v-if="this.getCampaignsCount > 1" justify="space-around">
      <v-col md="10">
        <v-card
          class="pa-2 d-flex justify-space-between full-width align-center"
          tile
        >
          <div class="d-md-inline-flex">
            <v-btn
              class="mr-4"
              color="blue darken-2"
              v-if="showTable == false"
              @click="showTable = true"
              >Show Char table</v-btn
            >
            <v-btn
              class="mr-4"
              color="orange darken-2"
              v-if="showTable == true"
              @click="showTable = false"
              >Hide Char table</v-btn
            >
            <UsersChars :campaign_id="campaign_id"> </UsersChars>

            <WatchUserTable
              :campaign_id="this.campaign_id"
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

            <v-btn v-if="$can('super')" @click="overlay = !overlay">
              test
            </v-btn>
            <v-tooltip bottom color="#121212">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-if="$can('access_campaigns')"
                  fab
                  dark
                  small
                  class="mr-4"
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
                <p class="text-md-center">
                  This will kicked everyone (you also) from the page and Delete
                  the Campagin.<strong>Press when hack is over.</strong>
                </p>
              </span>
            </v-tooltip>
          </div>
          <v-spacer></v-spacer>
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
        </v-card>
      </v-col>
      <div v-if="campaignWarmup == false" class="pt-10">
        <v-card>
          <v-card-title class="justify-center"
            ><h1>No Campaigns are open for setup yet</h1></v-card-title
          >
          <v-card-text>
            <span class="body-1">
              <p class="text-md-center">
                Systems will start to showup when a Campaign has less than
                <strong> One Hour</strong> to start
              </p>
            </span>
          </v-card-text>
        </v-card>
      </div>
    </v-row>

    <v-row no-gutters justify="space-around" v-if="showTable == true">
      <UserTable :campaign_id="campaign_id"> </UserTable>
    </v-row>

    <v-row no-gutters justify="center" :v-if="systemLoaded == true">
      <MultiSystemTable
        class="px-5 pt-5"
        v-for="(system, index) in systems"
        :system_name="system.system_name"
        :constellation_name="system.constellation_name"
        :system_id="system.id"
        :campaign_id="campaignId"
        :constellation_id="system.constellation_id"
        :index="index"
        :key="system.id"
        @openSolaLog="openSolaLog($event)"
      >
      </MultiSystemTable>
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
    <v-overlay z-index="5" :value="showLog">
      <CampaignLogging
        v-if="$can('view_campaign_logs')"
        @closeLog="showLog = false"
        :campaign_id="campaign_id"
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
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {
    return `EveStuff - ${this.$route.params.name}`;
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

      newCharName: null,
      newNameRules: [(v) => !!v || "Name is required"],
      newRole: null,
      newRoleRules: [(v) => !!v || "You need to pick a role"],
      newShip: null,
      newShipRules: [(v) => !!v || "Ship is required"],
      newLink: null,
      newLinkRules: [(v) => !!v || "T1 or T2?"],

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
      siteTitle: "eve",
      oldChar: [],
      role: 0,
      editrole: 0,
      systems: [],
      test: 1,
      test2: "",
      valid: false,
      addShown: false,
      removeShown: false,
      showTable: false,
      siteName: "eve",
      systemLoaded: false,
      campaignId: 0,
      campaign_id: "",
      showUsers: false,
      sitetest: "testf",
      channel: "",
      overlay: false,
      bullhorn: false,
      nodeItem: null,
      showLog: false,
      solalog: false,
      solaid: 0,
      load: 0,
      logName: null,
      solaName: null,
    };
  },

  async created() {
    this.campaignId = this.$route.params.id;
    this.campaign_id = parseInt(this.$route.params.id);
    if (this.$can("view_campaign_logs")) {
      Echo.private("campaignlogs." + this.campaignId).listen(
        "LoggingUpdate",
        (e) => {
          this.$store.dispatch("addLoggingCampaign", e.flag.message);
        }
      );
    }

    Echo.private("campaignsystem." + this.$route.params.id)
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
        if (e.flag.flag == 11) {
          if (this.$store.getters.getCampaignsCount == 0) {
            this.$store.dispatch("getCampaigns");
          }
          if (this.$store.getters.getMultiCampaignsCount == 0) {
            this.$store.dispatch("getMultiCampaigns");
          }
          this.$store.dispatch(
            "getCampaignJoinDataByCampaign",
            this.$route.params.id
          );
          let payload = {
            campaign_id: this.$route.params.id,
            user_id: this.$store.state.user_id,
            type: 2,
          };
          this.$store.dispatch("loadCampaignSystemData", payload);
          this.getSystems(this.campaignId);
          this.addMember();
          this.$store.dispatch("getCampaignSolaSystems");
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
    this.channel = "campaignsystem." + this.campaignId;
    this.logchannel = "campaignlogs." + this.campaignId;
    this.navdrawer = true;
  },

  beforeMonunt() {},

  async beforeCreate() {
    await this.$store.dispatch("getMultiCampaigns");
    await this.$store.dispatch("getCampaigns");
  },

  async mounted() {
    this.log();
    if (this.$store.getters.getCampaignsCount == 0) {
      await this.$store.dispatch("getCampaigns");
    }
    if (this.$store.getters.getMultiCampaignsCount == 0) {
      await this.$store.dispatch("getMultiCampaigns");
    }

    await this.$store.dispatch(
      "getCampaignJoinDataByCampaign",
      this.$route.params.id
    );

    let payload = {
      campaign_id: this.$route.params.id,
      user_id: this.$store.state.user_id,
      type: 2,
    };
    await this.$store.dispatch("loadCampaignSystemData", payload);
    await this.getSystems(this.campaignId);
    await this.addMember();
    await this.$store.dispatch("getCampaignSolaSystems");
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
    updateBar() {
      this.loadcampaigns();
    },
    checkAddUser() {
      if (this.userCount == 0) {
        this.bullhorn = true;
      }
    },

    openSolaLog(item) {
      this.solaid = item.solaid;
      this.solaName = item.solaName;
      this.solalog = true;
    },

    async loadCampaignlogs() {
      if (this.$can("view_campaign_logs")) {
        await this.$store.dispatch("getLoggingCampaign", this.campaignId);
      }
    },

    async loadCampaigns() {
      await this.$store.dispatch("getCampaigns");
    },

    async loadUsersRecords() {
      await this.$store.dispatch("getCampaignUsersRecords", this.campaignId);
    },

    async loadCampaignSolaSystems() {
      await this.$store.dispatch("getCampaignSolaSystems");
    },
    async loadCampaignSystemRecords() {
      await this.$store.dispatch("getCampaignSystemsRecords");
    },

    async loadcampaigns() {
      this.loadingr = true;
      await this.$store.dispatch("getCampaigns").then(() => {
        this.loadingr = false;
      });
    },

    async loadCampaignNodeJoin() {
      await this.$store.dispatch("getNodeJoinByCampaignId", this.campaignId);
    },

    async sendAddCharMessage() {
      await axios({
        method: "get",
        url: "/api/campaignsystemcheckaddchar/" + this.campaignId,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async getSystems(id) {
      let res = await axios({
        method: "get",
        url: "/api/campaignjoinsystems/" + id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      this.systems = res.data.systems;
      this.systemLoaded = true;
    },

    async finishCampaign() {
      await axios({
        method: "get",
        url: "/api/mcampaignsystemfinished/" + this.$route.params.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      await axios({
        method: "delete",
        url: "/api/multicampaigns/" + this.$route.params.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      this.$router.push("/campaignfinished");
    },

    async addMember() {
      let user_id = this.$store.state.user_id;
      var request = {
        user_id: user_id,
        campaign_id: this.$route.params.id,
        type: 2,
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
          "/api/mcheckjoinleavecampaign/" +
          this.campaignId +
          "/" +
          this.$store.state.user_id +
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
          "/api/mcheckjoinleavecampaign/" +
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
        campaign_id: this.campaignId,
        char_name: this.newCharName,
        link: this.newLink,
        ship: this.newShip,
        campaign_role_id: this.newRole,
      };

      await axios({
        method: "POST",
        url: "/api/campaignusers/" + this.campaignId,
        data: request,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.$store.dispatch("getCampaignUsersRecords", this.campaignId);
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
        url: "/api/campaignusers/" + this.oldChar.id + "/" + this.campaignId,
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
        url: "/api/campaignusers/" + this.oldChar.id + "/" + this.campaignId,
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

      this.$store.dispatch("getCampaignUsersRecords", this.campaignId);
      this.$store.dispatch("getCampaignSystemsRecords");
    },
  },

  computed: {
    ...mapGetters([
      "getsCampaignById",
      "getActiveCampaigns",
      "getCampaignsCount",
      "getCampaignUsersByUserId",
      "getCampaignUsersByUserIdCount",
      "getTotalNodeCountByMultiCampaign",
      "getHackingNodeCountByMultiCampaign",
      "getRedHackingNodeCountByMultiCampaign",
      "getMultiCampaignName",
    ]),

    ...mapState(["campaignJoin"]),

    sCampaigns() {
      return this.getsCampaignById(this.campaignId);
    },
    //changeback
    campaignWarmup() {
      let count = this.getsCampaignById(this.campaignId).filter(
        (c) => c.warmup == 1
      ).length;
      if (count > 0) {
        return true;
      } else {
        return false;
      }
    },

    campaignName() {
      return this.getMultiCampaignName(this.$route.params.id);
    },

    campaignNameText() {
      return this.campaignName[0]["name"];
    },

    campaignNameCount() {
      return this.getMultiCampaignName(this.$route.params.id).length;
    },

    userCharsDrop() {
      return this.getCampaignUsersByUserId(this.$store.state.user_id);
    },

    userCount() {
      return this.getCampaignUsersByUserIdCount(this.$store.state.user_id);
    },

    nodeCountAll() {
      return this.getTotalNodeCountByMultiCampaign(this.campaignId);
    },

    nodeCountHackingCountAll() {
      return this.getHackingNodeCountByMultiCampaign(this.campaignId);
    },

    nodeRedCountHackingCountAll() {
      return this.getRedHackingNodeCountByMultiCampaign(this.campaignId);
    },
  },
  beforeDestroy() {
    this.leaving();
    window.removeEventListener("beforeunload", this.leaving);
  },
};
</script>
