<template>
  <div class="pr-16 pl-16">
    <div class="d-flex align-items-center">
      <v-card-title>Campaigns</v-card-title>

      <v-btn
        :loading="loadingf"
        :disabled="loadingf"
        @click="overlay = !overlay"
        color="light-blue darken-4"
      >
        ADD CAMPAIGN
      </v-btn>
    </div>
    <v-data-table
      :headers="headers"
      :items="campaigns"
      item-key="id"
      :loading="loading"
      :items-per-page="25"
      :footer-props="{ 'items-per-page-options': [15, 25, 50, 100, -1] }"
      class="elevation-1"
    >
      <!-- @click:row="rowClick($event)" -->
      <template slot="no-data"> No Multi Campaigns have been made </template>
      <template v-slot:[`item.system`]="{ item }">
        <SystemItemList :campaignID="item.id"> </SystemItemList>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <div class="d-inline-flex">
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
          <DeleteButton :item="item"></DeleteButton>
          <v-btn @click="clickCampaign(item)" color="green">View</v-btn>
        </div>
      </template>

      <!-- <template v-slot:actions.="{ item }">
                LALALALA
            </template> -->
    </v-data-table>
    <v-overlay :value="overlay">
      <MultiCampaignAdd
        @closeAddNew="updatemultiCampaginAdd()"
        @closeAdd="overlay = !overlay"
      ></MultiCampaignAdd>
    </v-overlay>
    <v-overlay :value="overlayEdit">
      <MultiCampaignEdit
        :campaignID="getCampaignID()"
        :nameProp="getCampaignName()"
        @closeEditNew="updatemultiCampaginEdit()"
        @closeEdit="overlayEdit = !overlayEdit"
      ></MultiCampaignEdit>
    </v-overlay>
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
      colorflag: 4,
      name: "Timer",
      overlay: false,
      overlayEdit: false,
      overlayEditID: "",
      overlayEditName: "",

      headers: [
        { text: "Name", value: "name", width: "10%" },
        {
          text: "System - Target",
          value: "system",
          width: "70%",
          align: "center",
        },
        { text: "Status", value: "status_name", align: "end" },
        { text: "", value: "actions", align: "end" },
        // { text: "", value: "actions" },
      ],
    };
  },

  created() {
    this.$store.dispatch("getMultiCampaigns").then(() => {
      this.loadingf = false;
      this.loadingr = false;
      this.loading = false;
    });
    this.loadCampaignJoinData();

    Echo.private("multicampaigns").listen("MulticampaignUpdate", (e) => {
      this.$store.dispatch("getMultiCampaigns");
      this.loadCampaignJoinData();
    });
  },

  async mounted() {},
  methods: {
    async loadcampaigns() {
      this.loadingr = true;
      this.$store.dispatch("getCampaigns").then(() => {
        this.loadingr = false;
      });
    },

    clickCampaign(item) {
      this.$router.push({ path: `/mcampaign/${item.id}/${item.name}` }); // -> /user/123
    },

    loadCampaignJoinData() {
      this.$store.dispatch("getCampaignJoinData");
    },

    updatemultiCampaginAdd() {
      this.overlay = !this.overlay;
      this.$store.dispatch("getMultiCampaigns");
      this.loadCampaignJoinData();
    },
    updatemultiCampaginEdit() {
      this.overlayEdit = !this.overlayEdit;
      this.$store.dispatch("getMultiCampaigns");
      this.loadCampaignJoinData();
    },

    getCampaignID() {
      return this.overlayEditID;
    },

    getCampaignName() {
      return this.overlayEditName;
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
          this.$router.push({ path: `/campaign/${item.id}` }); // -> /user/123
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
    ...mapState(["multicampaigns"]),

    campaigns() {
      return this.multicampaigns;
    },
  },
  beforeDestroy() {
    Echo.leave("multicampaigns");
  },
};
</script>
