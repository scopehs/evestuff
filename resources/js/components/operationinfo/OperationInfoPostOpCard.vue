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
        color="red"
        >Post-Op
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
          <template v-slot:legend-value class="white--text">
            <span slot="legend-value">/5</span>
          </template>
        </Vep></v-btn
      >
    </template>
    <v-row no-gutters>
      <v-col cols="auto">
        <v-card rounded="xl"
          ><v-card-title class="red">Post-Op Cooldown</v-card-title
          ><v-card-text>
            <v-row no-gutters align="end">
              <v-col cols="auto">
                <v-checkbox
                  @change="changeCheck()"
                  hide-details
                  dense
                  v-model="opInfo.post_op_defrief_done"
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
                    :key="opInfo.post_op_defrief_done"
                    :class="cross(opInfo.post_op_defrief_done)"
                  >
                    Post-Op Debrief done
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
                  v-model="opInfo.post_op_scouts_done"
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
                    :key="opInfo.post_op_scouts_done"
                    :class="cross(opInfo.post_op_scouts_done)"
                  >
                    Scouts Stood Down
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
                  v-model="opInfo.post_op_recon_done"
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
                    :key="opInfo.post_op_recon_done"
                    :class="cross(opInfo.post_op_recon_done)"
                  >
                    Recon Stood Down
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
                  v-model="opInfo.post_op_fc_done"
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
                    :key="opInfo.post_op_fc_done"
                    :class="cross(opInfo.post_op_fc_done)"
                  >
                    FC's debriefed and stood down
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
                  v-model="opInfo.post_op_coord_done"
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
                    :key="opInfo.post_op_coord_done"
                    :class="cross(opInfo.post_op_coord_done)"
                    >Coord debriefed
                  </span>
                </transition>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions v-if="showButton"
            ><v-btn text @click="done()">Close Op</v-btn></v-card-actions
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
      await axios({
        method: "DELETE",
        url: "/api/operationinfosheet/" + this.opInfo.link,
        withCredentials: true,
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
        this.opInfo.post_op_coord_done &&
        this.opInfo.post_op_defrief_done &&
        this.opInfo.post_op_fc_done &&
        this.opInfo.post_op_recon_done &&
        this.opInfo.post_op_scouts_done
      ) {
        return true;
      } else {
        return false;
      }
    },

    count() {
      var count =
        this.opInfo.post_op_coord_done +
        this.opInfo.post_op_defrief_done +
        this.opInfo.post_op_fc_done +
        this.opInfo.post_op_recon_done +
        this.opInfo.post_op_scouts_done;
      return count;
    },

    countPercent() {
      return (this.count / 5) * 100;
    },
  },
  beforeDestroy() {},
};
</script>
