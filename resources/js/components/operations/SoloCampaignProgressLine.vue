<template>
  <div class="row full-width justify-around items-center">
    <div class="col-1">
      <q-icon :name="defenderIcon" :class="defenderIconColor" />
    </div>
    <div class="col-10">
      <q-linear-progress
        :value="props.defenderScore"
        rounded
        color="primary"
        track-color="red"
        class="q-mt-sm"
        size="25px"
      >
        <div class="absolute-full flex flex-center">
          <q-badge color="transparent" text-color="white" :label="text" /></div
      ></q-linear-progress>
    </div>
    <div class="col-1">
      <q-icon :name="attackerIcon" :class="attackerIconColor" />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  attackScore: Number,
  defenderScore: Number,
  attackScoreOld: Number,
  defenderScoreOld: Number,
});

let text = $computed(() => {
  let a = props.attackScore * 100;
  let d = props.defenderScore * 100;

  let text = d + "/" + a;
  return text;
});

let attackerIconColor = $computed(() => {
  if (props.attackScoreOld) {
    if (props.attackScoreOld == props.attackScore) {
      return "text-gray";
    } else if (props.attackScore > props.attackScoreOld) {
      return "text-green";
    } else {
      return "text-red";
    }
  }
  return "text-gray";
});

let attackerIcon = $computed(() => {
  if (props.attackScoreOld) {
    if (props.attackScoreOld == props.attackScore) {
      return "fa-solid fa-circle-minus";
    } else if (props.attackScore > props.attackScoreOld) {
      return "fa-solid fa-circle-up";
    } else {
      return "fa-solid fa-circle-up fa-rotate-180";
    }
  }
  return "fa-solid fa-circle-minus";
});

let defenderIconColor = $computed(() => {
  if (props.defenderScoreOld) {
    if (props.defenderScoreOld == props.defenderScore) {
      return "text-gray";
    } else if (props.defenderScore > props.defenderScoreOld) {
      return "text-green";
    } else {
      return "text-red";
    }
  }
  return "text-gray";
});

let defenderIcon = $computed(() => {
  if (props.defenderScoreOld) {
    if (props.defenderScoreOld == props.defenderScore) {
      return "fa-solid fa-circle-minus";
    } else if (props.defenderScore > props.defenderScoreOld) {
      return "fa-solid fa-circle-up";
    } else {
      return "fa-solid fa-circle-up fa-rotate-180";
    }
  }
  return "fa-solid fa-circle-minus";
});
</script>

<style lang="scss"></style>
