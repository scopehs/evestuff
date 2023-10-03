<template>
  <div>
    <q-btn
      color="positive"
      icon="fa-solid fa-plus"
      flat
      padding="none"
      size="xs"
      round
      v-if="checkShowAdd"
      ><q-menu>
        <q-list style="min-width: 100px">
          <q-item
            clickable
            v-close-popup
            v-for="(list, index) in charsFree"
            :key="index"
            @click="clickCharAddNode(list.id)"
          >
            <q-item-section>{{ list.name }}</q-item-section>
          </q-item>
        </q-list>
      </q-menu></q-btn
    >
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";

let store = useMainStore();

const props = defineProps({
  node: Object,
  operationID: Number,
});

let nonePrimeNodeUserCount = $computed(() => {
  return props.node.none_prime_node_user.length;
});

let primeNodeUserCount = $computed(() => {
  return props.node.prime_node_user.length;
});

let freeCharCount = $computed(() => {
  var data = store.getOwnHackingCharOnOp(props.operationID);
  if (data) {
    return data.length;
  } else {
    return 0;
  }
});

let charsFree = $computed(() => {
  var data = store.getOwnHackingCharOnOp(props.operationID);
  if (data) {
    return data;
  } else {
    return [];
  }
});

let checkShowAdd = $computed(() => {
  if (freeCharCount != 0) {
    if (
      nonePrimeNodeUserCount > 0 ||
      primeNodeUserCount > 0 ||
      props.node.node_status.id == 8
    ) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
});

let clickCharAddNode = (op_user_id) => {
  var request = {
    node_id: props.node.id,
    op_user_id: op_user_id,
    system_id: props.node.system_id,
    opID: props.operationID,
  };
  axios({
    method: "POST",
    url: "/api/addusertonode",
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
