<template>
  <div class="pr-16 pl-16">
    <messageStations></messageStations>
    <div class="d-flex align-items-center">
      <v-card-title>Feed Back</v-card-title>

      <v-btn
        :loading="loadingr"
        :disabled="loadingr"
        color="primary"
        class="ma-2 white--text"
        @click="loadFeedBack()"
      >
        Update
        <font-awesome-icon icon="fa-solid fa-rotate" pull="right" />
      </v-btn>

      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </div>
    <v-data-table
      :headers="headers"
      :items="this.data"
      :expanded.sync="expanded"
      item-key="id"
      show-expand
      :items-per-page="25"
      :footer-props="{ 'items-per-page-options': [15, 25, 50, 100, -1] }"
      :sort-by="['created']"
      :search="search"
      :sort-desc="[true, false]"
      multi-sort
      class="elevation-1"
    >
      >

      <template slot="no-data"> No feed back atm </template>

      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length" align="center">
          <div>
            <v-col class="align-center">
              <v-textarea
                v-model="item.text"
                readonly
                label="What do people think?"
                outlined
                shaped
                >{{ item.text }}</v-textarea
              >
            </v-col>
          </div>
        </td>
      </template>

      <template v-slot:[`item.actions`]="{ item }">
        <v-btn icon @click="deleteFeedBack(item)" color="orange darken-3">
          <font-awesome-icon icon="fa-solid fa-trash-can"
        /></v-btn>
      </template>
    </v-data-table>
    <v-row no-gutters class="blue" justify="center">
      <v-card
        ><v-card-text>
          <v-text-field v-model="rcdata"> </v-text-field
          ><v-btn @click="sumbitrcdata()">Submmit</v-btn></v-card-text
        ></v-card
      >

      <!-- <v-card>
        <v-btn @click="startReconRgionPull()">Recon Pull TEST</v-btn></v-card
      > -->
      <!-- <v-card
        ><v-btn class="mr-4" color="green lighten-1" href="/esi/add"
          >ADD</v-btn
        ></v-card
      > -->
      <v-card> <v-btn @click="prequal()">Prequal</v-btn></v-card>
      <v-card> <v-btn @click="horizon()"> Horizon </v-btn></v-card>
      <v-card> <v-btn @click="logs()"> Logs </v-btn></v-card>
    </v-row>

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
    return `EVE`;
  },
  data() {
    return {
      atime: null,
      check: "not here",
      componentKey: 0,
      rcdata: null,
      data: [],
      dialog1: false,
      dialog2: false,
      dialog3: false,
      diff: 0,
      endcount: "",
      expanded: [],
      expanded_id: 0,
      icon: "justify",
      loadingt: true,
      loadingf: true,
      loadingr: true,
      name: "Timer",
      poll: null,
      search: "",
      statusflag: 4,
      snack: false,
      snackColor: "",
      snackText: "",
      toggle_exclusive: 0,
      today: 0,
      text: "center",
      toggle_none: null,

      dropdown_edit: [
        { title: "On My Way", value: 2 },
        { title: "Gunning", value: 3 },
        { title: "Saved", value: 4 },
        { title: "Reffed - Shield", value: 8 },
        { title: "Reffed - Armor", value: 9 },
        { title: "New", value: 1 },
      ],

      headers: [
        { text: "User", value: "user_name" },
        { text: "Date", value: "created" },
        { text: "Actions", value: "actions", align: "start" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
    };
  },

  created() {
    this.loadFeedBack();
  },

  async mounted() {},
  methods: {
    checkexpanded(FeedBack) {
      if (FeedBack.id == this.expanded_id) {
        this.expanded = [];
        this.expanded_id = 0;
      }
    },

    prequal() {
      let route = this.$router.resolve({
        path: "/hithere",
      });
      window.open(route.href);
    },

    horizon() {
      let route = this.$router.resolve({
        path: "/hitherealso",
      });
      window.open(route.href);
    },

    logs() {
      let route = this.$router.resolve({
        path: "/hithereagain",
      });
      window.open(route.href);
    },

    async loadFeedBack() {
      let res = await axios({
        method: "GET", //you can set what request you want to be
        url: "api/feedback",
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.data = res.data.feedback;
      this.loadingr = false;
    },

    async deleteFeedBack(item) {
      let res = await axios({
        method: "delete", //you can set what request you want to be
        url: "api/feedback/" + item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.loadFeedBack();
    },

    async sumbitrcdata() {
      await axios({
        method: "post", //you can set what request you want to be
        url: "api/rcInput",
        withCredentials: true,
        data: this.rcdata,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    // async startReconRgionPull() {
    //   await axios({
    //     method: "get", //you can set what request you want to be
    //     url: "api/reconpullregion",
    //     withCredentials: true,
    //     data: this.rcdata,
    //     headers: {
    //       Accept: "application/json",
    //       "Content-Type": "application/json",
    //     },
    //   });
    // },

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

    sec(item) {
      var a = moment.utc();
      var b = moment(item.timestamp);
      this.diff = a.diff(b);
      return this.diff;
    },
  },

  computed: {
    user_name() {
      return this.$store.state.user_name;
    },
  },
  beforeDestroy() {},
};
</script>
