<template>
  <div>
    <transition mode="out-in" enter-active-class="animate__animated animate__flash">
      <q-btn
        :key="`${props.node.id}-1-addpilot`"
        color="positive"
        outline
        class="myOutLineButton"
        label="Add"
        rounded
        v-if="checkShowAdd"
        ><q-menu>
          <q-list style="min-width: 100px">
            <q-item
              clickable
              v-close-popup
              v-for="(list, index) in charsFree"
              :key="index"
              @click="addOpUser(list.id)"
            >
              <q-item-section>{{ list.name }}</q-item-section>
            </q-item>
          </q-list>
        </q-menu></q-btn
      >
      <span :key="`${node.id}-2-addpilot`" v-else>{{ activePilotName }}</span>
    </transition>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";

let store = useMainStore();
const props = defineProps({
  node: Object,
  operationID: Number,
});

let nodeFree = $computed(() => {
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
  if (
    nodeFree == 0 &&
    freeCharCount != 0 &&
    props.node.node_status.id != 4 &&
    props.node.node_status.id != 5 &&
    props.node.node_status.id != 7 &&
    props.node.node_status.id != 8
  ) {
    return true;
  } else {
    return false;
  }
});

let activePilot = $computed(() => {
  if (nodeFree > 0) {
    return props.node.prime_node_user[0];
  } else {
    return null;
  }
});

let activePilotName = $computed(() => {
  if (activePilot) {
    return activePilot.op_user.name;
  } else {
    return "";
  }
});

let addOpUser = async (op_user_id) => {
  var data = {
    node_id: props.node.id,
    op_user_id: op_user_id,
    system_id: props.node.system_id,
    opID: props.operationID,
  };
  await axios({
    method: "POST",
    url: "/api/addusertonode",
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss"></style>
