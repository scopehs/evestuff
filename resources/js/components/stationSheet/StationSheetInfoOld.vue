<template>
  <div>
    <v-dialog max-width="700px" z-index="0" v-model="showInfo" @click:outside="close()">
      <!-- <template v-slot:activator="{ on, attrs }">
        <v-btn icon color="blue" v-bind="attrs" v-on="on" @click="open()">
          <font-awesome-icon icon="fa-solid fa-circle-info" size="2xl"
        /></v-btn>
      </template> -->

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="1000px"
        class="d-flex flex-column"
      >
        <v-card-title class="justify-center"
          ><p>
            <!-- {{ station.name }} -->
            <v-chip
              pill
              class="ml-2"
              small
              outlined
              color="teal"
              @click="openRecon()"
              v-if="showLinkButton"
            >
              View On Recon Tool
            </v-chip>
          </p>
        </v-card-title>
        <v-card-subtitle>
          <!-- <div>
            Cored: <strong :class="textcolor"> {{ core }} </strong>
          </div> -->
          <div class="d-inline-flex">
            <!-- Last Updated: {{ lastUpdated() }} -->
            <!-- <v-tooltip
              color="#121212"
              bottom
              :open-delay="2000"
              :disabled="$store.state.tooltipToggle"
            >
              <template v-slot:activator="{ on: tooltip, attrs: atooltip }">
                <v-chip
                  v-on="{ ...tooltip }"
                  v-bind="{ ...atooltip }"
                  pill
                  class="ml-2"
                  small
                  outlined
                  color="teal"
                  v-if="$can('request_recon_task') && !taskFlag() && monthOld"
                  @click="taskRequest()"
                >
                  Request Update
                </v-chip>
              </template>
              <span>
                Request Recon to do a system scan update. Pressing this button will ping
                the recon channel and make a new task in the recon tool
              </span>
            </v-tooltip> -->
            <!-- <v-chip
              pill
              small
              class="ml-2"
              color="teal"
              v-if="$can('request_recon_task') && taskFlag()"
            >
              Request Made
            </v-chip> -->
            <StationRequestAmmo
              v-if="showAmmo()"
              :station="station"
              :key="'ammorequest' + station.id"
            ></StationRequestAmmo>
          </div>
        </v-card-subtitle>
        <v-card-text>
          <!-- <div v-if="showfit()">
            <v-chip
              small
              color="red"
              class="mt-2 mb-2"
              v-if="this.station.r_anti_cap == 1"
            >
              anti cap
            </v-chip>
            <v-chip
              small
              color="red"
              class="mt-2 mb-2"
              v-if="this.station.r_anti_subcap == 1"
            >
              anti subcap
            </v-chip>
            <v-chip
              small
              color="red"
              class="mt-2 mb-2"
              v-if="this.station.r_dooms_day == 1"
            >
              dooms day
            </v-chip>
            <v-chip
              small
              color="red"
              class="mt-2 mb-2"
              v-if="this.station.r_point_defense == 1"
            >
              point defense
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_biochemical == 1"
            >
              biochemical
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_capital_shipyard == 1"
            >
              capital shipyard
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_cloning == 1"
            >
              cloning
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_composite == 1"
            >
              composite
            </v-chip>

            <v-chip
              small
              color="red"
              class="mt-2 mb-2"
              v-if="this.station.r_guide_bombs == 1"
            >
              guide bombs
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_hyasyoda == 1"
            >
              hyasyoda
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_hybrid == 1"
            >
              hybrid
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_invention == 1"
            >
              invention
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_manufacturing == 1"
            >
              manufacturing
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_moon_drilling == 1"
            >
              moon drilling
            </v-chip>

            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_reprocessing == 1"
            >
              reprocessing
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_research == 1"
            >
              research
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_supercapital_shipyard == 1"
            >
              supercapital shipyard
            </v-chip>
            <v-chip
              small
              color="blue"
              class="mt-2 mb-2"
              v-if="this.station.r_t2_rigged == 1"
            >
              t2 rigged
            </v-chip>
          </div> -->

          <!-- <div v-if="!showfit()">No Fit Info</div> -->
          <!-- <v-card v-if="showfit()">
            <v-card-title> Fitting </v-card-title>
            <v-card-text>
              <v-data-table
                item-key="pivot.id"
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
          </v-card> -->
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <!-- <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn> -->
        </v-card-actions>
      </v-card>

      <!-- <ShowInfo
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showInfo = false"
            >
            </ShowInfo> -->
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
      //   headers: [
      //     { text: "", value: "icon", width: "5%", align: "start" },
      //     {
      //       text: "Items",
      //       value: "item_name",
      //       width: "95%",
      //       align: "start",
      //     },
      //   ],
      showInfo: false,
      editText: null,
      editAdashLink: null,
      fitted: false,
    };
  },

  //   async mounted() {
  //     this.checkfit();
  //   },

  methods: {
    close() {
      this.showInfo = false;
    },

    // openAdash(url) {
    //   var win = window.open(url, "_blank");
    //   win.focus();
    // },

    // taskFlag() {
    //   if (this.station.system.task_flag) {
    //     return true;
    //   } else {
    //     return false;
    //   }
    // },
    showAmmo() {
      if (this.$can("gunner") && this.station.standing > 0) {
        return true;
      } else {
        return false;
      }
    },
    // url(item) {
    //   return "https://images.evetech.net/types/" + item.id + "/icon";
    // },

    // open() {},

    // openRecon() {
    //   var url = "https://recon.gnf.lt/structures/" + this.station.r_hash + "/view";
    //   var win = window.open(url, "_blank");
    //   win.focus();
    // },

    // checkfit() {
    //   if (this.station.r_fitted == "Fitted") {
    //     this.fitted = true;
    //   }
    // },

    // taskRequest() {
    //   var request = {
    //     system_name: this.station.system.system_name,
    //     system_id: this.station.system_id,
    //     station_id: this.station.id,
    //     structure_name: this.station.name,
    //     username: this.$store.state.user_name,
    //     show_on_main: this.station.show_on_main,
    //     show_on_chill: this.show_on_chill,
    //   };
    //   axios({
    //     method: "post", //you can set what request you want to be
    //     url: "api/taskrequest",
    //     withCredentials: true,
    //     data: request,
    //     headers: {
    //       Accept: "application/json",
    //       "Content-Type": "application/json",
    //     },
    //   });
    // },

    // showfit() {
    //   if (this.fitted == true) {
    //     return true;
    //   } else {
    //     return false;
    //   }
    // },

    // lastUpdated() {
    //   if (this.station.r_updated_at != null) {
    //     var ago = moment.utc(this.station.r_updated_at).fromNow();
    //     return ago;
    //   } else {
    //     return "Never";
    //   }
    // },
  },

  computed: {
    ...mapGetters(["getStationItemsByStationID", "getCoreByStationID"]),

    // items() {
    //   //   return this.getStationItemsByStationID(this.station.id);
    //   return this.station.fit;
    // },

    // monthOld() {
    //   var a = moment.utc(this.station.r_updated_at);
    //   var b = moment.utc();
    //   var diff = b.diff(a, "months");

    //   if (diff > 0) {
    //     return true;
    //   } else {
    //     return false;
    //   }
    // },

    r_lastupdated() {
      return this.station.r_updated_at;
    },

    stationInfo() {
      return this.getCoreByStationID(this.station.id);
    },
    // core() {
    //   if (this.station.r_cored == 1) {
    //     return "Yes";
    //   } else {
    //     return "No";
    //   }
    // },

    // textcolor() {
    //   if (this.core == "Yes") {
    //     return "green--text";
    //   } else {
    //     return "red--text";
    //   }
    // },

    // showLinkButton() {
    //   if (this.$can("request_recon_task") && this.station.r_research != null) {
    //     return true;
    //   } else {
    //     return false;
    //   }
    // },
  },

  beforeDestroy() {},
};
</script>

<style></style>
