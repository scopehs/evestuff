<template>
  <div class="q-ma-md">
    <div class="row q-gutter-lg">
      <div class="col">
        <NewMultiCampaigns />
      </div>
      <!-- <div class="col">
        <StartCampaign />
      </div> -->
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";

let store = useMainStore();
const NewMultiCampaigns = defineAsyncComponent(() =>
  import("../components/newcampaign/NewMultiCampaigns.vue")
);
const StartCampaign = defineAsyncComponent(() =>
  import("../components/startcampaign/StartCampaign.vue")
);

onMounted(async () => {
  Echo.private("customoperationpage").listen("CustomOperationPageUpdate", (e) => {
    if (e.flag.flag == 1) {
      store.addoperationlist(e.flag.message);
    }

    if (e.flag.flag == 2) {
      store.updateoperationlist(e.flag.message);
    }

    if (e.flag.flag == 3) {
      store.deleteoperationfromlist(e.flag.message);
    }
  });
  store.getConstellationList();
  document.title = "EveStuff - MultiCampaigns";
});

onBeforeUnmount(async () => {
  Echo.leave("customoperationpage");
});
</script>

<style lang="scss"></style>
