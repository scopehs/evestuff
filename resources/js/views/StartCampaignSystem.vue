<template>
  <div>{{ route.params.id }} - {{ campaignId }} - {{ type }}</div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent } from "vue";
import { useRoute } from "vue-router";
import { useMainStore } from "@/store/useMain.js";

let store = useMainStore();
let route = useRoute();

let campaignId = $ref();

onMounted(async () => {
  await store.getStartCampaign(route.params.id);
  campaignId = store.startcampaign.id;

  Echo.private("startcampaignsystem." + campaignId).listen(
    "StartCampaignSystemUpdate",
    (e) => {
      if (e.flag.message != null) {
        this.$store.dispatch("updateStartCampaignSystem", e.flag.message);
      }
    }
  );

  await store.getStartCampaignJoinData();
  await store.getUsersChars(store.user_id);
  await store.getCampaignUsersRecords(campaignId);
});

onBeforeUnmount(async () => {});

let type = $computed(() => {
  return typeof campaignId;
});
</script>

<style lang="scss"></style>
