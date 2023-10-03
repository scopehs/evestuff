<template>
  <div class="q-ma-md">
    <div class="row q-gutter-sm justify-around">
      <div class="col-8">
        <StationWatchListTabel />
      </div>
      <div class="col-3">
        <StationWatchListSettingPannel />
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let can = inject("can");

const StationWatchListSettingPannel = defineAsyncComponent(() =>
  import("@/components/StationWatchList/StationWatchListSettingPannel.vue")
);

const StationWatchListTabel = defineAsyncComponent(() =>
  import("@/components/StationWatchList/StationWatchListTable.vue")
);

onMounted(async () => {
  await store.getStationWatchListNeededInfo();
  await store.getWatchListList();
  Echo.private("stationwatchlistsetuppage").listen(
    "StationWatchListSettingPageUpdate",
    (e) => {
      if (e.flag.flag == 1) {
        store.updateStationWatchListNeededInfo(e.flag.message);
      }

      if (e.flag.flag == 2) {
        store.updateStationDropDown(e.flag.message);
      }

      if (e.flag.flag == 3) {
        store.updateWatchListList(e.flag.message);
      }

      if (e.flag.flag == 4) {
        store.deleteWatchListList(e.flag.id);
      }
    }
  );
});

onBeforeUnmount(() => {
  Echo.leave("stationwatchlistsetuppage");
});
</script>

<style lang="scss"></style>
