<template>
  <div class="row full-width justify-between">
    <div class="col-4">
      <VueCountUp
        class="q-pl-sm"
        :emit-events="false"
        :time="countUpTimeMil(props.list.created_at)"
        :interval="1000"
        v-slot="{ hours, minutes, seconds, days }"
      >
        <span class="text-red" v-if="days >= 1"
          >{{ days }}:{{ hours }}:{{ minutes }}:{{ seconds }}</span
        >
        <span class="text-red" v-else-if="hours >= 1"
          >{{ hours }}:{{ minutes }}:{{ seconds }}</span
        >
        <span v-else>{{ minutes }}:{{ seconds }}</span>
      </VueCountUp>
    </div>
    <div class="col-5">
      {{ props.list.alliancesTotalNumber }}/{{ props.list.corpsTotalNumber }}/{{
        props.list.affiliationsTotalNumber
      }}
    </div>
    <div class="col-2">
      {{ props.list.itemTotalsNumber }}/{{ props.list.categoryTotalsNumber }}
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
const props = defineProps({
  list: [Object, Array],
});

const VueCountUp = defineAsyncComponent(() => import("@/components/countup/index"));

let countUpTimeMil = (time) => {
  if (!time) {
    return 0;
  }
  const timestamp = Date.parse(time);
  return timestamp;
};
</script>

<style lang="scss"></style>
