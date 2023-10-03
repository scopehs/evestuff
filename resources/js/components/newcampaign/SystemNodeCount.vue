<template>
  <div>
    <div class="row">
      <div class="col">
        <span> Nodes -</span>
        <q-circular-progress
          show-value
          :max="totalNodes"
          :min="0"
          rounded
          :value="blueNodes"
          size="45px"
          :thickness="0.1"
          color="positive"
          track-color="green-3"
          class=""
          >{{ blueNodes }}/{{ totalNodes }}</q-circular-progress
        >
        <q-circular-progress
          show-value
          :max="totalNodes"
          :min="0"
          rounded
          :value="redNodes"
          size="45px"
          :thickness="0.1"
          color="negative"
          track-color="red-3"
          class=""
          >{{ redNodes }}/{{ totalNodes }}</q-circular-progress
        >
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});
let totalNodes = $computed(() => {
  var count = null;
  if (props.item) {
    count = props.item.length;
  } else {
    count = 0;
  }
  return count;
});

let blueNodes = $computed(() => {
  var count = null;
  if (props.item) {
    count = props.item.filter(
      (c) =>
        c.node_status.id == 2 ||
        c.node_status.id == 3 ||
        c.node_status.id == 4 ||
        c.node_status.id == 8
    ).length;
  } else {
    count = 0;
  }
  return count;
});

let redNodes = $computed(() => {
  var count = null;
  if (props.item) {
    count = props.item.filter((n) => n.node_status.id == 5 || n.node_status.id == 7)
      .length;
  } else {
    count = 0;
  }
  return count;
});
</script>

<style lang="scss"></style>
