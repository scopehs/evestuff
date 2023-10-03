<template>
  <VueCountDown
    :time="countUpTimeMil"
    :transform="transformSlotProps"
    @end="onCountdownEnd"
    :interval="1000"
    v-slot="{ hours, minutes, seconds }"
  >
    <span class="text-red">{{ hours }}:{{ minutes }}:{{ seconds }}</span>
  </VueCountDown>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const VueCountDown = defineAsyncComponent(() => import("../countdown/index"));
const props = defineProps({
  row: Object,
});

let onCountdownEnd = () => {
  store.markOver(props.row.id);
};

let countUpTimeMil = $computed(() => {
  let time = null;
  if (props.row.window_station == "Open") {
    time = props.row.end;
  } else {
    time = props.row.start;
  }

  let dateString = time;
  let date = new Date(dateString);
  let timestamp = date.getTime();
  let count = timestamp - new Date();
  return count;
});

let transformSlotProps = (props) => {
  const formattedProps = {};

  Object.entries(props).forEach(([key, value]) => {
    formattedProps[key] = value < 10 ? `0${value}` : String(value);
  });

  return formattedProps;
};
</script>

<style lang="scss"></style>
