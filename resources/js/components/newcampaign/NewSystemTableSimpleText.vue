<template>
  <div>{{ activePilotText }}</div>
</template>

<script setup>
const props = defineProps({
  node: Object,
  type: Number,
});

let nodeFree = $computed(() => {
  return props.node.prime_node_user.length;
});

let activePilot = $computed(() => {
  if (nodeFree > 0) {
    return props.node.prime_node_user[0];
  } else {
    return null;
  }
});

let activePilotText = $computed(() => {
  if (activePilot) {
    switch (props.type) {
      case 1: // * return main name
        return activePilot.op_user.user.name;

      case 2: // * return {{ship_name}} + T-{{entosi}}
        var entosis = " T-" + activePilot.op_user.entosis;
        var ship = activePilot.op_user.ship;
        var text = ship + entosis;
        return text;
    }
  } else {
    return null;
  }
});
</script>

<style lang="scss"></style>
