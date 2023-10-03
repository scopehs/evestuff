<template>
  <v-row no-gutters>
    <v-col>
      <VueCountUptimer
        v-if="showAgeCountUp"
        :start-time="moment.utc(timeMoment).unix()"
        :end-text="'Window Closed'"
        :interval="1000"
      >
        <template slot="countup" slot-scope="scope">
          <transition
            name="custom-classes"
            enter-active-class="animate__animated animate__heartBeat animate__repeat-2"
            leave-active-class="animate__animated animate__flash animate__faster"
            mode="out-in"
          >
            <div
              :key="`${node.id}-1-timer-age`"
              v-if="clockRedText(scope.props)"
              class="green--text pl-3"
            >
              {{ scope.props.hours }}:{{ scope.props.minutes }}:{{ scope.props.seconds }}
            </div>

            <div :key="`${node.id}-2-timer-age`" v-else class="red--text pl-3">
              {{ scope.props.hours }}:{{ scope.props.minutes }}:{{ scope.props.seconds }}
            </div>
          </transition>
        </template>
      </VueCountUptimer>
      <v-menu
        :close-on-content-click="false"
        :value="timerShown"
        v-else-if="checkHackUser"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-chip
            v-bind="attrs"
            v-on="on"
            pill
            :outlined="pillOutlined"
            @click="timerShown = true"
            small
            color="warning"
          >
            Add Time
          </v-chip>
        </template>

        <template>
          <v-card tile min-height="150px">
            <v-card-title class="pb-0">
              <v-text-field
                v-model="hackTime"
                label="Hack Time mm:ss"
                v-mask="'##:##'"
                autofocus
                placeholder="mm:ss"
                @keyup.enter="(timerShown = false), addHacktime()"
                @keyup.esc="(timerShown = false), (hackTime = null)"
              ></v-text-field>
            </v-card-title>
            <v-card-text>
              <v-btn
                icon
                fixed
                left
                color="success"
                @click="(timerShown = false), addHacktime()"
                ><font-awesome-icon icon="fa-solid fa-check"
              /></v-btn>

              <v-btn
                fixed
                right
                icon
                color="warning"
                @click="(timerShown = false), (hackTime = null)"
                ><font-awesome-icon icon="fa-solid fa-circle-xmark"
              /></v-btn>
            </v-card-text>
          </v-card>
        </template>
      </v-menu>
      <CountDowntimer v-else :start-time="startTime" :end-text="endText" :interval="1000">
        <template slot="countdown" slot-scope="scope">
          <span :class="hackCountDownTextColor" v-if="node.node_status.id != 8"
            ><span v-if="scope.props.hours > 0">{{ scope.props.hours }}:</span
            >{{ scope.props.minutes }}:{{ scope.props.seconds }}</span
          >

          <v-chip color="blue darken-4" v-else
            ><span v-if="scope.props.hours > 0">{{ scope.props.hours }}:</span
            >{{ scope.props.minutes }}:{{ scope.props.seconds }}</v-chip
          >

          <v-menu :close-on-content-click="false" :value="timerShown">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                v-if="checkHackUserEdit"
                v-bind="attrs"
                v-on="on"
                @click="(timerShown = true), (hackTime = null)"
                icon
                color="warning"
              >
                <font-awesome-icon icon="fa-solid fa-pen-to-square" />
              </v-btn>
            </template>

            <template>
              <v-card tile min-height="150px">
                <v-card-title class="pb-0">
                  <v-text-field
                    v-model="hackTime"
                    label="Hack Time mm:ss"
                    autofocus
                    v-mask="'##:##'"
                    placeholder="mm:ss"
                    @keyup.enter="(timerShown = false), addHacktime()"
                    @keyup.esc="(timerShown = false), (hackTime = null)"
                  ></v-text-field>
                </v-card-title>
                <v-card-text>
                  <v-btn
                    icon
                    fixed
                    left
                    color="success"
                    @click="(timerShown = false), addHacktime()"
                    ><font-awesome-icon icon="fa-solid fa-check"
                  /></v-btn>

                  <v-btn
                    fixed
                    right
                    icon
                    color="warning"
                    @click="(timerShown = false), (hackTime = null)"
                    ><font-awesome-icon icon="fa-solid fa-circle-xmark"
                  /></v-btn>
                </v-card-text>
              </v-card>
            </template>
          </v-menu>
        </template>
        <template slot="end-text" slot-scope="scope">
          <span :style="hackTextColor">{{ scope.props.endText }}</span>
        </template>
      </CountDowntimer>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
import moment from "moment";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    node: Object,
    operationID: Number,
    extra: {
      type: Number,
      default: 1,
    },
    tidiProp: Number,
    systemIDProp: Number,
  },
  data() {
    return {
      timerShown: false,
      hackTime: {
        mm: "",
        ss: "",
      },
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    clockRedText(props) {
      if ((props.minutes < 5 || props.hours > 0) && this.node.node_status.id == 1) {
        return true;
      } else if (
        (props.minutes < 10 || props.hours > 0) &&
        this.node.node_status.id == 2
      ) {
        return true;
      }

      return false;
    },
    async addHacktime() {
      var min = parseInt(this.hackTime.substr(0, 2));
      var sec = parseInt(this.hackTime.substr(3, 2));
      var base = min * 60 + sec;
      var sec = min * 60 + sec;
      if (this.extra == 2) {
        var sec = sec / (this.tidiProp / 100);
      } else {
        var sec = sec / (this.node.system.tidi / 100);
      }
      var finishTime = moment.utc().add(sec, "seconds").format("YYYY-MM-DD HH:mm:ss");

      if (
        this.node.node_status.id == 7 ||
        this.node.node_status.id == 8 ||
        this.node.node_status.id == 9
      ) {
        var request = {
          end_time: finishTime,
          input_time: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
          base_time: base,
          system_id: this.node.system_id,
        };

        await axios({
          method: "put",
          url: "/api/addtimertonode/" + this.node.id,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      } else {
        if (this.extra == 1) {
          var url = this.opUserInfo.id;
          var request = {
            end_time: finishTime,
            input_time: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
            base_time: base,
            system_id: this.node.system_id,
          };
        } else {
          var url = this.node.id;
          var request = {
            end_time: finishTime,
            input_time: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
            base_time: base,
            system_id: this.systemIDProp,
          };
        }

        await axios({
          method: "put",
          url: "/api/addprimetimer/" + url,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    checkHackUserEdit() {
      if (this.extra == 1) {
        if (this.node.prime_node_user.length > 0) {
          var use = this.opUserInfo.node_status.id;
        } else {
          var use = this.node.node_status.id;
        }
      } else {
        var use = this.node.node_status.id;
      }

      if (
        use == 7 || // * Hostile Hacking
        use == 8 || // * Friendly Hacking
        use == 3 || // * Hacking
        use == 9 // * Passive
      ) {
        return true;
      } else {
        return false;
      }
    },

    endText() {
      if (this.node.node_status.id == 7 || this.node.node_status.id == 8) {
        return "Did they Finish?";
      } else if (this.node.node_status.id == 3) {
        return "Did you Finish?";
      } else if (this.node.node_status.id == 9) {
        return "Did it Finish?";
      } else {
        return "Finished!!! ";
      }
    },

    // opUserInfo() {
    //   if (this.extra == 1) {
    //     if (this.node.prime_node_user.length > 0) {
    //       return this.node.prime_node_user[0];
    //     } else {
    //       return null;
    //     }
    //   } else {
    //     return null;
    //   }
    // },

    // showAgeCountUp() {
    //   if (this.extra == 1) {
    //     if (this.node.prime_node_user.length > 0) {
    //       var use = this.node.prime_node_user[0].node_status.id;
    //     } else {
    //       var use = this.node.node_status.id;
    //     }
    //   } else {
    //     var use = this.node.node_status.id;
    //   }
    //   switch (use) {
    //     case 1: // * New
    //       return true;

    //     case 2: // * Warm up
    //       return true;

    //     case 3: // * Hacking
    //       return false;

    //     case 4: // * Success
    //       return false;

    //     case 5: // * Failed
    //       return false;

    //     case 6: // * Pushed off
    //       return true;

    //     case 7: // * Hostile Hacking
    //       return false;

    //     case 8: // * Friendly Hacking
    //       return false;

    //     case 9: // * Passive
    //       return false;

    //     case 10: // * Other
    //       return true;
    //   }
    // },

    startTime() {
      if (this.extra == 1) {
        if (this.opUserInfo) {
          return moment.utc(this.opUserInfo.end_time).unix();
        } else if (this.node.node_status.id == 7 || this.node.node_status.id == 8) {
          return moment.utc(this.node.end_time).unix();
        } else {
          return null;
        }
      } else {
        if (this.node.end_time) {
          return moment.utc(this.node.end_time).unix();
        }
      }
    },

    hackTextColor() {
      if (this.node.node_status.id == 7) {
        return "color: while";
      } else {
        return "color: green";
      }
    },

    hackCountDownTextColor() {
      if (this.node.node_status.id == 7) {
        return "white--text pl-3";
      } else {
        return "blue--text pl-3";
      }
    },

    checkHackUser() {
      if (this.opUserInfo) {
        if (this.opUserInfo.end_time == null && this.opUserInfo.node_status.id == 3) {
          return true;
        } else if (
          this.opUserInfo.end_time == null &&
          (this.node.node_status.id == 7 ||
            this.node.node_status.id == 8 ||
            this.node.node_status.id == 9)
        ) {
          return true;
        } else {
          return false;
        }
      } else {
        if (this.node.node_status.id == 3 && this.node.end_time == null) {
          return true;
        } else if (
          (this.node.node_status.id == 7 ||
            this.node.node_status.id == 8 ||
            this.node.node_status.id == 9) &&
          this.node.end_time == null
        ) {
          return true;
        } else {
          return false;
        }
      }
    },

    pillOutlined() {
      if (this.node.node_status.id == 7 || this.node.node_status.id == 8) {
        return false;
      } else {
        return true;
      }
    },

    // timeMoment() {
    //   if (this.extra == 1) {
    //     if (this.node.node_status.id == 2) {
    //       return moment.utc(this.opUserInfo.updated_at).format("YYYY-MM-DD HH:mm:ss");
    //     } else {
    //       return moment.utc(this.node.created_at).format("YYYY-MM-DD HH:mm:ss");
    //     }
    //   } else {
    //     return moment.utc(this.node.created_at);
    //   }
    // },

    countUptimerColor() {},
  },
  beforeDestroy() {},
};
</script>

<style>
.style-2 {
  background-color: rgb(30, 30, 30, 1);
}
.style-1 {
  background-color: rgb(184, 22, 35);
}
</style>
