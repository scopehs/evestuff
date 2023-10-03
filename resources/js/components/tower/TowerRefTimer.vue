<template>
  <v-col>
    <div v-if="item.out_time == null">
      <v-menu :close-on-content-click="false" :value="timerShown">
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
                v-model="refTime"
                label="Ref Time d hh:mm:ss"
                autofocus
                v-mask="'#d ##:##:##'"
                placeholder="d:hh:mm:ss"
                @keyup.enter="(timerShown = false), addRefTime(item)"
                @keyup.esc="(timerShown = false), (refTime = null)"
              ></v-text-field>
            </v-card-title>
            <v-card-text>
              <v-btn
                icon
                fixed
                left
                color="success"
                @click="(timerShown = false), addRefTime(item)"
                ><font-awesome-icon icon="fa-solid fa-check"
              /></v-btn>

              <v-btn
                fixed
                right
                icon
                color="warning"
                @click="(timerShown = false), (refTime = null)"
                ><font-awesome-icon icon="fa-solid fa-circle-xmark"
              /></v-btn>
            </v-card-text>
          </v-card>
        </template>
      </v-menu>
    </div>

    <CountDowntimer
      v-if="item.out_time != null"
      :start-time="moment.utc(item.out_time).unix()"
      end-text="Out of ref"
      :interval="1000"
    >
      <template slot="countdown" slot-scope="scope" class="d-inline">
        <span class="blue--text pl-3" v-if="scope.props.days == 0"
          >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
            scope.props.seconds
          }}</span
        >
        <span class="blue--text pl-3" v-if="scope.props.days != 0"
          >{{ numberDay(scope.props.days) }} {{ scope.props.hours }}:{{
            scope.props.minutes
          }}:{{ scope.props.seconds }}</span
        >
        <v-menu :close-on-content-click="false" :value="timerShown">
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              v-bind="attrs"
              v-on="on"
              @click="(timerShown = true), (refTime = null)"
              icon
              color="warning"
            >
              <font-awesome-icon icon="fa-solid fa-pen-to-square" size="2xl" />
            </v-btn>
          </template>

          <template>
            <v-card tile min-height="150px">
              <v-card-title class="pb-0">
                <v-text-field
                  v-model="refTime"
                  label="Repair Time d hh:mm:ss"
                  autofocus
                  v-mask="'#d ##:##:##'"
                  placeholder="d:hh:mm:ss"
                  @keyup.enter="(repairShown = false), addRefTime(item)"
                  @keyup.esc="(timerShown = false), (refTime = null)"
                ></v-text-field>
              </v-card-title>
              <v-card-text>
                <v-btn
                  icon
                  fixed
                  left
                  color="success"
                  @click="(timerShown = false), addRefTime(item)"
                  ><font-awesome-icon icon="fa-solid fa-check"
                /></v-btn>

                <v-btn
                  fixed
                  right
                  icon
                  color="warning"
                  @click="(timerShown = false), (refTime = null)"
                  ><font-awesome-icon icon="fa-solid fa-circle-xmark"
                /></v-btn>
              </v-card-text>
            </v-card>
          </template>
        </v-menu>
      </template>
      <template slot="end-text" slot-scope="scope">
        <span style="color: green">{{ scope.props.endText }}</span>
      </template>
    </CountDowntimer>
  </v-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  props: {
    item: Object,
  },
  data() {
    return {
      timerShown: false,
      refTime: {
        mm: "",
        ss: "",
      },
    };
  },

  methods: {
    async addRefTime(item) {
      var d = parseInt(this.refTime.substr(0, 1));
      var h = parseInt(this.refTime.substr(3, 2));
      var m = parseInt(this.refTime.substr(6, 2));
      var s = parseInt(this.refTime.substr(9, 2));
      var ds = d * 24 * 60 * 60;
      var hs = h * 60 * 60;
      var ms = m * 60;
      var sec = ds + hs + ms + s;
      var finishTime = moment
        .utc()
        .add(sec, "seconds")
        .format("YYYY-MM-DD HH:mm:ss");
      item.out_time = finishTime;
      this.$store.dispatch("updateTowers", item);
      var request = {
        out_time: finishTime,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "api/towerrecords/" + item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
    numberDay(day) {
      return parseInt(day, 10) + "d";
    },
  },

  computed: {},
  async beforeDestroy() {
    await sleep(1000);
  },
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
