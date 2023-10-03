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
        color="orange"
        >Form Up
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
            <span slot="legend-value">/8</span>
          </template>
        </Vep></v-btn
      >
    </template>
    <v-row no-gutters>
      <v-col cols="auto">
        <v-card rounded="xl"
          ><v-card-title class="orange">Pre-Op Form Up</v-card-title
          ><v-card-text>
            <v-row no-gutters align="end">
              <v-col cols="auto">
                <v-checkbox
                  @change="changeCheck()"
                  hide-details
                  dense
                  v-model="opInfo.form_op_fc_ready"
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
                    :key="opInfo.form_op_fc_ready"
                    :class="cross(opInfo.form_op_fc_ready)"
                  >
                    FC's ready and informed
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
                  v-model="opInfo.form_op_capital_fc_ready"
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
                    :key="opInfo.form_op_capital_fc_ready"
                    :class="cross(opInfo.form_op_capital_fc_ready)"
                  >
                    Campital FC's ready and informed
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
                  v-model="opInfo.form_op_recon_ready"
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
                    :key="opInfo.form_op_recon_ready"
                    :class="cross(opInfo.form_op_recon_ready)"
                  >
                    Recon Online
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
                  v-model="opInfo.form_op_scouts_ready"
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
                    :key="opInfo.form_op_scouts_ready"
                    :class="cross(opInfo.form_op_scouts_ready)"
                  >
                    Scouts Online
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
                  v-model="opInfo.form_op_gsol_ready"
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
                    :key="opInfo.form_op_gsol_ready"
                    :class="cross(opInfo.form_op_gsol_ready)"
                    >GSOL Online
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
                  v-model="opInfo.form_op_blackhand_ready"
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
                    :key="opInfo.form_op_blackhand_ready"
                    :class="cross(opInfo.form_op_blackhand_ready)"
                    >Black Hand Online
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
                  v-model="opInfo.form_op_gsfoe_ready"
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
                    :key="opInfo.form_op_gsfoe_ready"
                    :class="cross(opInfo.form_op_gsfoe_ready)"
                    >GSFOE Online
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
                  v-model="opInfo.form_op_allies_ready"
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
                    :key="opInfo.form_op_allies_ready"
                    :class="cross(opInfo.form_op_allies_ready)"
                    >Allies Confirmed
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

    async done() {
      this.opInfo.status_id = 3;
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
        this.opInfo.form_op_allies_ready &&
        this.opInfo.form_op_blackhand_ready &&
        this.opInfo.form_op_capital_fc_ready &&
        this.opInfo.form_op_fc_ready &&
        this.opInfo.form_op_gsfoe_ready &&
        this.opInfo.form_op_gsol_ready &&
        this.opInfo.form_op_recon_ready &&
        this.opInfo.form_op_scouts_ready
      ) {
        return true;
      } else {
        return false;
      }
    },

    count() {
      var count =
        this.opInfo.form_op_allies_ready +
        this.opInfo.form_op_blackhand_ready +
        this.opInfo.form_op_capital_fc_ready +
        this.opInfo.form_op_fc_ready +
        this.opInfo.form_op_gsfoe_ready +
        this.opInfo.form_op_gsol_ready +
        this.opInfo.form_op_recon_ready +
        this.opInfo.form_op_scouts_ready;
      return count;
    },

    countPercent() {
      return (this.count / 8) * 100;
    },
  },
  beforeDestroy() {},
};
</script>
