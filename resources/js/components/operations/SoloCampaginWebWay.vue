<template>
  <div>
    <q-btn round size="sm" :href="url" target="_blank" color="positive" dark>
      {{ props.jumps }}
    </q-btn>
  </div>
</template>

<script setup>
const props = defineProps({
  jumps: Number,
  web: String,
});

let url = $computed(() => {
  var base = "https://webway.apps.gnf.lt/routing?share=";
  var url = base + props.web;
  return url;
});
</script>
