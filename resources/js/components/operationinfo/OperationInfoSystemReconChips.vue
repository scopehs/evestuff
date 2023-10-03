<template>
  <div>
    <q-tabs style="25px; max-width: 600px; width: 600px" outside-arrows class="text-teal">
      <q-chip
        removable
        dense
        v-for="(recon, index) in systemInfo.recons"
        :key="index"
        color="webChip"
        @remove="remove(recon.id)"
        ><q-avatar> <img :src="recon.url" /></q-avatar> {{ recon.name }}</q-chip
      >
    </q-tabs>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import axios from "axios";

let store = useMainStore();
const props = defineProps({
  item: [Array, Object],
});

let systemInfo = $computed(() => {
  return store.getOperationSystemInfo(props.item.id);
});

let remove = async (id) => {
  var request = {
    reconID: id,
    opID: store.operationInfoPage.id,
  };

  await axios({
    method: "delete", //you can set what request you want to be
    url: "/api/operationinfosystemreconremove/" + props.item.id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss"></style>
