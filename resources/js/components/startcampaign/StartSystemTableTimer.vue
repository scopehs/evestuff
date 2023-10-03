<template>
  <v-col>
    <v-menu
      :close-on-content-click="false"
      :value="timerShown"
      v-if="checkHackUser(item)"
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
      v-else-if="item.end_time != null"
      :start-time="moment.utc(item.end_time).unix()"
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
                  ><font-awesome-icon icon="fa-solid fa-circle-xmark"
                /></v-btn>
              </v-card-text>
            </v-card>
          </template>
        </v-menu>
      </template>
      <template slot="end-text" slot-scope="scope">
        <span :style="hackTextColor(item)">{{ scope.props.endText }}</span>
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
                  ><font-awesome-icon icon="fa-solid fa-circle-xmark"
                /></v-btn>
              </v-card-text>
            </v-card>
          </template>
        </v-menu>
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
      var sec = sec / (100 / 100);
      var finishTime = moment
        .utc()
        .add(sec, "seconds")
        .format("YYYY-MM-DD HH:mm:ss");
      item.end_time = finishTime;
      this.$store.dispatch("updateStartCampaignSystem", item);
      var request = {
        end_time: finishTime,
        input_time: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
        base_time: base,
      };

      await axios({
        method: "put",
        url:
          "/api/startcampaignsystemupdatetimer/" +
          item.id +
          "/" +
          item.start_campaign_id,
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
      if (item.site_id == this.$store.state.user_id && item.end_time == null) {
        return true;
      } else {
        return false;
      }
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
      if (
        item.site_id == this.$store.state.user_id ||
        this.$can("access_multi_campaigns")
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
