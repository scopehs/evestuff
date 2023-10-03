<template>
  <div>
    <q-btn color="positive" padding="none" icon="fa-solid fa-plus" flat size="xs">
      <q-menu @before-hide="close()" :offset="[50, 10]">
        <q-card class="myRoundTop" style="width: 250px">
          <q-card-section>
            <q-select
              v-model="pickedRecon"
              :options="dropDown"
              option-label="name"
              option-value="id"
              rounded
              @filter="filterFnReconFinish"
              @filter-abort="abortFilterFn"
              outlined
              use-input
              label="Add cyno to system"
            />
          </q-card-section>
          <q-card-actions align="between">
            <q-btn
              :disable="!pickedRecon"
              rounded
              color="positive"
              class="q-px-md"
              label="add"
              v-close-popup
              @click="done()"
            />
            <q-btn rounded color="negative" label="close" v-close-popup />
          </q-card-actions>
        </q-card> </q-menu
    ></q-btn>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import axios from "axios";
let store = useMainStore();

const props = defineProps({
  item: [Array, Object],
});

let pickedRecon = $ref();
let reconText = $ref();

let reconList = $computed(() => {
  if (reconText) {
    return dropDown.filter((r) => r.name.toLowerCase().includes(reconText.toLowerCase()));
  }
  return dropDown;
});

let filterFnReconFinish = (val, update) => {
  update(() => {
    reconText = val.toLowerCase();
    if (reconList.length > 0 && val) {
      pickedRecon = reconList[0];
    }
  });
};

let dropDown = $computed(() => {
  let data = store.operationInfoRecon.filter(
    (r) =>
      r.operation_info_id == store.operationInfoPage.id && r.system_id != props.item.id
  );
  return data;
});

let close = () => {
  pickedRecon = null;
};

let done = async () => {
  var request = {
    recon: pickedRecon.id,
    opID: store.operationInfoPage.id,
  };
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/operationinfosystemreconupdate/" + props.item.id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let abortFilterFn = () => {};
</script>

<style lang="scss"></style>
