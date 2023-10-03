<template>
  <div>
    <q-btn flat :icon="text" @click="click()" />
  </div>
</template>

<script setup>
import axios from "axios";
const props = defineProps({
  row: Object,
});
let click = () => {
  if (props.row.priority == 0) {
    var num = 1;
  } else {
    var num = 0;
  }

  props.row.priority = num;
  var request = {
    priority: num,
  };
  axios({
    method: "post",
    url: "api/newcampaignpriority/" + props.row.id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let text = $computed(() => {
  if (props.row.priority == 0) {
    return "fa-regular fa-bell";
  } else {
    return "fa-solid fa-bell";
  }
});
</script>

<style lang="scss"></style>
