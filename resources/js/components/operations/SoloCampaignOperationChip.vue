<template>
  <div
    class="col-4 flex flex-center"
    v-if="props.row.campaign[0].status_id == 1 || props.row.campaign[0].status_id == 5"
  >
    <VueCountDown
      :time="countDownTimeMil(props.row.campaign[0].start_time)"
      :interval="1000"
      :emit-events="false"
      v-slot="{ hours, minutes, seconds, days }"
      :transform="transformSlotProps"
    >
      <span v-if="hours <= 1 && days == 0 && can('access_campaigns')" class="text-red">
        <q-chip
          class=""
          filter
          pill
          :disabled="pillDisabled"
          clickable
          color="primary"
          text-color="white"
        >
          <span v-if="hours == 1"> {{ hours }}:{{ minutes }}:{{ seconds }} </span>
          <span v-else> {{ minutes }}:{{ seconds }} </span>
        </q-chip>
      </span>
      <span v-else class="text-red"
        ><span v-if="props.row.priority == 0"
          >{{ days }}:{{ hours }}:{{ minutes }}:{{ seconds }}</span
        ><span v-else
          ><q-chip color="red"
            >{{ days }}:{{ hours }}:{{ minutes }}:{{ seconds }}</q-chip
          ></span
        ></span
      >
    </VueCountDown>
  </div>
  <div
    class="col-4 flex flex-center"
    v-if="
      (props.row.campaign[0].status_id == 2 ||
        props.row.campaign[0].status_id == 3 ||
        props.row.campaign[0].status_id == 4) &&
      can('access_campaigns')
    "
  >
    <div>
      <q-chip class clickable :disabled="pillDisabled" :color="pillColor(props.row)">
        {{ props.row.campaign[0].status.name }}
        <span class="q-pl-xs" v-if="props.row.campaign[0].status_id == 2">
          <VueCountUp
            :time="countUpTimeMil(props.row.campaign[0].start_time)"
            :interval="1000"
            v-slot="{ hours, minutes, seconds }"
          >
            <span>{{ hours }}:{{ minutes }}:{{ seconds }}</span>
          </VueCountUp></span
        >
      </q-chip>
    </div>
  </div>
  <div
    class="col-4 flex flex-center"
    v-else-if="
      props.row.campaign[0].status_id == 2 ||
      props.row.campaign[0].status_id == 3 ||
      props.row.campaign[0].status_id == 4
    "
  >
    <div>
      <q-chip class :disabled="pillDisabled" :color="pillColor(props.row)">
        {{ props.row.campaign[0].status.name }}
        <span class="q-pl-xs" v-if="props.row.campaign[0].status_id == 2">
          <VueCountUp
            :time="countUpTimeMil(props.row.campaign[0].start_time)"
            :interval="1000"
            v-slot="{ hours, minutes, seconds }"
          >
            <span>{{ hours }}:{{ minutes }}:{{ seconds }}</span>
          </VueCountUp></span
        >
      </q-chip>
    </div>
  </div>
</template>

<script setup>
import { defineAsyncComponent, inject } from "vue";
const VueCountDown = defineAsyncComponent(() => import("../countdown/index"));
const VueCountUp = defineAsyncComponent(() => import("../countup/index"));
let can = inject("can");
const props = defineProps({
  row: Object,
});

let countDownTimeMil = (time) => {
  const utcTime = new Date(time + "Z");
  const currentTimestamp = Date.now();
  const timeDiff = utcTime.getTime() - currentTimestamp;
  return timeDiff;
};

let countUpTimeMil = (time) => {
  const timestamp = Date.parse(time);
  return timestamp;
};

let pillDisabled = $computed(() => {
  if (props.row.campaign[0].status_id == 3) {
    return true;
  } else {
    false;
  }
});

let pillColor = (item) => {
  if (item.campaign[0].status_id == 3) {
    return "blue-grey darken-4";
  }

  return "green darken-3";
};

let transformSlotProps = (props) => {
  const formattedProps = {};

  Object.entries(props).forEach(([key, value]) => {
    formattedProps[key] = value < 10 ? `0${value}` : String(value);
  });

  return formattedProps;
};
</script>
