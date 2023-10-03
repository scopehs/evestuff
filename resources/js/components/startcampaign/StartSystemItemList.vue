<template>
  <div>
    <span v-if="systemCount">
      <span v-for="(system, index) in systems" :key="index" class="pr-2">
        <q-chip dense :label="system.constellation_name" color="red"
      /></span>
    </span>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  campaignID: Number,
});

let pillColor = (campaign) => {
  if (campaign.alliance.standing < 0) {
    return "red";
  } else if (campaign.alliance.standing == 0) {
    return "webChip";
  } else {
    return "blue";
  }
};

let text = (campaign) => {
  var name = campaign.system.system_name;
  if (campaign.event_type == "32458") {
    var type = "Ihub";
  } else {
    var type = "TCU";
  }

  var text = name + " - " + type;
  return text;
};

let systemCount = $computed(() => {
  let count = store.getStartJoinById(props.campaignID);
  if (count == 0) {
    return false;
  } else {
    return true;
  }
});

let systems = $computed(() => {
  let data = store.getStartJoinById(props.campaignID);
  return data;
});
</script>

<style lang="scss"></style>
