<template>
  <div>
    <v-dialog
      :max-width="maxW"
      z-index="0"
      v-model="showInfo"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-chip
          :key="'infobutton' + station.id"
          pill
          small
          color="blue"
          v-bind="attrs"
          v-on="on"
          @click="open()"
        >
          <font-awesome-icon icon="fa-solid fa-circle-info" pull="left" />
          Info
        </v-chip>
      </template>

      <v-card
        tile
        :max-width="maxW"
        min-width="700px"
        min-height="200px"
        max-height="1000px"
        class="d-flex flex-column justify-center"
      >
        <v-card-title class="justify-center"
          ><p>
            {{ station.station_name }}
            <v-chip
              pill
              class="ml-2"
              small
              outlined
              color="teal"
              @click="openRecon(fit[0]['r_hash'])"
            >
              View On Recon Tool
            </v-chip>
          </p>
        </v-card-title>
        <v-card-subtitle>
          <div>Assigned too: {{ showAssignName }}</div>
          <div>
            Cored: <strong :class="textcolor"> {{ core }} </strong>
          </div>
          <div class="d-inline-flex">Last Updated: {{ lastUpdated() }}</div>
        </v-card-subtitle>
        <v-card-text>
          <div v-if="showfit()">
            <v-chip
              small
              color="red"
              class="mt-2 mb-2"
              v-if="fit[0]['r_anti_cap'] == 1"
            >
              anti cap
            </v-chip>
            <v-chip
              small
              color="red"
              class="mt-2 mb-2"
              v-if="fit[0]['r_anti_subcap'] == 1"
            >
              anti subcap
            </v-chip>
            <v-chip
              small
              color="red"
              class="mt-2 mb-2"
              v-if="fit[0]['r_dooms_day'] == 1"
            >
              dooms day
            </v-chip>
            <v-chip
              small
              color="red"
              class="mt-2 mb-2"
              v-if="fit[0]['r_point_defense'] == 1"
            >
              point defense
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_biochemical'] == 1"
            >
              biochemical
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_capital_shipyard'] == 1"
            >
              capital shipyard
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_cloning'] == 1"
            >
              cloning
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_composite'] == 1"
            >
              composite
            </v-chip>

            <v-chip
              small
              color="red"
              class="mt-2 mb-2"
              v-if="fit[0]['r_guide_bombs'] == 1"
            >
              guide bombs
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_hyasyoda'] == 1"
            >
              hyasyoda
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_hybrid'] == 1"
            >
              hybrid
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_invention'] == 1"
            >
              invention
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_manufacturing'] == 1"
            >
              manufacturing
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_moon_drilling'] == 1"
            >
              moon drilling
            </v-chip>

            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_reprocessing'] == 1"
            >
              reprocessing
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_research'] == 1"
            >
              research
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_supercapital_shipyard'] == 1"
            >
              supercapital shipyard
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="fit[0]['r_t2_rigged'] == 1"
            >
              t2 rigged
            </v-chip>
          </div>

          <div v-if="!showfit()">No Fit Info</div>
          <div class="d-inline-flex">
            <v-card v-if="showfit()">
              <v-card-title> Fitting </v-card-title>
              <v-card-text>
                <v-data-table
                  :headers="headers"
                  :items="items"
                  disable-sort
                  hide-default-footer
                  hide-default-header
                  disable-pagination
                  class="elevation-12"
                  height="500px"
                >
                  <template v-slot:[`item.icon`]="{ item }">
                    <v-avatar>
                      <img :src="url(item)" />
                    </v-avatar>
                  </template>
                </v-data-table>
              </v-card-text>
            </v-card>

            <v-card
              class="mx-4"
              elevation="5"
              max-height="600px"
              min-width="300px"
              max-width="300px"
            >
              <v-card-title> Current Ammo </v-card-title>
              <v-card-text>
                <v-list dense max-height=" 600px">
                  <v-list-item
                    v-for="(currentAmmo, index) in currentAmmos"
                    :key="index"
                  >
                    <p>
                      <strong>{{ currentAmmo[0] }}</strong>
                      x{{ currentAmmo[1] }}
                    </p>
                  </v-list-item>
                </v-list>
              </v-card-text>
            </v-card>
            <v-card
              elevation="5"
              max-height="600px"
              min-width="300px"
              max-width="300px"
              ><v-card-title>Request</v-card-title
              ><v-card-text>{{ station.request_text }}</v-card-text></v-card
            >
          </div>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn>

          <v-btn
            v-show="showTakeTask"
            :key="'takebtn' + this.station.id"
            class="white--text"
            color="green"
            @click="taskTask()"
          >
            Take Task
          </v-btn>
          <v-btn
            v-show="showDoneTask"
            :key="'dropbtn' + this.station.id"
            class="white--text"
            color="red"
            @click="taskDrop()"
          >
            Drop Task
          </v-btn>
          <v-btn
            v-show="showDoneTask"
            :key="'donebtn' + this.station.id"
            class="white--text"
            color="warning"
            @click="taskDone()"
          >
            Task Done
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    station: Object,
  },
  data() {
    return {
      headers: [
        { text: "", value: "icon", width: "5%", align: "start" },
        {
          text: "Items",
          value: "item_name",
          width: "95%",
          align: "start",
        },
      ],
      showInfo: false,
      fitted: false,
      maxW: "700px",
    };
  },

  methods: {
    close() {
      this.showInfo = false;
    },

    async taskTask() {
      var request = {
        user_id: this.$store.state.user_id,
      };
      await axios({
        method: "post",
        url: "/api/ammorequestupdate/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      this.showInfo = true;
    },

    async taskDrop() {
      var request = {
        user_id: null,
      };
      await axios({
        method: "post",
        url: "/api/ammorequestupdate/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async taskDone() {
      this.showInfo = false;
      await axios({
        method: "delete",
        url: "/api/ammorequestdelete/" + this.station.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    url(item) {
      return "https://images.evetech.net/types/" + item.item_id + "/icon";
    },

    open() {
      if (this.showfit()) {
        this.maxW = "1200px";
      } else {
        this.maxW = "700px";
      }
    },

    openRecon(hash) {
      var url = "https://recon.gnf.lt/structures/" + hash + "/view";
      var win = window.open(url, "_blank");
      win.focus();
    },

    showfit() {
      if (this.fitted == true) {
        return true;
      } else {
        return false;
      }
    },

    lastUpdated() {
      if (this.fit[0]["r_updated_at"] != null) {
        var ago = moment(this.fit[0]["r_updated_at"]).fromNow();
        return ago;
      } else {
        return "Never";
      }
    },
  },

  created() {},

  computed: {
    ...mapGetters([
      "getStationItemsByStationID",
      "getCoreByStationID",
      "getStationFitByStationID",
    ]),

    items() {
      return this.getStationItemsByStationID(this.station.station_id);
    },

    fit() {
      var fit = this.getStationFitByStationID(this.station.station_id);
      if (fit[0]["r_fitted"] == "Fitted") {
        this.fitted = true;
      }

      return fit;
    },

    r_lastupdated() {
      return this.fit.r_updated_at;
    },

    stationInfo() {
      return this.getCoreByStationID(this.station.station_id);
    },
    core() {
      var core = this.getCoreByStationID(this.station.station_id);

      if (this.fit == "NO") {
        return "No Info";
      }

      if ((core[0].cored == "Yes") == "Yes") {
        return "Yes";
      } else {
        return "No";
      }
    },

    textcolor() {
      if (this.core == "Yes") {
        return "green--text";
      } else {
        return "red--text";
      }
    },

    currentAmmos() {
      var data = [];
      var text = this.station.current_ammo;
      var t = text.split("\n");

      t.forEach((a) => {
        var s = a.split("\t");
        data.push(s);
      });
      return data;
    },

    showTakeTask() {
      if (this.station.user_id == null) {
        return true;
      } else {
        return false;
      }
    },

    showDoneTask() {
      if (this.station.user_id == this.$store.state.user_id) {
        return true;
      } else {
        return false;
      }
    },

    showAssignName() {
      if (this.station.user_id == null) {
        return "None";
      } else {
        return this.station.user_name;
      }
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
