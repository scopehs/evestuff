<template>
  <v-menu
    :close-on-content-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        rounded
        v-bind="attrs"
        v-on="on"
        @click="addShown = true"
        class="elevation-10"
        color="green"
        >Planning
        <Vep
          :progress="countPercent"
          :size="30"
          :legend-value="count"
          fontSize="0.60rem"
          color="#00ff00"
          :thickness="4"
          :emptyThickness="1"
          emptyColor="#a4fca4"
        >
          <template v-slot:legend-value>
            <span slot="legend-value">/7</span>
          </template>
        </Vep></v-btn
      >
    </template>
    <v-row no-gutters>
      <v-col cols="auto">
        <v-card rounded="xl"
          ><v-card-title class="green">Pre-Op Planning</v-card-title
          ><v-card-text>
            <v-row no-gutters>
              <v-col cols="auto"
                ><v-btn
                  text
                  class="lowercase"
                  @click="opInfo.planing_op_posted = !opInfo.planing_op_posted"
                  >Formup Time</v-btn
                ></v-col
              ><v-col cols="auto"></v-col>
            </v-row>
            <v-row no-gutters align="end">
              <v-col cols="auto">
                <v-checkbox
                  @change="changeCheck()"
                  hide-details
                  dense
                  v-model="opInfo.planing_op_posted"
                  :false-value="0"
                  :true-value="1"
                >
                </v-checkbox>
              </v-col>
              <v-col cols="auto">
                <transition
                  mode="out-in"
                  :enter-active-class="showEnter"
                  :leave-active-class="showLeave"
                >
                  <span
                    :key="opInfo.planing_op_posted"
                    :class="cross(opInfo.planing_op_posted)"
                    ><span
                      style="cursor: pointer"
                      @click="clickButtonOpPosted(opInfo.planing_op_posted)"
                    >
                      Forum OP Posted?</span
                    >
                  </span>
                </transition>
              </v-col>
            </v-row>
            <v-row no-gutters align="end">
              <v-col cols="auto">
                <v-checkbox
                  @change="changeCheck()"
                  hide-details
                  dense
                  v-model="opInfo.planing_op_pre_ping"
                  :false-value="0"
                  :true-value="1"
                >
                </v-checkbox>
              </v-col>
              <v-col cols="auto">
                <transition
                  mode="out-in"
                  :enter-active-class="showEnter"
                  :leave-active-class="showLeave"
                >
                  <span
                    :key="opInfo.planing_op_pre_ping"
                    :class="cross(opInfo.planing_op_pre_ping)"
                  >
                    OP Pre-Pinged?
                  </span>
                </transition>
              </v-col>
            </v-row>
            <v-row no-gutters align="end">
              <v-col cols="auto">
                <v-checkbox
                  @change="changeCheck()"
                  hide-details
                  dense
                  v-model="opInfo.planing_op_recon_alerted"
                  :false-value="0"
                  :true-value="1"
                >
                </v-checkbox>
              </v-col>
              <v-col cols="auto">
                <transition
                  mode="out-in"
                  :enter-active-class="showEnter"
                  :leave-active-class="showLeave"
                >
                  <span
                    :key="opInfo.planing_op_recon_alerted"
                    :class="cross(opInfo.planing_op_recon_alerted)"
                  >
                    Reacon Informed
                  </span>
                </transition>
              </v-col>
            </v-row>
            <v-row no-gutters align="end">
              <v-col cols="auto">
                <v-checkbox
                  @change="changeCheck()"
                  hide-details
                  dense
                  v-model="opInfo.planing_op_capital_fc_found"
                  :false-value="0"
                  :true-value="1"
                >
                </v-checkbox>
              </v-col>
              <v-col cols="auto">
                <transition
                  mode="out-in"
                  :enter-active-class="showEnter"
                  :leave-active-class="showLeave"
                >
                  <span
                    :key="opInfo.planing_op_capital_fc_found"
                    :class="cross(opInfo.planing_op_capital_fc_found)"
                  >
                    Capaital FC's Found
                  </span>
                </transition>
              </v-col>
            </v-row>
            <v-row no-gutters align="end"
              ><v-col cols="auto"
                ><v-checkbox
                  @change="changeCheck()"
                  hide-details
                  dense
                  v-model="opInfo.planing_op_fc_found"
                  :false-value="0"
                  :true-value="1"
                >
                </v-checkbox>
              </v-col>
              <v-col cols="auto">
                <transition
                  mode="out-in"
                  :enter-active-class="showEnter"
                  :leave-active-class="showLeave"
                >
                  <span
                    :key="opInfo.planing_op_fc_found"
                    :class="cross(opInfo.planing_op_fc_found)"
                    >FC's Found
                  </span>
                </transition>
              </v-col>
            </v-row>
            <v-row no-gutters align="end"
              ><v-col cols="auto"
                ><v-checkbox
                  @change="changeCheck()"
                  hide-details
                  dense
                  v-model="opInfo.planing_op_doctromes_decoded"
                  :false-value="0"
                  :true-value="1"
                >
                </v-checkbox>
              </v-col>
              <v-col cols="auto">
                <transition
                  mode="out-in"
                  :enter-active-class="showEnter"
                  :leave-active-class="showLeave"
                >
                  <span
                    :key="opInfo.planing_op_doctromes_decoded"
                    :class="cross(opInfo.planing_op_doctromes_decoded)"
                    >Doctrines decided
                  </span>
                </transition>
              </v-col>
            </v-row>
            <v-row no-gutters align="end"
              ><v-col cols="auto"
                ><v-checkbox
                  @change="changeCheck()"
                  hide-details
                  dense
                  v-model="opInfo.planing_op_allies_infored"
                  :false-value="0"
                  :true-value="1"
                >
                </v-checkbox>
              </v-col>
              <v-col cols="auto">
                <transition
                  mode="out-in"
                  :enter-active-class="showEnter"
                  :leave-active-class="showLeave"
                >
                  <span
                    :key="opInfo.planing_op_allies_infored"
                    :class="cross(opInfo.planing_op_allies_infored)"
                    >Allies Informed
                  </span>
                </transition>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions v-if="showButton"
            ><v-btn text @click="done()">Next</v-btn></v-card-actions
          >
        </v-card>
      </v-col>
    </v-row>
  </v-menu>
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
    loaded: Boolean,
  },
  data() {
    return {
      addShown: false,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    cross(value) {
      if (value == 1) {
        return "text-decoration-line-through green--text";
      }
    },
    clickButtonOpPosted(item) {
      if (item == 1) {
        var num = 0;
      } else {
        var num = 1;
      }

      this.opInfo.planing_op_posted = num;
      this.changeCheck();
    },
    async done() {
      this.opInfo.status_id = 2;
      var request = this.opInfo;
      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/operationinfopage/" + this.opInfo.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async changeCheck() {
      var request = this.opInfo;
      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/operationinfopage/" + this.opInfo.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState["operationInfoPage"],

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },

    showEnter() {
      if (this.loaded == true) {
        return "animate__animated animate__flash animate__faster";
      }
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__flash animate__faster";
      }
    },

    showButton() {
      if (
        this.opInfo.planing_op_allies_infored &&
        this.opInfo.planing_op_capital_fc_found &&
        this.opInfo.planing_op_doctromes_decoded &&
        this.opInfo.planing_op_fc_found &&
        this.opInfo.planing_op_posted &&
        this.opInfo.planing_op_pre_ping &&
        this.opInfo.planing_op_recon_alerted
      ) {
        return true;
      } else {
        return false;
      }
    },

    count() {
      var count =
        this.opInfo.planing_op_allies_infored +
        this.opInfo.planing_op_capital_fc_found +
        this.opInfo.planing_op_doctromes_decoded +
        this.opInfo.planing_op_fc_found +
        this.opInfo.planing_op_posted +
        this.opInfo.planing_op_pre_ping +
        this.opInfo.planing_op_recon_alerted;
      return count;
    },

    countPercent() {
      return (this.count / 7) * 100;
    },
  },
  beforeDestroy() {},
};
</script>
