<template>
  <div>
    <CountDowntimer
      v-if="done"
      :start-time="countDownStartTime"
      :end-text="'OUT'"
      :interval="1000"
      :day-text="'Days'"
      @campaignStart="updateDone()"
    >
      <template slot="countdown" slot-scope="scope">
        <span v-if="scope.props.days == 0"
          >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
            scope.props.seconds
          }}</span
        >
        <span v-if="scope.props.days != 0"
          >{{ numberDay(scope.props.days) }} {{ scope.props.hours }}:{{
            scope.props.minutes
          }}:{{ scope.props.seconds }}</span
        >
      </template>
    </CountDowntimer>
    <VueCountUptimer
      v-else
      :start-time="countDownStartTime"
      :end-text="'Window Closed'"
      :interval="1000"
      ><template slot="countup" slot-scope="scope"
        ><span
          v-if="scope.props.minutes < 5 && scope.props.hours == 0"
          class="green--text pl-2 pr-2"
          >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
            scope.props.seconds
          }}</span
        >
        <span v-else class="red--text pl-2 pr-2"
          >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
            scope.props.seconds
          }}</span
        >
      </template>
    </VueCountUptimer>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { EventBus } from "../../app";
import moment from "moment";
export default {
  props: {
    station: Object,
  },
  data() {
    return {
      done: 0,
    };
  },

  async created() {
    this.setdone();
  },

  methods: {
    setdone() {
      var outTime = moment.utc(this.station.out_time);
      var now = moment.utc();
      if (outTime.isAfter(now)) {
        this.done = true;
      } else {
        this.done = false;
      }
    },

    numberDay(day) {
      return parseInt(day, 10) + "d";
    },

    updateDone() {
      this.done = false;
      EventBus.$emit("timerDone", this.station.id);
    },
  },

  computed: {
    countDownStartTime() {
      return moment.utc(this.station.out_time).unix();
    },
  },

  beforeDestroy() {},
};
</script>

<style>
</style>
