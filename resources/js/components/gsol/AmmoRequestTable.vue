<template>
  <div v-resize="onResize">
    <div class="d-flex align-items-center">
      <v-card-title>Restock Requests</v-card-title>

      <v-text-field
        class="ml-5"
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>

      <!-- <v-btn-toggle
                right-align
                v-model="toggle_exclusive"
                mandatory
                :value="1"
            >
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 1"
                >
                    All
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 2"
                >
                    Outstanding
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 3"
                >
                    In Progress
                </v-btn>
            </v-btn-toggle> -->
    </div>
    <v-data-table
      :headers="headers"
      :items="filteredItems"
      item-key="id"
      :loading="loadingt"
      :height="height"
      fixed-header
      :items-per-page="50"
      :footer-props="{
        'items-per-page-options': [10, 20, 30, 50, 100, -1],
      }"
      :sort-by="['start_time']"
      :search="search"
      :sort-desc="[true, false]"
      multi-sort
      class="elevation-1"
    >
      >

      <template slot="no-data"> No Ammo Requests At The Moment </template>
      <template v-slot:[`item.count`]="{ item }">
        <VueCountUptimer
          :start-time="moment.utc(item.start_time).unix()"
          :end-text="'Window Closed'"
          :interval="1000"
        >
          <template slot="countup" slot-scope="scope">
            <span class="red--text pl-3" v-if="scope.props.days == 0"
              >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                scope.props.seconds
              }}</span
            >
            <span class="red--text pl-3" v-if="scope.props.days != 0"
              >{{ numberDay(scope.props.days) }} {{ scope.props.hours }}:{{
                scope.props.minutes
              }}:{{ scope.props.seconds }}</span
            >
          </template>
        </VueCountUptimer>
      </template>
      <template
        v-slot:[`item.alliance_ticker`]="{ item }"
        class="d-inline-flex align-center"
      >
        <v-avatar size="35"><img :src="item.url" /></v-avatar>
        <span v-if="item.standing > 0" class="blue--text pl-3"
          >{{ item.alliance_ticker }}
        </span>
        <span v-else-if="item.standing < 0" class="red--text pl-3"
          >{{ item.alliance_ticker }}
        </span>
        <span v-else class="pl-3">{{ item.alliance_ticker }}</span>
      </template>

      <template v-slot:[`item.actions`]="{ item }">
        <div class="d-inline-flex">
          <AmmoStocker :station="item"></AmmoStocker>
          <AmmoStationInfo class="ml-3" :station="item"></AmmoStationInfo>
        </div>
      </template>
    </v-data-table>
  </div>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  data() {
    return {
      headers: [
        {
          text: "Region",
          value: "region_name",
          width: "5%",
        },
        {
          text: "Constellation",
          value: "constellation_name",
          width: "8%",
        },
        {
          text: "System",
          value: "system_name",
          width: "8%",
        },
        {
          text: "Alliance",
          value: "alliance_ticker",
          width: "10%",
        },
        {
          text: "Type",
          value: "item_name",
          width: "10%",
        },
        {
          text: "Name",
          value: "station_name",
          width: "15%",
        },
        {
          text: "Timestamp",
          value: "start_time",
          align: "center",
          width: "15%",
        },
        {
          text: "Age/CountDown",
          value: "count",
          width: "5%",
        },
        {
          text: "",
          value: "actions",
          width: "10%",
          align: "start",
        },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      statusflag: 1,
      loadingt: true,
      loadingf: true,
      loadingr: true,
      search: "",
      toggle_exclusive: 1,
      windowSize: {
        x: 0,
        y: 0,
      },
    };
  },

  async created() {
    Echo.private("ammorequest")
      .listen("AmmoRequestNew", (e) => {
        this.$store.dispatch("loadStationInfo");
        this.$store.dispatch("addAmmoRequest", e.flag.message);
      })
      .listen("AmmoRequestUpdate", (e) => {
        this.$store.dispatch("updateAmmoRequest", e.flag.message);
      })
      .listen("AmmoRequestDelete", (e) => {
        this.$store.dispatch("deleteAmmoRequest", e.flag.id);
      })
      .listen("StationDataSet", (e) => {
        this.$store.dispatch("getStationData");
      });

    this.$store.dispatch("loadAmmoRequestInfo").then(() => {
      this.loadingt = false;
      this.loadingf = false;
      this.loadingr = false;
    });
  },

  async mounted() {
    this.onResize();
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
    numberDay(day) {
      return parseInt(day, 10) + "d";
    },
  },

  computed: {
    ...mapState(["ammoRequest"]),

    filteredItems() {
      const filter = this.ammoRequest;
      if (this.statusflag == 2) {
        return filter.filter((f) => f.user_id == null);
      }
      if (this.statusflag == 3) {
        return filter.filter((f) => f.user_id != null);
      } else {
        return filter;
      }
    },
    height() {
      let num = this.windowSize.y - 277;
      return num;
    },
  },
  beforeDestroy() {
    Echo.leave("ammorequest");
  },
};
</script>

<style>
.style-4 {
  background-color: rgba(255, 153, 0, 0.199);
}
</style>
