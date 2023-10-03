<template>
  <div>
    <span v-for="(message, index) in getDif" :key="index">
      {{ message }}
    </span>
  </div>
</template>

<script setup>
const props = defineProps({
  row: Object,
});

let getDif = $computed(() => {
  const changes = [];
  const oldProps = props.row.properties.old;
  const newProps = props.row.properties.attributes;
  for (let key in oldProps) {
    if (key == "updated_at" || key == "log_helper" || key == "station_status_id") {
      continue;
    }
    if (oldProps[key] !== newProps[key]) {
      let message = `${key} changed from ${oldProps[key]} to ${newProps[key]}`;
      if (key === "status.name") {
        message = "status" + message.slice(11);
      }
      changes.push(message);
    }
  }
  return changes;
});
</script>

<style lang="scss"></style>
