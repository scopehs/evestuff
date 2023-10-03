<template>
  <div>
    <span v-if="systemCount">
      <span v-for="(campaign, index) in props.campaigns" :key="index" class="pr-2">
        <q-chip dense :label="text(campaign)" :color="pillColor(campaign)"
      /></span> </span
    ><span v-else><div>All Campaigns have finished</div></span>
  </div>
</template>

<script setup>
const props = defineProps({
  campaigns: Array,
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
  let count = props.campaigns.length;
  if (count == 0) {
    return false;
  } else {
    return true;
  }
});
</script>

<style lang="scss"></style>
