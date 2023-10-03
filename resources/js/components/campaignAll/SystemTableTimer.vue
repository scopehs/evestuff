<template>
  <v-col>
    <VueCountUptimer
      v-if="item.status_id < 3 && item.end_time == null"
      :start-time="moment.utc(item.start).unix()"
      :end-text="'Window Closed'"
      :interval="1000"
    >
      <template slot="countup" slot-scope="scope">
        <span v-if="scope.props.minutes < 5" class="green--text pl-3"
          >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
            scope.props.seconds
          }}</span
        >
        <span v-else class="red--text pl-3"
          >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
            scope.props.seconds
          }}</span
        >
      </template>
    </VueCountUptimer>
    <v-menu
      :close-on-content-click="false"
      :value="timerShown"
      v-else-if="checkHackUser(item)"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-chip
          v-bind="attrs"
          v-on="on"
          pill
          :outlined="pillOutlined(item)"
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
              @keyup.enter="(timerShown = false), addHacktime(item)"
              @keyup.esc="(timerShown = false), (hackTime = null)"
            ></v-text-field>
          </v-card-title>
          <v-card-text>
            <v-btn
              icon
              fixed
              left
              color="success"
              @click="(timerShown = false), addHacktime(item)"
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
      v-else
      :start-time="moment.utc(item.end).unix()"
      :end-text="endText(item)"
      :interval="1000"
    >
      <template slot="countdown" slot-scope="scope">
        <span :class="hackCountDownTextColor(item)"
          ><span v-if="scope.props.hours > 0">{{ scope.props.hours }}:</span
          >{{ scope.props.minutes }}:{{ scope.props.seconds }}</span
        >
        <v-menu :close-on-content-click="false" :value="timerShown">
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              v-if="checkHackUserEdit(item)"
              v-bind="attrs"
              v-on="on"
              @click="(timerShown = true), (hackTime = null)"
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
                  v-model="hackTime"
                  label="Hack Time mm:ss"
                  autofocus
                  v-mask="'##:##'"
                  placeholder="mm:ss"
                  @keyup.enter="(timerShown = false), addHacktime(item)"
                  @keyup.esc="(timerShown = false), (hackTime = null)"
                ></v-text-field>
              </v-card-title>
              <v-card-text>
                <v-btn
                  icon
                  fixed
                  left
                  color="success"
                  @click="(timerShown = false), addHacktime(item)"
                  ><font-awesome-icon icon="fa-solid fa-check"
                /></v-btn>

                <v-btn
                  fixed
                  right
                  icon
                  color="warning"
                  @click="(timerShown = false), (hackTime = null)"
                  ><font-awesome-icon icon="fa-solid fa-circle-xmark" />
                  ></v-btn
                >
              </v-card-text>
            </v-card>
          </template>
        </v-menu>
      </template>
      <template slot="end-text" slot-scope="scope">
        <span :style="hackTextColor(item)">{{ scope.props.endText }}</span>
      </template>
    </CountDowntimer>
  </v-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    item: Object,
    CampaignSolaSystem: Array,
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

  methods: {
    async addHacktime(item) {
      var min = parseInt(this.hackTime.substr(0, 2));
      var sec = parseInt(this.hackTime.substr(3, 2));
      var base = min * 60 + sec;
      var sec = min * 60 + sec;
      var sec = sec / (this.CampaignSolaSystem[0]["tidi"] / 100);
      var finishTime = moment
        .utc()
        .add(sec, "seconds")
        .format("YYYY-MM-DD HH:mm:ss");
      item.end = finishTime;
      this.$store.dispatch("updateCampaignSystem", item);
      var request = {
        end_time: finishTime,
        input_time: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
        base_time: base,
      };
      if (this.item.custom_campaign_id != null) {
        var campid = this.$route.params.id;
      } else {
        var campid = this.item.campaign_id;
      }

      await axios({
        method: "put",
        url: "/api/campaignsystems/" + item.id + "/" + campid,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      // this.$store.dispatch("getCampaignSystemsRecords");
    },

    hackCountDownTextColor(item) {
      if (item.status_id == 7) {
        return "white--text pl-3";
      } else {
        return "blue--text pl-3";
      }
    },

    checkHackUser(item) {
      if (
        // item.site_id == this.$store.state.user_id &&
        item.end == null &&
        item.status_id == 3
      ) {
        return true;
      } else if (
        item.end == null &&
        (item.status_id == 7 || item.status_id == 8 || item.status_id == 9)
      ) {
        return true;
      } else {
        return false;
      }

      // if (item.end == null) {
      //     return true;
      // } else {
      //     return false;
      // }
    },
    endText(item) {
      if (item.status_id == 7 || item.status_id == 8) {
        return "Did they Finish?";
      } else if (item.status_id == 3) {
        return "Did you Finish?";
      } else if (item.status_id == 9) {
        return "Did it Finish?";
      } else {
        return "Finished!!! ";
      }
    },

    checkHackUserEdit(item) {
      // if (
      //     item.site_id == this.$store.state.user_id &&
      //     item.status_id == 3
      // ) {
      //     return true;
      // } else if (item.status_id == 7 || item.status_id == 8) {
      //     return true;
      // } else {
      //     return false;
      // }

      if (
        item.status_id == 7 ||
        item.status_id == 8 ||
        item.status_id == 3 ||
        item.status_id == 9
      ) {
        return true;
      } else {
        return false;
      }
    },

    hackTextColor(item) {
      if (item.status_id == 7) {
        return "color: while";
      } else {
        return "color: green";
      }
    },

    pillOutlined(item) {
      if (item.status_id == 7) {
        return false;
      } else {
        return true;
      }
    },
  },

  computed: {},
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
