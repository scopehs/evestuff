<template>
  <v-card
    tile
    max-width="1500px"
    max-height="1000px"
    class="d-inline-flex flex-column"
  >
    <v-card-title class="d-lg-inline-block justify-space-between align-center">
      <div>Tidi Calculator</div>
    </v-card-title>
    <v-card-text class="body-1 d-inline-flex align-items-center">
      <div class="pt-4 pr-10">
        <v-menu
          :close-on-content-click="false"
          :value="timerShown"
          v-if="showAddButton()"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-chip
              v-bind="attrs"
              v-on="on"
              pill
              outlined
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
        <CountDowntimer
          :start-time="moment.utc(finishTime).unix()"
          end-text="Time is over"
          :interval="1000"
        >
          <template slot="countdown" slot-scope="scope">
            <span
              ><span v-if="scope.props.hours > 0">{{ scope.props.hours }}:</span
              >{{ scope.props.minutes }}:{{ scope.props.seconds }}</span
            >
            <v-menu :close-on-content-click="false" :value="timerShown">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  v-bind="attrs"
                  v-on="on"
                  @click="(timerShown = true), (hackTime = null)"
                  icon
                  color="warning"
                  ><font-awesome-icon icon="fa-solid fa-pen-to-square" />
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
            <span>{{ scope.props.endText }}</span>
          </template>
        </CountDowntimer>
      </div>
      <div class="pt-4">
        TiDi:
        <span :class="colorTidi()">{{ tidi }}%</span>
        <v-menu :close-on-content-click="false" :value="tidiShow">
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              class="pb-1"
              v-bind="attrs"
              v-on="on"
              @click="(tidiShow = true), (tidiEdit = null)"
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
                  v-model="tidiEdit"
                  label="Tidi %"
                  autofocus
                  v-mask="'###'"
                  :placeholder="placeHolder()"
                  @keyup.enter="(tidiShow = false), editTidi()"
                  @keyup.esc="(tidiShow = false), (tidiEdit = null)"
                ></v-text-field>
              </v-card-title>
              <v-card-text>
                <v-btn
                  icon
                  fixed
                  left
                  color="success"
                  @click="(tidiShow = false), editTidi()"
                  ><font-awesome-icon icon="fa-solid fa-check"
                /></v-btn>

                <v-btn
                  fixed
                  right
                  icon
                  color="warning"
                  @click="(tidiShow = false), (tidiEdit = null)"
                  ><font-awesome-icon icon="fa-solid fa-circle-xmark"
                /></v-btn>
              </v-card-text>
            </v-card>
          </template>
        </v-menu>
      </div>
    </v-card-text>
    <v-spacer></v-spacer
    ><v-card-actions
      ><v-btn class="white--text" color="teal" @click="close()">
        Close
      </v-btn></v-card-actions
    >
  </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
import moment, { duration } from "moment";
export default {
  props: {},
  data() {
    return {
      overlay: true,
      timerShown: false,
      tidiShow: false,
      hackTime: {
        mm: "",
        ss: "",
      },
      base_time: null,
      finishTime: null,
      input_time: null,
      tidi: 100,
      tidiEdit: null,
      test: null,
    };
  },

  methods: {
    close() {
      this.$emit("closeCalc", "yo");
    },

    async addHacktime() {
      var min = parseInt(this.hackTime.substr(0, 2));
      var sec = parseInt(this.hackTime.substr(3, 2));
      this.base_time = min * 60 + sec;
      var sec = min * 60 + sec;
      var sec = sec / (this.tidi / 100);
      this.finishTime = moment
        .utc()
        .add(sec, "seconds")
        .format("YYYY-MM-DD HH:mm:ss");
      this.input_time = moment.utc().format("YYYY-MM-DD HH:mm:ss");
    },

    showAddButton() {
      if (this.base_time == null) {
        return true;
      } else {
        return false;
      }
    },
    colorTidi() {
      if (this.tidi > 59) {
        return "green--text font-weight-bold";
      } else if (this.tidi > 34) {
        return "orange--text font-weight-bold";
      } else {
        return "red--text font-weight-bold";
      }
    },

    placeHolder() {
      return "" + this.tidi;
    },

    editTidi() {
      if (this.input_time != null) {
        var now = moment.utc();
        var diff = moment.duration(now.diff(this.input_time));
        var diffSec = diff.asSeconds();
        this.base_time = this.base_time - diffSec;
        var time_left = this.base_time / (this.tidiEdit / 100);
        this.finishTime = moment
          .utc()
          .add(time_left, "seconds")
          .format("YYYY-MM-DD HH:mm:ss");
      }
      this.tidi = this.tidiEdit;
      this.tidiEdit = null;
    },
  },

  computed: {},
};
</script>

<style></style>
