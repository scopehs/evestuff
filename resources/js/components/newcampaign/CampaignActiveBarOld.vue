<template>
  <v-row no-gutters>
    <v-col>
      <v-expansion-panels
        v-model="hidePannel"
        readonly
        popout
        style="cursor: context-menu"
      >
        <v-expansion-panel class="rounded-xl" style="cursor: context-menu">
          <v-expansion-panel-header expand-icon="" style="cursor: context-menu">
            <v-row no-gutters align="baseline" justify="space-between">
              <v-col cols="auto">
                <v-row no-gutters>
                  <!-- <v-col cols="auto" class="pr-2">
                    <v-btn
                      color="primary"
                      @click="btnShowCharTable"
                      :outlined="charTableOutlined"
                      rounded
                      small
                    >
                      Char Table
                    </v-btn>
                    </v-col> -->
                  <!-- <v-col cols="auto"
                    ><AddOperationUser :operationID="operationID"
                  />
                  </v-col> -->
                  <!-- <v-col cols="auto"><OperationCal :operationID="operationID" /></v-col> -->
                </v-row>
              </v-col>
              <v-col cols="auto" v-if="$can('access_multi_campaigns')">
                <v-tooltip bottom color="#121212">
                  <!-- <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      v-if="$can('access_campaigns')"
                      icon
                      dark
                      class="mr-4"
                      small
                      v-bind="attrs"
                      color="blue"
                      v-on="on"
                      @click="sendAddCharMessage()"
                    >
                      <font-awesome-icon icon="fa-solid fa-bullhorn" size="xl" />
                    </v-btn>
                  </template> -->
                  <span> Send a message to all Users without a Char to add chars </span>
                </v-tooltip>
                <v-tooltip bottom color="#121212">
                  <!-- <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      v-if="$can('access_campaigns')"
                      icon
                      dark
                      class="mr-4"
                      small
                      v-bind="attrs"
                      color="red"
                      v-on="on"
                      @click="sendAddCharMessagePlus()"
                    >
                      <font-awesome-icon icon="fa-solid fa-bullhorn" size="xl" />
                    </v-btn>
                  </template> -->
                  <span>
                    Send a message to all Users without a Char added to add chars and head
                    to systems
                  </span>
                </v-tooltip>
              </v-col>

              <v-col cols="auto" v-if="$can('view_campaign_members')">
                <!-- <v-btn
                  color="primary"
                  @click="btnShowUserTable"
                  :outlined="userTableOutlined"
                  rounded
                  small
                >
                  User List
                </v-btn> -->
              </v-col>
              <v-col cols="auto" v-if="$can('view_campaign_members')">
                <!-- <v-btn
                  color="primary"
                  @click="btnShowLogTable"
                  :outlined="logTableOutlined"
                  rounded
                  small
                >
                  Logs
                </v-btn> -->
              </v-col>
              <v-col cols="auto" v-if="$can('edit_read_only')">
                <!-- <v-row no-gutters>
                  <v-col cols="auto">
                    <v-switch
                      class="mt-0 pt-0"
                      dense
                      hide-details
                      v-model="opInfoReadOnly"
                      @change="updateReadOnly()"
                    ></v-switch>
                  </v-col>
                  <v-col cols="auto" class="d-flex align-baseline">
                    <transition
                      mode="out-in"
                      enter-active-class="animate__animated animate__flash animate__faster"
                      leave-active-class="animate__animated animate__flash animate__faster"
                    >
                      <span
                        class="pt-1"
                        :key="opInfoReadOnly"
                        :class="readOnlyClassColor"
                      >
                        Read Only - {{ readOnlyText }}</span
                      >
                    </transition>
                  </v-col>
                </v-row> -->
              </v-col>
              <!-- <v-col cols="auto">
                <v-btn
                  :exact="true"
                  color="primary"
                  class="rounded-l-xl"
                  @click="toggleopen"
                  small
                  rounded-l-xl
                >
                  Open
                </v-btn>
                <v-btn
                  :exact="true"
                  color="primary"
                  class="rounded-r-xl"
                  @click="toggleclose"
                  small
                >
                  Close
                </v-btn>
              </v-col> -->
            </v-row>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <!-- <OperationUserTable
              v-if="charTable"
              :operationID="operationID"
              :windowSize="windowSize"
            /> -->
            <!-- <OperationUserListTable
              v-if="usertable"
              :operationID="operationID"
              :windowSize="windowSize"
            /> -->

            <!-- <OperationLogTable
              v-if="logTable"
              :operationID="operationID"
              :windowSize="windowSize"
            /> -->
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    operationID: Number,
    windowSize: Object,
  },
  data() {
    return {
      hidePannel: 1,
      charTable: 0,
      usertable: 0,
      logTable: 0,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    btnShowCharTable() {
      if (this.hidePannel == 1) {
        this.hidePannel = 0;
        this.charTable = 1;
      } else {
        if (this.usertable == 1 || this.logTable == 1) {
          this.usertable = 0;
          this.logTable = 0;
          this.charTable = 1;
        } else {
          this.hidePannel = 1;
          this.charTable = 0;
        }
      }
    },

    async btnShowLogTable() {
      if (this.hidePannel == 1) {
        this.hidePannel = 0;
        this.logTable = 1;
        await this.$store.dispatch("getCampaignsList", this.operationID);
      } else {
        if (this.usertable == 1 || this.charTable == 1) {
          this.usertable = 0;
          this.logTable = 1;
          this.charTable = 0;
          await this.$store.dispatch("getCampaignsList", this.operationID);
        } else {
          this.hidePannel = 1;
          this.logTable = 0;
        }
      }
    },

    async sendAddCharMessage() {
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/sendadduseroverlay/" + this.operationID + "/" + 1,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async updateReadOnly() {
      if (this.opInfoReadOnly == true) {
        var request = {
          read_only: 1,
        };
      } else {
        var request = {
          read_only: 0,
        };
      }
      await axios({
        method: "POST",
        url: "/api/setreadonly/" + this.operationID,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async sendAddCharMessagePlus() {
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/sendadduseroverlay/" + this.operationID + "/" + 2,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    btnShowUserTable() {
      if (this.hidePannel == 1) {
        this.hidePannel = 0;
        this.usertable = 1;
      } else {
        if (this.charTable == 1 || this.logTable == 1) {
          this.charTable = 0;
          this.logTable = 0;
          this.usertable = 1;
        } else {
          this.hidePannel = 1;
          this.usertable = 0;
        }
      }
    },

    toggleopen() {
      EventBus.$emit("showSystemTable", 1);
    },

    toggleclose() {
      EventBus.$emit("showSystemTable", 0);
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["newOperationInfo"]),

    opInfoReadOnly: {
      get() {
        return this.newOperationInfo.read_only;
      },
      set(newValue) {
        return this.$store.dispatch("setReadOnly", newValue);
      },
    },

    // readOnlyText() {
    //   if (this.opInfoReadOnly == 1) {
    //     return "On";
    //   } else {
    //     return "Off";
    //   }
    // },

    readOnlyClassColor() {
      if (this.opInfoReadOnly == 1) {
        return "blue--text";
      } else {
        return "red--text";
      }
    },

    // charTableOutlined() {
    //   if (this.hidePannel == 0 && this.charTable == 1) {
    //     return false;
    //   } else {
    //     return true;
    //   }
    // },

    userTableOutlined() {
      if (this.hidePannel == 0 && this.usertable == 1) {
        return false;
      } else {
        return true;
      }
    },

    logTableOutlined() {
      if (this.hidePannel == 0 && this.logTable == 1) {
        return false;
      } else {
        return true;
      }
    },
  },
  beforeDestroy() {},
};
</script>
