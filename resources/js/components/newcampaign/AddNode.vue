<template>
  <div>
    <q-btn
      v-if="showButton"
      color="positive"
      padding="none"
      flat
      icon="fa-solid fa-plus"
      label="Node"
      ><q-menu persistent v-model="addShow" @before-hide="beforeHide">
        <q-card class="myRoundTop" style="width: 250px; max-width: 80vw">
          <q-card-section>
            <div class="column q-gutter-md">
              <q-select
                v-if="dropDownFocus"
                v-model="nodeCampaignID"
                label="Campaign"
                option-label="name"
                option-value="id"
                :options="props.activeCampaigns"
                :autofocus="dropDownFocus"
                outlined
                rounded
              />
              <q-input
                label="Node Name"
                :autofocus="textFocus"
                mask="AA##"
                v-model="nodeText"
                type="text"
                @keyup.enter="addNode()"
                outlined
                rounded
              />
            </div>
          </q-card-section>
          <q-card-actions align="center">
            <q-btn
              rounded
              color="positive"
              label="Add"
              :disable="nodeText.length != 4"
              @click="addNode()"
            />
            <q-btn rounded color="negative" label="Close" v-close-popup />
          </q-card-actions>
        </q-card> </q-menu
    ></q-btn>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";

let can = inject("can");

let store = useMainStore();

const props = defineProps({
  item: Object,
  operationID: Number,
  activeCampaigns: Array,
});
let nodeCampaignID = $ref(null);
let nodeText = $ref("");
let addShow = $ref(false);

let beforeHide = () => {
  nodeText = "";
  nodeCampaignID = null;
  addShow = false;
};

let addNode = async () => {
  if (activeCount == 1) {
    var campaign_id = props.activeCampaigns[0].id;
  } else {
    var campaign_id = nodeCampaignID.id;
  }

  let node = nodeText.toUpperCase();
  var data = {
    system_id: props.item.id,
    campaign_id: campaign_id,
    name: node,
  };

  nodeText = "";
  addShow = false;

  await axios({
    method: "POST",
    url: "/api/addnode",
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let showButton = $computed(() => {
  if (activeCount > 0) {
    if (store.newOperationInfo.read_only == 1) {
      if (can("view_operation_read_only")) {
        return true;
      } else {
        return false;
      }
    } else {
      return true;
    }
  } else {
    return false;
  }
});

let activeCount = $computed(() => {
  return props.activeCampaigns.length;
});

let dropDownFocus = $computed(() => {
  if (activeCount == 1) {
    return false;
  } else {
    return true;
  }
});

let textFocus = $computed(() => {
  if (activeCount == 1) {
    return true;
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>
