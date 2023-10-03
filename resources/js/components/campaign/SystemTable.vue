<template>
  <v-col cols="6" align-self="stretch">
    <v-card tile height="100%" class="d-flex flex-column">
      <v-card-text>
        <template>
          <v-card flat max-width elevation="24" color="grey darken-4">
            <v-card-title
              max-width
              class="d-flex justify-space-between align-center"
              style="width: 100%"
            >
              <div>{{ system_name }}</div>
              <v-divider class="mx-4 my-0" vertical></v-divider>
              <p v-if="nodeCount > 0" class="pt-4 pr-3">Nodes -</p>
              <div>
                <v-progress-circular
                  v-if="nodeCount > 0"
                  :transitionDuration="5000"
                  :radius="20"
                  :strokeWidth="4"
                  :value="(nodeCountHackingCount / nodeCount) * 100 || 0.000001"
                >
                  <div class="caption">
                    {{ nodeCountHackingCount }} /
                    {{ nodeCount }}
                  </div></v-progress-circular
                >

                <v-progress-circular
                  v-if="nodeCount > 0"
                  :transitionDuration="5000"
                  :radius="20"
                  :strokeWidth="4"
                  strokeColor="#FF3D00"
                  :value="
                    (nodeRedCountHackingCount / nodeCount) * 100 || 0.000001
                  "
                >
                  <div class="caption">
                    {{ nodeRedCountHackingCount }} /
                    {{ nodeCount }}
                  </div></v-progress-circular
                >
              </div>
              <v-spacer></v-spacer>
              <v-divider class="mx-4 my-0" vertical></v-divider>
              <div class="ml-auto">
                <v-menu transition="fade-transition" v-if="charCount != 0">
                  <template v-slot:activator="{ on, attrs }">
                    <v-chip
                      dark
                      :color="filterCharsOnTheWay"
                      v-bind="attrs"
                      v-on="on"
                      small
                    >
                      On the Way
                    </v-chip>
                  </template>
                  <v-list>
                    <v-list-item
                      v-for="(list, index) in charsFree"
                      :key="index"
                      @click="(charOnTheWay = list.id), clickOnTheWay()"
                    >
                      <v-list-item-title>{{
                        list.char_name
                      }}</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
                <span v-else> On the way - </span>
                <v-menu transition="fade-transition">
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      class="mx-2"
                      v-bind="attrs"
                      :disabled="fabOnTheWayDisbale"
                      v-on="on"
                      fab
                      color="green darken-4"
                      dark
                      x-small
                    >
                      {{ OnTheWayCount }}
                    </v-btn>
                  </template>
                  <v-list>
                    <v-list-item
                      v-for="(list, index) in charsOnTheWayAll"
                      :key="index"
                    >
                      <v-list-item-title>
                        {{ list.char_name }} - {{ list.ship }} - T{{ list.link
                        }}<span class="pl-3" v-if="seeReadyToGoOnTheWay(list)">
                          <v-btn
                            icon
                            @click="removeReadyToGoOnTheWay(list)"
                            color="orange darken-3"
                          >
                            <font-awesome-icon
                              icon="fa-solid fa-trash-can"
                            /> </v-btn></span
                      ></v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </div>
              <v-divider class="mx-4 my-0" vertical></v-divider>
              <div>
                <v-menu transition="fade-transition" v-if="charCount != 0">
                  <template v-slot:activator="{ on, attrs }">
                    <v-chip
                      dark
                      :color="filterCharsReadyToGo"
                      v-bind="attrs"
                      v-on="on"
                      small
                    >
                      Ready to go
                    </v-chip>
                  </template>
                  <v-list>
                    <v-list-item
                      v-for="(list, index) in charsFree"
                      :key="index"
                      @click="(charReadyToGo = list.id), clickReadyToGo()"
                    >
                      <v-list-item-title>{{
                        list.char_name
                      }}</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
                <span v-else> Ready to go - </span>
                <v-menu transition="fade-transition">
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      class="mx-2"
                      v-bind="attrs"
                      v-on="on"
                      :disabled="fabReadyToGoDisbale"
                      fab
                      color="green darken-4"
                      dark
                      x-small
                    >
                      {{ ReadyToGoCount }}
                    </v-btn>
                  </template>
                  <v-list>
                    <v-list-item
                      v-for="(list, index) in charsReadyToGoAll"
                      :key="index"
                    >
                      <v-list-item-title>
                        {{ list.char_name }} - {{ list.ship }} - T{{ list.link
                        }}<span class="pl-3" v-if="seeReadyToGoOnTheWay(list)">
                          <v-btn
                            color="orange darken-3"
                            @click="removeReadyToGoOnTheWay(list)"
                          >
                            <font-awesome-icon
                              icon="fa-solid fa-trash-can"
                            /> </v-btn></span
                      ></v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </div>
              <v-divider class="mx-4 my-0" vertical></v-divider>
              <div>
                <v-menu :close-on-content-click="false" :value="addShown">
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      text
                      v-bind="attrs"
                      v-on="on"
                      @click="addShown = true"
                      color="success"
                    >
                      ><font-awesome-icon icon="fa-solid fa-plus" size="2xl" />
                      Node</v-btn
                    >
                  </template>
                  <v-card tile min-height="150px">
                    <v-card-title class="pb-0">
                      <v-text-field
                        class="mt-2"
                        label="Node"
                        placeholder="Enter Node"
                        flat
                        v-mask="'AA##'"
                        autofocus
                        v-model="nodeText"
                        @keyup.enter="addNode()"
                        @keyup.esc="(addShown = false), (nodeText = '')"
                      ></v-text-field>
                    </v-card-title>

                    <v-card-text>
                      <v-btn icon fixed left color="success" @click="addNode()"
                        ><font-awesome-icon icon="fa-solid fa-check"
                      /></v-btn>

                      <v-btn
                        fixed
                        right
                        icon
                        color="warning"
                        @click="(addShown = false), (nodeText = '')"
                        ><font-awesome-icon icon="fa-solid fa-circle-xmark"
                      /></v-btn>
                    </v-card-text>
                  </v-card>
                </v-menu>
              </div>
            </v-card-title>
          </v-card>
        </template>
        <v-data-table
          :headers="headers"
          :items="filteredItems"
          :single-expand="singleExpand"
          item-key="node"
          disable-sort
          :expanded="expanded"
          :item-class="itemRowBackground"
          hide-default-footer
          disable-pagination
          class="elevation-12"
        >
          <template v-slot:[`item.status_name`]="{ item }">
            <v-menu offset-y>
              <template v-slot:activator="{ on, attrs }">
                <div>
                  <v-chip
                    v-bind="attrs"
                    v-on="on"
                    pill
                    :outlined="pillOutlined(item)"
                    small
                    :color="pillColor(item)"
                  >
                    {{ item.status_name }}
                  </v-chip>
                </div>
              </template>

              <v-list>
                <v-list-item
                  v-for="(list, index) in dropdown_edit"
                  :key="index"
                  @click="
                    (item.status_id = list.value),
                      (item.status_name = list.title),
                      statusClick(item)
                  "
                >
                  <v-list-item-title>{{ list.title }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </template>
          <template v-slot:[`item.user_name`]="{ item }">
            <div class="d-inline-flex align-items-center">
              <v-menu offset-y v-if="checkShowAdd(item)">
                <template v-slot:activator="{ on, attrs }">
                  <div>
                    <v-chip
                      v-bind="attrs"
                      v-on="on"
                      pill
                      outlined
                      small
                      color="light-green accent-3"
                    >
                      Add
                    </v-chip>
                  </div>
                </template>

                <v-list>
                  <v-list-item
                    v-for="(list, index) in charsFree"
                    :key="index"
                    @click="(charAddNode = list.id), clickCharAddNode(item)"
                  >
                    <v-list-item-title>{{ list.char_name }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
              <div
                v-else-if="item.user_name != null"
                class="d-inline-flex align-items-center"
              >
                {{ item.user_name }}
                <v-btn
                  v-if="checkShowAddRemove(item)"
                  icon
                  @click="
                    (item.user_name = null),
                      (item.main_name = null),
                      removeCharNode(item)
                  "
                  color="orange darken-3"
                >
                  <font-awesome-icon icon="fa-solid fa-trash-can" size="2xl"
                /></v-btn>
                <NodeExtraChar :item="item"></NodeExtraChar>
              </div>
              <AdminHackUserTable
                v-if="$can('campaigns_admin_access')"
                :nodeItem="item"
              ></AdminHackUserTable>
            </div>
          </template>
          <template v-slot:[`item.count`]="{ item }">
            <SystemTableTimer
              :item="item"
              :CampaignSolaSystem="CampaignSolaSystem"
            >
            </SystemTableTimer>
          </template>

          <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="headers.length" align="center">
              <JoinNodeTable :sysid="item.id"></JoinNodeTable>
            </td>
          </template>
          <template v-slot:[`item.actions`]="{ item }">
            <div class="d-inline-flex">
              <SystemAttackMessage
                class="pr-3"
                :item="item"
              ></SystemAttackMessage>
              <SystemMessage :item="item"> </SystemMessage>
              <v-btn
                v-if="item.status_id != 4 && item.status_id != 5"
                color="orange darken-3"
                class="pl-5"
                @click="deleteNode(item)"
              >
                <font-awesome-icon icon="fa-solid fa-trash-can" />
              </v-btn>
            </div>
          </template>

          <template v-slot:[`item.user_ship`]="{ item }">
            <span v-if="item.user_name != null">
              {{ item.user_ship }} - T{{ item.user_link }}
            </span>
          </template>

          <template slot="no-data">
            No nodes have shown up here..... yet!!!!
          </template>
        </v-data-table>
      </v-card-text>
      <v-spacer></v-spacer>
      <v-card-actions class="pt-4 d-inline-flex">
        <div class="d-flex-block" style="width: 100%">
          <div
            class="d-inline-flex justify-md-space-between"
            style="width: 100%"
          >
            <LastedChecked :CampaignSolaSystem="CampaignSolaSystem">
            </LastedChecked>
            <SystemTidi :CampaignSolaSystem="CampaignSolaSystem"> </SystemTidi>
          </div>
          <div
            class="d-inline-flex justify-md-space-between"
            style="width: 100%"
          >
            <SystemScout :CampaignSolaSystem="CampaignSolaSystem">
            </SystemScout>
            <v-btn
              v-if="$can('view_campaign_logs')"
              @click="openSolaLog()"
              small
              class="mr-4"
              color="blue"
            >
              System Logs
            </v-btn>
          </div>
        </div>
      </v-card-actions>
    </v-card>
  </v-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    system_name: String,
    system_id: Number,
    campaign_id: Number,
  },
  data() {
    return {
      headers: [
        { text: "NodeID", value: "node", width: "5%", align: "start" },
        {
          text: "Pilot",
          value: "user_name",
          width: "25%",
          align: "start",
        },
        {
          text: "Main",
          value: "main_name",
          width: "10%",
          align: "start",
        },
        {
          text: "Ship",
          value: "user_ship",
          width: "15%",
          align: "start",
        },

        {
          text: "Status",
          value: "status_name",
          width: "20%",
          align: "center",
        },
        {
          text: "Age/Hack",
          value: "count",
          width: "20%",
          align: "center",
        },
        {
          text: "",
          value: "actions",
          sortable: false,
          align: "end",
          width: "5%",
        },
        {
          text: "",
          value: "data-table-expand",
          align: "end",
          width: "5%",
        },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],

      dropdown_edit: [
        { title: "New", value: 1 },
        { title: "Warm up", value: 2 },
        { title: "Hacking", value: 3 },
        { title: "Friendly Hacking", value: 8 },
        { title: "Passive", value: 9 },
        { title: "Success", value: 4 },
        { title: "Pushed off", value: 6 },
        { title: "Hostile Hacking", value: 7 },
        { title: "Failed", value: 5 },
      ],
      charOnTheWay: 0,
      charReadyToGo: 0,
      OnTheWayColor: "teal",
      nodeText: "",
      showNodeNotes: false,
      addShown: false,
      nodeNoteItem: [],
      singleExpand: false,
      charAddNode: null,
      noteText: "",
      test1: "",
    };
  },

  async created() {},

  async mounted() {},

  methods: {
    pillOutlined(item) {
      if (item.status_id == 7 || item.status_id == 9) {
        return false;
      } else {
        return true;
      }
    },

    openMessage(item) {
      // this.$emit("openMessage", item);
      this.showNodeNotes = true;
    },

    openSolaLog() {
      let item = {
        solaID: this.CampaignSolaSystem[0]["id"],
        solaName: this.system_name,
      };

      this.$emit("openSolaLog", item);
    },

    async clickOnTheWay() {
      this.OnTheWayColor = "green";
      var item = {
        id: this.charOnTheWay,
        status_id: 2,
        user_status_name: "On the way",
        system_id: this.system_id,
        system_name: this.system_name,
        campaign_system_id: null,
        node_id: null,
      };

      this.$store.dispatch("updateCampaignUsers", item);
      var request = {
        status_id: 2,
        system_id: this.system_id,
        campaign_system_id: null,
      };

      await axios({
        method: "put",
        url: "/api/campaignusers/" + this.charOnTheWay + "/" + this.campaign_id,
        data: request,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      request = null;
      request = {
        campaign_user_id: this.charOnTheWay,
        system_id: this.system_id,
        campaign_id: this.campaign_id,
      };

      await axios({
        method: "put",
        url: "/api/campaignsystemremovechar/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      // this.$store.dispatch("getCampaignSystemsRecords");
      this.charOnTheWay = null;
    },

    async clickReadyToGo() {
      var item = {
        id: this.charReadyToGo,
        status_id: 3,
        user_status_name: "Ready To Go",
        system_id: this.system_id,
        system_name: this.system_name,
        campaign_system_id: null,
        node_id: null,
      };

      this.$store.dispatch("updateCampaignUsers", item);
      var request = {
        status_id: 3,
        system_id: this.system_id,
        campaign_system_id: null,
      };

      await axios({
        method: "put",
        url:
          "/api/campaignusers/" + this.charReadyToGo + "/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      request = null;
      request = {
        campaign_user_id: this.charReadyToGo,
        system_id: this.system_id,
        campaign_id: this.campaign_id,
      };

      await axios({
        method: "put",
        url: "/api/campaignsystemremovechar/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.charReadyToGo = null;
    },

    async deleteNode(item) {
      this.$store.dispatch("deleteCampaignSystem", item.id);
      var users = this.$store.getters.getUsersOnNodeByID(item.id);
      var chars = this.$store.getters.getCharsOnNodeByID(item.id);

      if (users.length > 0) {
        users.forEach((user) => {
          var data = {
            id: user.id,
            campaign_system_id: null,
            status_id: 3,
            user_status_name: "Ready to go",
            node_id: null,
          };
          this.$store.dispatch("updateCampaignUsers", data);
        });
      }

      if (chars.length > 0) {
        chars.forEach((chars) => {
          var data = {
            id: chars.id,
            campaign_system_id: null,
            status_id: 3,
            user_status_name: "Ready to go",
            node_id: null,
          };
          this.$store.dispatch("updateUsersChars", data);
        });
      }
      await axios({
        method: "DELETE",
        url: "/api/campaignsystems/" + item.id + "/" + this.campaign_id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      // this.$store.dispatch("getCampaignSystemsRecords");
      // this.$store.dispatch("getCampaignUsersRecords", this.campaign_id);

      //---- Loggin start ----//
      var request = null;
      request = {
        campaign_id: this.campaign_id,
        campaign_system_id: item.node,
        campaign_sola_system_id: this.CampaignSolaSystem[0]["id"],
        user_id: this.$store.state.user_id,
        type: 1,
      };

      axios({
        method: "POST",
        url: "/api/checkdeletenode/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      //------ loggin end-----//
    },

    clickCharAddNode(item) {
      var addChar = this.chars.find((user) => user.id == this.charAddNode);
      var data = {
        id: item.id,
        user_id: addChar.id,
        site_id: this.$store.state.user_id,
        user_name: addChar.char_name,
        main_name: addChar.main_name,
        user_ship: addChar.ship,
        user_link: addChar.link,
      };

      var request = {
        campaign_user_id: addChar.id,
      };
      this.$store.dispatch("updateCampaignSystem", data);

      data = null;
      data = {
        id: addChar.id,
        campaign_system_id: item.id,
        node_id: item.node,
        system_id: item.system_id,
        system_name: item.system_name,
        status_id: 4,
        user_status_name: "Hacking",
      };

      var request1 = {
        campaign_system_id: item.id,
        system_id: item.system_id,
        status_id: 4,
      };
      this.$store.dispatch("updateCampaignUsers", data);
      this.$store.dispatch("updateUsersChars", data);

      axios({
        method: "put",
        url: "/api/campaignsystems/" + item.id + "/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      axios({
        method: "put",
        url: "/api/campaignusers/" + addChar.id + "/" + this.campaign_id,
        withCredentials: true,
        data: request1,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    itemRowBackground: function (item) {
      if (item.status_id == 7) {
        return "style-1";
      } else if (item.status_id == 8) {
        return "style-2";
      } else if (item.under_attack == 1) {
        return "style-4";
      }
    },

    async statusClick(item) {
      var request = [];

      if (
        item.status_id == 1 ||
        item.status_id == 7 ||
        item.status_id == 6 ||
        item.status_id == 9 ||
        item.status_id == 8
      ) {
        item.end = null;
        this.removeCharNode(item);
        item.user_name = null;
        item.main_name = null;
        return;
      }
      if (item.status_id == 2 || item.status_id == 3 || item.status_id == 8) {
        item.end = null;
        request = {
          campaign_system_status_id: item.status_id,
          end_time: null,
        };
      }
      if (item.status_id == 4 || item.status_id == 5) {
        await this.removeCharNode(item);
        item.user_name = null;
        item.main_name = null;
        return;
      }

      await axios({
        method: "put",
        url: "/api/campaignsystems/" + item.id + "/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      // this.$store.dispatch("getCampaignSystemsRecords");
      this.$store.dispatch("getCampaignUsersRecords", this.campaign_id);
    },

    checkShowAdd(item) {
      if (
        item.user_name == null &&
        this.freecharCount != 0 &&
        item.status_id != 4 &&
        item.status_id != 5 &&
        item.status_id != 7 &&
        item.status_id != 8
      ) {
        return true;
      } else {
        return false;
      }
    },

    checkShowAddRemove(item) {
      if (
        item.user_name != null &&
        this.charCount != 0 &&
        item.status_id != 4 &&
        item.status_id != 5 &&
        item.status_id != 7 &&
        item.status_id != 8
      ) {
        return true;
      } else if (this.$can("campaigns_admin_access")) {
        return true;
      } else {
        return false;
      }
    },

    pillColor(item) {
      if (item.status_id == 1) {
        return "deep-orange lighten-1";
      }
      if (item.status_id == 2) {
        return "lime darken-4";
      }
      if (item.status_id == 3 || item.status_id == 8) {
        return "green darken-3";
      }
      if (item.status_id == 4) {
        return "green accent-4";
      }
      if (item.status_id == 5) {
        return "red darken-4";
      }
      if (item.status_id == 6) {
        return "#FF5EEA";
      }
      if (item.status_id == 7) {
        return "#801916";
      }
      if (item.status_id == 9) {
        return "#9C9C9C";
      }
    },
    updatetext(item) {
      this.noteText = this.noteText + "\n";
      if (item.notes == null) {
        var note =
          moment.utc().format("HH:mm:ss") +
          ": " +
          this.$store.state.user_name +
          ": " +
          this.noteText;
      } else {
        var note =
          moment.utc().format("HH:mm:ss") +
          ": " +
          this.$store.state.user_name +
          ": " +
          this.noteText +
          item.notes;
      }

      item.notes = note;
      let request = {
        notes: note,
      };
      this.$store.dispatch("updateCampaignSystem", item);
      axios({
        method: "put",
        url: "/api/campaignsystems/" + item.id + "/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.noteText = null;
    },

    async addNode() {
      let node = this.nodeText.toUpperCase();
      var request = {
        campaign_id: this.campaign_id,
        system_id: this.system_id,
        node_id: node,
      };
      this.nodeText = "";
      this.addShown = false;
      await axios({
        method: "POST",
        url: "/api/campaignsystems/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      // this.$store.dispatch("getCampaignSystemsRecords");

      //----Logging Start-----//
      request = null;
      request = {
        campaign_id: this.campaign_id,
        campaign_system_id: node,
        campaign_sola_system_id: this.CampaignSolaSystem[0]["id"],
        user_id: this.$store.state.user_id,
        type: 1,
      };

      axios({
        method: "POST",
        url: "/api/checkaddnode/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      //----Logging End-----//
    },

    removeCharNode(item) {
      var userId = item.user_id;
      item.user_id = null;
      item.user_ship = null;
      item.user_link = null;
      this.$store.dispatch("updateCampaignSystem", item);
      var data = {
        id: userId,
        node_id: null,
        status_id: 3,
        user_status_name: "Ready to go",
      };
      if (userId != null) {
        this.$store.dispatch("updateCampaignUsers", data);
      }
      var request = null;
      if (item.status_id == 4 || item.status_id == 5) {
        request = {
          campaign_user_id: null,
          campaign_system_status_id: item.status_id,
        };
      } else if (
        item.status_id == 1 ||
        item.status_id == 7 ||
        item.status_id == 9 ||
        item.status_id == 8
      ) {
        request = {
          campaign_user_id: null,
          campaign_system_status_id: item.status_id,
          end_time: null,
        };
      } else {
        request = {
          campaign_user_id: null,
        };
      }
      axios({
        method: "PUT",
        url: "/api/removecharfromnode/" + item.id + "/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async removeReadyToGoOnTheWay(item) {
      var request = null;
      var request = {
        system_id: null,
        status_id: 1,
      };

      await axios({
        method: "PUT",
        url: "/api/campaignusers/" + item.id + "/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    seeReadyToGoOnTheWay(item) {
      if (
        this.$can("campaigns_admin_access") ||
        this.$store.state.user_id == item.site_id
      ) {
        return true;
      } else {
        false;
      }
    },
  },

  computed: {
    ...mapState(["campaignsystems", "user_id"]),

    ...mapGetters([
      "getCampaignUsersByUserIdEntosis",
      "getCampaignUsersByUserIdEntosisCount",
      "getCampaignUsersByUserIdEntosisFree",
      "getCampaignSolaSystemFilter",
      "getTotalNodeCountBySystem",
      "getHackingNodeCountBySystem",
      "getNodeValue",
      "getRedHackingNodeCountBySystem",
      "getSystemReadyToGoCount",
      "getSystemOnTheWayCount",
      "getCampaignUsersReadyToGoAll",
      "getCampaignUsersOnTheWayAll",
      "getCampaignUsersByUserIdEntosisFreeCount",
      "getSystemTableExpandable",
    ]),

    fabOnTheWayDisbale() {
      if (this.OnTheWayCount == 0) {
        return true;
      } else {
        return false;
      }
    },

    fabReadyToGoDisbale() {
      if (this.ReadyToGoCount == 0) {
        return true;
      } else {
        return false;
      }
    },

    filteredItems() {
      // var timers = this.$store.state.timers;
      if (this.statusflag == 1) {
        return this.campaignsystems.filter(
          (s) =>
            s.status_id == 1 &&
            s.system_id == this.system_id &&
            s.campaign_id == this.campaign_id
        );
      }
      if (this.statusflag == 3) {
        return this.campaignsystems.filter(
          (s) =>
            s.status_id == 3 &&
            s.system_id == this.system_id &&
            s.campaign_id == this.campaign_id
        );
      }
      if (this.statusflag == 5) {
        return this.campaignsystems.filter(
          (s) =>
            s.status_id == 5 &&
            s.system_id == this.system_id &&
            s.campaign_id == this.campaign_id
        );
      }
      if (this.statusflag == 6) {
        return this.campaignsystems.filter(
          (s) =>
            s.status_id == 6 &&
            s.system_id == this.system_id &&
            s.campaign_id == this.campaign_id
        );
      } else {
        return this.campaignsystems.filter(
          (s) =>
            s.status_id != 10 &&
            s.system_id == this.system_id &&
            s.campaign_id == this.campaign_id
        );
      }
    },

    chars() {
      return this.getCampaignUsersByUserIdEntosis(this.$store.state.user_id);
    },

    charsReadyToGoAll() {
      return this.getCampaignUsersReadyToGoAll(this.system_id);
    },

    charsOnTheWayAll() {
      return this.getCampaignUsersOnTheWayAll(this.system_id);
    },

    charsFree() {
      return this.getCampaignUsersByUserIdEntosisFree(
        this.$store.state.user_id
      );
    },

    charCount() {
      return this.getCampaignUsersByUserIdEntosisCount(
        this.$store.state.user_id
      );
    },

    nodeCount() {
      let payload = {
        system_id: this.system_id,
        campaign_id: this.campaign_id,
      };
      return this.getTotalNodeCountBySystem(payload);
    },

    nodeCountHackingCount() {
      let payload = {
        system_id: this.system_id,
        campaign_id: this.campaign_id,
      };
      return this.getHackingNodeCountBySystem(payload);
    },

    nodeRedCountHackingCount() {
      let payload = {
        system_id: this.system_id,
        campaign_id: this.campaign_id,
      };
      return this.getRedHackingNodeCountBySystem(payload);
    },

    nodeValue() {
      let payload = {
        system_id: this.system_id,
        campaign_id: this.campaign_id,
      };
      return this.getNodeValue(payload);
    },

    filterCharsOnTheWay() {
      var count = this.getCampaignUsersByUserIdEntosis(
        this.$store.state.user_id
      ).filter(
        (char) => char.status_id == 2 && char.system_id == this.system_id
      ).length;

      if (count > 0) {
        return "green";
      } else {
        return "red";
      }
    },

    filterCharsReadyToGo() {
      var count = this.getCampaignUsersByUserIdEntosis(
        this.$store.state.user_id
      ).filter(
        (char) => char.status_id == 3 && char.system_id == this.system_id
      ).length;

      if (count > 0) {
        return "green";
      } else {
        return "red";
      }
    },

    ReadyToGoCount() {
      let payload = {
        system_id: this.system_id,
        campaign_id: this.campaign_id,
      };
      return this.getSystemReadyToGoCount(payload);
    },

    OnTheWayCount() {
      let payload = {
        system_id: this.system_id,
        campaign_id: this.campaign_id,
      };
      return this.getSystemOnTheWayCount(payload);
    },

    CampaignSolaSystem() {
      let payload = {
        system_id: this.system_id,
        campaign_id: this.campaign_id,
      };
      return this.getCampaignSolaSystemFilter(payload);
    },

    freecharCount() {
      return this.getCampaignUsersByUserIdEntosisFreeCount(
        this.$store.state.user_id
      );
    },

    expanded() {
      let payload = {
        system_id: this.system_id,
        campid: this.campaign_id,
      };
      return this.getSystemTableExpandable(payload);
    },
  },
};
</script>

<style>
.style-4 {
  background-color: rgba(255, 153, 0, 0.199);
}
.style-3 {
  background-color: rgb(255, 172, 77);
}
.style-2 {
  background-color: rgb(30, 30, 30, 1);
}
.style-1 {
  background-color: rgb(184, 22, 35);
}
</style>
