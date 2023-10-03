<template>
  <div>
    <div class="row">
      <div class="col-9">
        <q-btn
          color="positive"
          icon="fa-solid fa-magnifying-glass-location"
          label="System Checked"
          @click="checkClick"
          rounded
          class="myOutLineButton"
        />
      </div>
    </div>
    <div class="row">
      <div class="col" v-if="props.item.check_user">
        Check By {{ props.item.check_user.name }}
        <VueCountUp
          :time="countUpTimeMil(props.item.scouted_at)"
          :interval="1000"
          v-slot="{ hours, minutes, seconds }"
        >
          <transition
            name="custom-classes"
            enter-active-class="animate__animated animate__heartBeat animate__repeat-2"
            leave-active-class="animate__animated animate__flash"
            mode="out-in"
          >
            <span
              :key="`${props.item.id}-1-timer-age`"
              v-if="hours == 0 && minutes < 5"
              class="text-positive"
            >
              {{ hours }}:{{ minutes }}:{{ seconds }}</span
            >
            <span v-else :key="`${props.item.id}-2-timer-age`" class="text-negative">
              {{ hours }}:{{ minutes }}:{{ seconds }}</span
            >
          </transition>
        </VueCountUp>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { defineAsyncComponent } from "vue";
let store = useMainStore();

const VueCountUp = defineAsyncComponent(() => import("../countup/index"));
const props = defineProps({
  item: Object,
  operationID: Number,
});
let checkClick = async () => {
  var data = {
    user_id: store.user_id,
  };

  await axios({
    method: "POST",
    url: "/api/checkedat/" + props.item.id,
    data: data,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let countUpTimeMil = (time) => {
  const timestamp = Date.parse(time);
  return timestamp;
};
</script>

<style lang="scss"></style>
