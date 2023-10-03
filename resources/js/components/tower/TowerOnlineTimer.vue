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
                v-model="anchoringTime"
                label="Anchoring Time mm:ss"
                v-mask="'##:##'"
                autofocus
                placeholder="mm:ss"
                @keyup.enter="(timerShown = false), addAnchoringTime(item)"
                @keyup.esc="(timerShown = false), (anchoringTime = null)"
              ></v-text-field>
            </v-card-title>
            <v-card-text>
              <v-btn
                icon
                fixed
                left
                color="success"
                @click="(timerShown = false), addAnchoringTime(item)"
                ><font-awesome-icon icon="fa-solid fa-check"
              /></v-btn>

              <v-btn
                fixed
                right
                icon
                color="warning"
                @click="(timerShown = false), (anchoringTime = null)"
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
      end-text="Did it anchor?"
      :interval="1000"
    >
      <template slot="countdown" slot-scope="scope">
        <span class="red--text pl-3"
          >{{ scope.props.minutes }}:{{ scope.props.seconds }}</span
        >
        <v-menu :close-on-content-click="false" :value="timerShown">
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              v-bind="attrs"
              v-on="on"
              @click="(timerShown = true), (anchoringTime = null)"
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
                  v-model="anchoringTime"
                  label="Repair Time mm:ss"
                  autofocus
                  v-mask="'##:##'"
                  placeholder="mm:ss"
                  @keyup.enter="(repairShown = false), addAnchoringTime(item)"
                  @keyup.esc="(timerShown = false), (anchoringTime = null)"
                ></v-text-field>
              </v-card-title>
              <v-card-text>
                <v-btn
                  icon
                  fixed
                  left
                  color="success"
                  @click="(timerShown = false), addAnchoringTime(item)"
                  ><font-awesome-icon icon="fa-solid fa-check"
                /></v-btn>

                <v-btn
                  fixed
                  right
                  icon
                  color="warning"
                  @click="(timerShown = false), (anchoringTime = null)"
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
      anchoringTime: {
        mm: "",
        ss: "",
      },
    };
  },

  methods: {
    async addAnchoringTime(item) {
      var min = parseInt(this.anchoringTime.substr(0, 2));
      var sec = parseInt(this.anchoringTime.substr(3, 2));
      var finishTime = moment
        .utc()
        .add(sec, "seconds")
        .add(min, "minutes")
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
  },

  computed: {},
  async beforeDestroy() {},
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
