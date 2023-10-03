<template>
  <div class="pr-16 pl-16">
    <div>
      <div class="d-flex align-items-center">
        <v-card-title>Hack Notifications</v-card-title>
        <div v-if="$can('access hacks')">You can edit posts.</div>

        <!-- <v-btn
          :loading="loadingr"
          :disabled="loadingr"
          color="primary"
          class="ma-2 white--text"
          @click="loadtimers()"
        >
          Update

          <font-awesome-icon
            icon="fa-solid fa-rotate"
            pull="right"
            size="2xl"
          />
        </v-btn> -->
        <!-- <div>
          <v-btn-toggle v-model="icon" borderless group>
            <v-dialog
              v-model="dialog1"
              fullscreen
              hide-overlay
              transition="dialog-bottom-transition"
            >
              <template v-slot:activator="{ on, attrs }">
                <template>
                  <v-btn
                    v-show="delvecheck == 1"
                    color="primary"
                    class="ma-2 white--text"
                    v-bind="attrs"
                    v-on="on"
                    @click="dialog1 = true"
                  >
                    Delve
                    <font-awesome-icon
                      icon="fa-solid fa-map"
                      pull="right"
                      size="xs"
                    />
                  </v-btn>
                </template>
              </template>
              <v-card>
                <v-toolbar dark color="primary">
                  <v-btn icon dark @click="dialog1 = false">
                    <font-awesome-icon icon="fa-solid fa-circle-xmark" />
                  </v-btn>
                  <v-toolbar-title>Delve</v-toolbar-title>
                  <v-spacer></v-spacer>
                </v-toolbar>
                <div
                  style="
                    position: absolute;
                    top: 64px;
                    right: 0px;
                    bottom: 0px;
                    left: 0px;
                  "
                >
                  <iframe
                    :src="delveLink"
                    style="
                      left: 0;
                      bottom: 0;
                      right: 0;
                      width: 100%;
                      height: 100%;
                      border: none;
                      margin: 0;
                      padding: 0;
                      overflow: hidden;
                      z-index: 999999;
                    "
                  >
                  </iframe>
                </div>
              </v-card>
            </v-dialog>

            <v-dialog
              v-model="dialog2"
              fullscreen
              hide-overlay
              transition="dialog-bottom-transition"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-show="queriousCheck == 1"
                  color="primary"
                  class="ma-2 white--text"
                  v-bind="attrs"
                  v-on="on"
                  @click="dialog2 = true"
                >
                  Querious
                  <font-awesome-icon
                    icon="fa-solid fa-map"
                    pull="right"
                    size="xs"
                  />
                </v-btn>
              </template>
              <v-card>
                <v-toolbar dark color="primary">
                  <v-btn icon dark @click="dialog2 = false">
                    <font-awesome-icon icon="fa-solid fa-circle-xmark" />
                  </v-btn>
                  <v-toolbar-title>Querious</v-toolbar-title>
                  <v-spacer></v-spacer>
                </v-toolbar>
                <div
                  style="
                    position: absolute;
                    top: 64px;
                    right: 0px;
                    bottom: 0px;
                    left: 0px;
                  "
                >
                  <iframe
                    :src="queriousLink"
                    style="
                      left: 0;
                      bottom: 0;
                      right: 0;
                      width: 100%;
                      height: 100%;
                      border: none;
                      margin: 0;
                      padding: 0;
                      overflow: hidden;
                      z-index: 999999;
                    "
                  >
                  </iframe>
                </div>
              </v-card>
            </v-dialog>
            <v-dialog
              v-model="dialog3"
              fullscreen
              hide-overlay
              transition="dialog-bottom-transition"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-show="periodbasisCheck == 1"
                  color="primary"
                  class="ma-2 white--text"
                  v-bind="attrs"
                  v-on="on"
                  @click="dialog3 = true"
                >
                  Period Basis
                  <font-awesome-icon
                    icon="fa-solid fa-map"
                    pull="right"
                    size="xs"
                  />
                </v-btn>
              </template>
              <v-card>
                <v-toolbar dark color="primary">
                  <v-btn icon dark @click="dialog3 = false">
                    <font-awesome-icon icon="fa-solid fa-circle-xmark" />
                  </v-btn>
                  <v-toolbar-title>Period Basis</v-toolbar-title>
                  <v-spacer></v-spacer>
                </v-toolbar>
                <div
                  style="
                    position: absolute;
                    top: 64px;
                    right: 0px;
                    bottom: 0px;
                    left: 0px;
                  "
                >
                  <iframe
                    :src="periodbasisLink"
                    style="
                      left: 0;
                      bottom: 0;
                      right: 0;
                      width: 100%;
                      height: 100%;
                      border: none;
                      margin: 0;
                      padding: 0;
                      overflow: hidden;
                      z-index: 999999;
                    "
                  >
                  </iframe>
                </div>
              </v-card>
            </v-dialog>
          </v-btn-toggle>
        </div> -->
        <!--
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field> -->

        <!-- <v-btn-toggle
          right-align
          v-model="toggle_exclusive"
          mandatory
          :value="0"
        >
          <v-btn
            :loading="loadingf"
            :disabled="loadingf"
            @click="statusflag = 4"
          >
            All
          </v-btn>
          <v-btn
            :loading="loadingf"
            :disabled="loadingf"
            @click="statusflag = 1"
          >
            New
          </v-btn>
          <v-btn
            :loading="loadingf"
            :disabled="loadingf"
            @click="statusflag = 6"
          >
            Scouting
          </v-btn>
          <v-btn
            :loading="loadingf"
            :disabled="loadingf"
            @click="statusflag = 3"
          >
            Repairing
          </v-btn>
          <v-btn
            :loading="loadingf"
            :disabled="loadingf"
            @click="statusflag = 5"
          >
            Contested
          </v-btn>
        </v-btn-toggle> -->
      </div>
      <v-data-table
        :headers="headers"
        :items="filteredItems"
        :expanded.sync="expanded"
        item-key="id"
        :loading="loadingt"
        :items-per-page="25"
        :footer-props="{
          'items-per-page-options': [15, 25, 50, 100, -1],
        }"
        :sort-by="['timestamp']"
        :search="search"
        :sort-desc="[true, false]"
        multi-sort
        class="elevation-1"
      >
        >

        <template slot="no-data">
          No hacking notifications to show, which I would say is a good thing?
        </template>
        <template v-slot:[`item.count`]="{ item }">
          <VueCountUptimer
            :start-time="moment.utc(item.timestamp).unix()"
            :end-text="'Window Closed'"
            :interval="1000"
            @timecheck="timecheck(item)"
          >
            <template slot="countup" slot-scope="scope">
              <span class="red--text pl-3"
                >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                  scope.props.seconds
                }}</span
              >
            </template>
          </VueCountUptimer>
        </template>

        <template
          v-slot:[`item.status_name`]="{ item }"
          class="align-items-center d-inline-flex"
        >
          <div v-if="$can('edit_notifications')" class="d-inline-flex align-items-center">
            <v-menu offset-y>
              <template v-slot:activator="{ on, attrs }">
                <div class="align-content-center d-inline-flex">
                  <v-btn
                    class="ma-2"
                    v-bind="attrs"
                    v-on="on"
                    tile
                    outlined
                    :color="statusButtonColor(item)"
                  >
                    <font-awesome-icon :icon="statusButtonIcon(item)" pull="left" />
                    {{ item.status_name }}
                  </v-btn>
                </div>
              </template>

              <v-list>
                <v-list-item
                  v-for="(list, index) in dropdown_edit"
                  :key="index"
                  @click="
                    (item.status_id = list.value),
                      (item.status_name = list.title),
                      (item.user_name = user_name),
                      click(item)
                  "
                >
                  <v-list-item-title>{{ list.title }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
            <v-slide-x-transition group>
              <v-chip
                pill
                :key="'adash' + item.id"
                outlined
                small
                @click="(expanded = [item]), (expanded_id = item.id)"
                v-show="item.status_id == 5 && !expanded.includes(item)"
                color="success"
                >aDash</v-chip
              >
              <v-btn
                icon
                :key="'adash_' + item.id"
                @click="(expanded = []), (expanded_id = 0)"
                v-show="item.status_id == 5 && expanded.includes(item)"
                color="error"
                ><font-awesome-icon icon="fa-solid fa-minus"
              /></v-btn>
            </v-slide-x-transition>
            <v-slide-x-transition>
              <NotificationTimer
                :item="item"
                :key="'NoteTimer' + item.id"
                v-show="
                  (item.status_id == 5 || item.status_id == 3) &&
                  $can('edit_notifications')
                "
              ></NotificationTimer>
            </v-slide-x-transition>
          </div>
          <div v-else>
            <template>
              <div class="align-items-center d-inline-flex">
                <v-chip v-if="item.status_id == 1" class="ma-2" label color="success">
                  <font-awesome-icon icon="fa-solid fa-plus" pull="left" />
                  {{ item.status_name }}
                </v-chip>
                <v-chip v-if="item.status_id == 2" class="ma-2" label color="error">
                  <font-awesome-icon
                    icon="fa-solid fa-fire-flame"
                    size="sm"
                    pull="left"
                  />
                  {{ item.status_name }}
                </v-chip>
                <v-chip v-if="item.status_id == 3" class="ma-2" label color="dark-orange">
                  <font-awesome-icon icon="fa-solid fa-toolbox" pull="left" />
                  {{ item.status_name }}
                </v-chip>
                <v-chip v-if="item.status_id == 4" class="ma-2" label color="primary">
                  <font-awesome-icon icon="fa-solid fa-thumbs-up" pull="left" />
                  {{ item.status_name }}
                </v-chip>
                <v-chip v-if="item.status_id == 5" class="ma-2" label color="warning">
                  <font-awesome-icon icon="fa-solid fa-circle-exclamation" pull="left" />
                  {{ item.status_name }}
                </v-chip>

                <v-chip
                  v-if="item.status_id == 6"
                  class="ma-2"
                  label
                  color="light-green darken-1"
                >
                  <font-awesome-icon icon="fa-solid fa-magnifying-glass" pull="left" />
                  {{ item.status_name }}
                </v-chip>
                <CountDowntimer
                  v-if="
                    (item.status_id == 3 || item.status_id == 5) && item.end_time != null
                  "
                  :start-time="moment.utc(item.end_time).unix()"
                  :interval="1000"
                  end-text="Is it Secured?"
                >
                  <template slot="countdown" slot-scope="scope">
                    <span class="blue--text pl-3"
                      >{{ scope.props.minutes }}:{{ scope.props.seconds }}</span
                    >
                  </template>
                </CountDowntimer>
              </div>
            </template>
          </div>
        </template>
        <template
          v-slot:expanded-item="{ headers, item }"
          class="align-center"
          height="100%"
        >
          <td :colspan="headers.length" align="center">
            <div>
              <v-col class="align-center">
                <v-text-field
                  v-bind:value="item.text"
                  label="aDash Board Link - needs to be a link to a scan, making a new scan from where will not save"
                  outlined
                  shaped
                  @change="(payload = $event), updatetext(payload, item)"
                ></v-text-field>
              </v-col>
            </div>
            <div
              v-if="
                item.text != null &&
                item.text.includes('https://adashboard.info/intel/dscan/')
              "
            >
              <v-card class="mx-auto" elevation="24">
                <iframe
                  :name="'ifram' + item.id"
                  :src="item.text"
                  style="
                    left: 0;
                    bottom: 0;
                    right: 0;
                    width: 100%;
                    height: 600px;
                    border: none;
                    margin: 0;
                    padding: 0;
                    overflow: hidden;
                    z-index: 999999;
                  "
                >
                </iframe>
              </v-card>
            </div>
            <div>
              {{ item.text }}
            </div>
          </td>
        </template>
        <template v-slot:[`item.user_name`]="{ item }" class="d-flex align-center">
          <p v-if="$can('edit_notifications')">
            {{ item.user_name }}
          </p>
        </template>
      </v-data-table>
      <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
        {{ snackText }}

        <template v-slot:action="{ attrs }">
          <v-btn v-bind="attrs" text @click="snack = false">Close</v-btn>
        </template>
      </v-snackbar>
    </div>
    <Structure v-if="$can('view_station_notifications')"></Structure>
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
    return `EveStuff - Notifications`;
  },
  data() {
    return {
      atime: null,
      check: "not here",
      componentKey: 0,
      dialog1: false,
      dialog2: false,
      dialog3: false,
      diff: 0,
      delve: 0,
      endcount: "",
      expanded: [],
      expanded_id: 0,
      icon: "justify",
      loadingt: true,
      loadingf: true,
      loadingr: true,
      name: "Timer",
      poll: null,
      periodbasis: 0,
      search: "",
      statusflag: 4,
      snack: false,
      snackColor: "",
      snackText: "",
      toggle_exclusive: 0,
      today: 0,
      text: "center",
      toggle_none: null,
      querious: 0,

      dropdown_edit: [
        { title: "Scouting", value: 6 },
        { title: "Reffed", value: 2 },
        { title: "Repairing", value: 3 },
        { title: "Secured", value: 4 },
        { title: "Hostile", value: 5 },
        { title: "New", value: 1 },
      ],

      headers: [
        { text: "Region", value: "region_name", width: "10%" },
        {
          text: "Constellation",
          value: "constellation_name",
          width: "8%",
        },
        { text: "System", value: "system_name", width: "8%" },
        { text: "Structure", value: "item_name", width: "8%" },
        { text: "ADM", value: "adm", width: "5%" },
        {
          text: "Timestamp",
          value: "timestamp",
          align: "center",
          width: "20%",
        },
        { text: "Age", value: "count", sortable: false, width: "10%" },
        { text: "Status", value: "status_name", width: "20%" },
        {
          text: "Edited By",
          value: "user_name",
          width: "10%",
          align: "start",
        },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
    };
  },

  created() {
    Echo.private("notes")
      .listen("NotificationChanged", (e) => {
        this.checkexpanded(e.notifications);
        this.$store.dispatch("updateNotification", e.notifications);
      })

      .listen("NotificationNew", (e) => {
        this.loadtimers();
      });
    this.$store.dispatch("getNotifications").then(() => {
      this.loadingt = false;
      this.loadingf = false;
      this.loadingr = false;
    });

    this.$store.dispatch("getqueriousLink");
    this.$store.dispatch("getdelveLink");
    this.$store.dispatch("getperiodbasisLink");
  },

  async mounted() {
    this.log();
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

    timecheck(item) {
      if (item.status_id == 4 || item.status_id == 2) {
        item.status_id = 10;
        this.$store.dispatch("updateNotification", item);
        if (item.region_name === "Querious") {
          this.$store.dispatch("getqueriousLink");
        }
        if (item.region_name === "Period Basis") {
          this.$store.dispatch("getperiodbasisLink");
        }
        if (item.region_name === "Delve") {
          this.$store.dispatch("getdelveLink");
        }
        var request = {
          status_id: 10,
        };
        axios({
          method: "put", //you can set what request you want to be
          url: "api/notifications/" + item.id,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }
    },

    seeMessage() {
      if (this.$can("edit_notifications")) {
        return false;
      } else {
        return true;
      }
    },

    checkexpanded(notifications) {
      if (notifications.status_id != 5) {
        if (notifications.id == this.expanded_id) {
          this.expanded = [];
          this.expanded_id = 0;
        }
      }
    },

    updatetext(payload, item) {
      if (item.text != payload) {
        item.text = payload;
        var request = {
          text: item.text,
        };
        this.$store.dispatch("updateNotification", item);
        axios({
          method: "put", //you can set what request you want to be
          url: "api/notifications/" + item.id,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }
    },

    loadtimers() {
      this.loadingr = true;
      this.$store.dispatch("getNotifications").then(() => {
        this.loadingr = false;
      });
      this.$store.dispatch("getqueriousLink");
      this.$store.dispatch("getdelveLink");
      this.$store.dispatch("getperiodbasisLink");
    },

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

    statusButtonColor(item) {
      if (item.status_id == 1) {
        return "success";
      } else if (item.status_id == 2) {
        return "error";
      } else if (item.status_id == 3) {
        return "dark-orange";
      } else if (item.status_id == 4) {
        return "primary";
      } else if (item.status_id == 5) {
        return "warning";
      } else if (item.status_id == 6) {
        return "light-green darken-1";
      }
    },

    statusButtonIcon(item) {
      if (item.status_id == 1) {
        return "fa-solid fa-plus";
      } else if (item.status_id == 2) {
        return "fa-solid fa-fire-flame";
      } else if (item.status_id == 3) {
        return "fa-solid fa-toolbox";
      } else if (item.status_id == 4) {
        return "fa-solid fa-thumbs-up";
      } else if (item.status_id == 5) {
        return "fa-solid fa-circle-exclamation";
      } else if (item.status_id == 6) {
        return "fa-solid fa-magnifying-glass";
      }
    },

    click(item) {
      if (item.status_id != 5) {
        this.expanded = [];
        item.text = null;
      }

      if (item.status_id != 3) {
        item.end_time = null;
      }
      var request = {
        status_id: item.status_id,
        user_id: this.$store.state.user_id,
        end_time: item.end_time,
      };
      axios({
        method: "put", //you can set what request you want to be
        url: "api/notifications/" + item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    sec(item) {
      var a = moment.utc();
      var b = moment(item.timestamp);
      this.diff = a.diff(b);
      return this.diff;
    },
  },

  computed: {
    ...mapState(["notifications", "delveLink", "queriousLink", "periodbasisLink"]),

    filteredItems() {
      // var timers = this.$store.state.timers;
      if (this.statusflag == 1) {
        return this.notifications.filter((notifications) => notifications.status_id == 1);
      }
      if (this.statusflag == 3) {
        return this.notifications.filter((notifications) => notifications.status_id == 3);
      }
      if (this.statusflag == 5) {
        return this.notifications.filter((notifications) => notifications.status_id == 5);
      }
      if (this.statusflag == 6) {
        return this.notifications.filter((notifications) => notifications.status_id == 6);
      } else {
        return this.notifications.filter(
          (notifications) => notifications.status_id != 10
        );
      }
    },

    delvecheck() {
      if (this.delveLink === "") {
        return 0;
      } else if (this.delveLink === "nope") {
        return 0;
      } else {
        return 1;
      }
    },

    periodbasisCheck() {
      if (this.periodbasisLink === "") {
        return 0;
      } else if (this.periodbasisLink === "nope") {
        return 0;
      } else {
        return 1;
      }
    },

    user_name() {
      return this.$store.state.user_name;
    },

    queriousCheck() {
      if (this.queriousLink === "") {
        return 0;
      } else if (this.queriousLink === "nope") {
        return 0;
      } else {
        return 1;
      }
    },
  },
  beforeDestroy() {
    Echo.leave("notes");
  },
};
</script>
