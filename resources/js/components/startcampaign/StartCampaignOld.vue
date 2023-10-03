<template>
  <v-row class="pr-1 pl-1 pt-3" no-gutters justify="center">
    <v-col cols="10" class="pt-0">
      <v-card elevation="10" rounded="xl">
        <v-card-title class="primary pt-0 pb-0">
          <v-row no-gutters align-content="start" align="center">
            <v-col cols="6">
              <v-col cols="6">
                Initial Campaigns
                <v-btn
                  dark
                  rounded
                  :loading="loadingf"
                  :disabled="loadingf"
                  @click="overlay = !overlay"
                  color="green"
                >
                  ADD CAMPAIGN
                </v-btn></v-col
              >
            </v-col>
          </v-row>
        </v-card-title>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="campaigns"
            fixed-header
            item-key="id"
            :loading="loading"
            :items-per-page="25"
            :footer-props="{ 'items-per-page-options': [15, 25, 50, 100, -1] }"
          >
            <!-- @click:row="rowClick($event)" -->
            <template slot="no-data"> No Multi Campaigns have been made </template>
            <template v-slot:[`item.system`]="{ item }">
              <StartSystemItemList :campaignID="item.id"> </StartSystemItemList>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <v-btn
                icon
                @click="
                  (overlayEditID = item.id),
                    (overlayEditName = item.name),
                    (overlayEdit = !overlayEdit)
                "
                color="warning"
                ><font-awesome-icon icon="fa-solid fa-pen-to-square" size="2xl"
              /></v-btn>
              <v-btn icon @click="deleteCampaign(item)" color="warning"
                ><font-awesome-icon icon="fa-solid fa-trash" size="2xl"
              /></v-btn>
              <v-btn @click="clickCampaign(item)" color="green">View</v-btn>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-col>
    <v-overlay :value="overlay">
      <StartCampaignAdd
        @closeAddNew="updatemultiCampaginAdd()"
        @closeAdd="overlay = !overlay"
      ></StartCampaignAdd>
    </v-overlay>
  </v-row>
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
  props: {
    windowSize: Object,
  },
  data() {
    return {
      loadingr: true,
      loadingf: true,
      loading: true,
      endcount: "",
      search: "",
      componentKey: 0,
      toggle_exclusive: 0,
      colorflag: 4,
      name: "Timer",
      overlay: false,
      overlayEdit: false,
      overlayEditID: "",
      overlayEditName: "",

      //   headers: [
      //     { text: "Name", value: "name", width: "10%" },
      //     {
      //       text: "Constellations - Target",
      //       value: "system",
      //       width: "70%",
      //       align: "center",
      //     },
      //     { text: "", value: "actions", align: "end" },
      //   ],
    };
  },

  created() {
    // this.$store.dispatch("getConstellationList");
    // this.$store.dispatch("getStartCampaigns").then(() => {
    //   this.loadingf = false;
    //   this.loadingr = false;
    //   this.loading = false;
    // });
    // this.loadStartCampaignJoinData();
    // Echo.private("startcampaigns").listen("StartcampaignUpdate", (e) => {
    //   this.$store.dispatch("getStartCampaigns");
    //   this.loadStartCampaignJoinData();
    // });
  },

  async mounted() {},
  methods: {
    updatemultiCampaginAdd() {
      this.overlay = !this.overlay;
      this.$store.dispatch("getStartCampaigns");
      this.loadStartCampaignJoinData();
    },

    clickCampaign(item) {
      this.$router.push({ path: `/scampaign/${item.id}` }); // -> /user/123
    },

    // loadStartCampaignJoinData() {
    //   this.$store.dispatch("getStartCampaignJoinData");
    // },

    async deleteCampaign(item) {
      await axios({
        method: "delete", //you can set what request you want to be
        url: "/api/startcampaigns/" + item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      sleep(500);

      this.$store.dispatch("getStartCampaigns");
    },
  },
  computed: {
    ...mapState(["startcampaigns"]),
    height() {
      let num = this.windowSize.y - 375 * 2;
      return num;
    },
    campaigns() {
      return this.startcampaigns;
    },
  },
  beforeDestroy() {
    Echo.leave("startcampaigns");
  },
};
</script>
