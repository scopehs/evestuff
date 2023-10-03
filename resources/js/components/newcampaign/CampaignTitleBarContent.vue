<template>
  <div>
    <transition
      name="custom-classes"
      enter-active-class="animate__animated animate__heartBeat animate__repeat-2"
      leave-active-class="animate__animated animate__flash animate__faster"
      mode="out-in"
    >
      <div class="row items-center" :key="`${props.item.id}-score`" v-if="showScore">
        <div class="col-2">
          <span :class="textColor">
            {{ props.item.system.system_name }} - {{ eventType }}:
            {{ item.alliance.ticker }}
            <q-avatar size="50px"><img :src="item.alliance.url" /></q-avatar
          ></span>
        </div>
        <div class="col-6">
          <div class="row full-width justify-around">
            <div class="col-1 flex justify-end items-center">
              <q-icon :name="defenderIcon" :class="defenderIconColor" />
            </div>
            <div class="col-10 flex-center">
              <q-linear-progress
                :value="props.item.defenders_score"
                rounded
                color="primary"
                track-color="red"
                size="25px"
              >
                <div class="absolute-full flex flex-center">
                  <q-badge color="transparent" text-color="white" :label="text" /></div
              ></q-linear-progress>
            </div>
            <div class="col-1 flex justify-start items-center">
              <q-icon :name="attackerIcon" :class="attackerIconColor" />
            </div>
          </div>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <span class="text-caption"> Active Nodes -</span>
          <q-circular-progress
            size="50px"
            :thickness="0.1"
            :max="totalNodes"
            rounded
            show-value
            :min="0"
            :value="blueNodes"
            color="positive"
            track-color="green-3"
            class="q-my-md"
            >{{ blueNodes }}/{{ totalNodes }}</q-circular-progress
          >
          <q-circular-progress
            size="50px"
            :thickness="0.1"
            :max="totalNodes"
            :min="0"
            :value="redNodes"
            rounded
            show-value
            color="negative"
            track-color="red-3"
            class="q-my-md"
            >{{ redNodes }}/{{ totalNodes }}</q-circular-progress
          >
          <span class="ml-2"> Completed Nodes -</span>
          <q-circular-progress
            size="50px"
            :thickness="0.1"
            :max="totalNodeDone"
            rounded
            show-value
            :min="0"
            :value="totalBlueNodeDone"
            color="positive"
            track-color="green-3"
            class="q-my-md"
            >{{ totalBlueNodeDone }}/{{ totalNodeDone }}</q-circular-progress
          >
          <q-circular-progress
            size="50px"
            :thickness="0.1"
            :max="totalNodeDone"
            :min="0"
            :value="totalRedNodeDone"
            rounded
            show-value
            color="negative"
            track-color="red-3"
            class="q-my-md"
            >{{ totalRedNodeDone }}/{{ totalNodeDone }}</q-circular-progress
          >
        </div>
      </div>
      <div
        class="row"
        v-else-if="props.item.status_id != 3 && props.item.status_id != 4"
        :key="`${props.item.id}-timer`"
      >
        <div class="col-2">
          <span :class="textColor">
            {{ item.system.system_name }} - {{ eventType }}:
            {{ item.alliance.ticker }}
            <q-avatar size="50px"><img :src="item.alliance.url" /></q-avatar
          ></span>
        </div>
        <div class="col-3 flex flex-center">
          <VueCountDown
            :time="countDownTimeMil(item.start_time)"
            :interval="1000"
            :emit-events="false"
            v-slot="{ hours, minutes, seconds, days }"
            :transform="transformSlotProps"
          >
            <span class="text-h5">
              Warm up -
              <span class="text-negative pl-3">
                <span v-if="hours > 1"> {{ hours }}:{{ minutes }}:{{ seconds }} </span>

                <span v-else> {{ minutes }}:{{ seconds }} </span>
              </span></span
            >
          </VueCountDown>
        </div>
      </div>
      <div class="row" v-else :key="`${item.id}-over`">
        <div class="col-2">
          <span :class="textColor">
            {{ item.system.system_name }} - {{ eventType }}:
            {{ item.alliance.ticker }}
            <q-avatar size="50px"><img :src="item.alliance.url" /></q-avatar
          ></span>
        </div>
        <div class="col-3 flex flex-center">
          <span class="text-negative text-h5">Campaign Over</span>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";

const store = useMainStore();

const VueCountDown = defineAsyncComponent(() => import("../countdown/index"));
const props = defineProps({
  item: [Object, Array],
  title: String,
  operationID: Number,
  activeCampaigns: Array,
  warmUpCampaigns: Array,
});

let eventType = $computed(() => {
  if (props.item.event_type == "32458") {
    return "Ihub";
  } else {
    return "TCU";
  }
});

let textColor = $computed(() => {
  if (props.item.alliance.color >= 2) {
    return "text-primary";
  }
  if (props.item.alliance.color < 0) {
    return "text-negative";
  }
});

let countDownTimeMil = (time) => {
  const utcTime = new Date(time + "Z");
  const currentTimestamp = Date.now();
  const timeDiff = utcTime.getTime() - currentTimestamp;
  return timeDiff;
};
let transformSlotProps = (props) => {
  const formattedProps = {};

  Object.entries(props).forEach(([key, value]) => {
    formattedProps[key] = value < 10 ? `0${value}` : String(value);
  });

  return formattedProps;
};

let text = $computed(() => {
  let a = props.item.attackers_score * 100;
  let d = props.item.defenders_score * 100;

  let text = d + "/" + a;
  return text;
});

let attackerIconColor = $computed(() => {
  if (props.item.attackers_score_old) {
    if (props.item.attackers_score_old == props.item.attackers_score) {
      return "text-gray";
    } else if (props.item.attackers_score > props.item.attackers_score_old) {
      return "text-green";
    } else {
      return "text-red";
    }
  }
  return "text-gray";
});

let attackerIcon = $computed(() => {
  if (props.item.attackers_score_old) {
    if (props.item.attackers_score_old == props.item.attackers_score) {
      return "fa-solid fa-circle-minus";
    } else if (props.item.attackers_score > props.item.attackers_score_old) {
      return "fa-solid fa-circle-up";
    } else {
      return "fa-solid fa-circle-up fa-rotate-180";
    }
  }
  return "fa-solid fa-circle-minus";
});

let defenderIconColor = $computed(() => {
  if (props.item.defenders_score_old) {
    if (props.item.defenders_score_old == props.item.defenders_score) {
      return "text-gray";
    } else if (props.item.defenders_score > props.item.defenders_score_old) {
      return "text-green";
    } else {
      return "text-red";
    }
  }
  return "text-gray";
});

let defenderIcon = $computed(() => {
  if (props.item.defenders_score_old) {
    if (props.item.defenders_score_old == props.item.defenders_score) {
      return "fa-solid fa-circle-minus";
    } else if (props.item.defenders_score > props.item.defenders_score_old) {
      return "fa-solid fa-circle-up";
    } else {
      return "fa-solid fa-circle-up fa-rotate-180";
    }
  }
  return "fa-solid fa-circle-minus";
});

let totalNodes = $computed(() => {
  return store.getTotalCampaignNodes(props.item.id);
});

let redNodes = $computed(() => {
  return store.getRedCampaignNodes(props.item.id);
});

let blueNodes = $computed(() => {
  return store.getBlueCampaignNodes(props.item.id);
});

let totalRedNodeDone = $computed(() => {
  return props.item.r_node;
});

let totalBlueNodeDone = $computed(() => {
  return props.item.b_node;
});

let totalNodeDone = $computed(() => {
  return totalRedNodeDone + totalBlueNodeDone;
});

let showScore = $computed(() => {
  var show = false;
  props.activeCampaigns.forEach((element) => {
    if (element.id == props.item.id) {
      show = true;
    }
  });
  return show;
});
</script>

<style lang="scss"></style>
