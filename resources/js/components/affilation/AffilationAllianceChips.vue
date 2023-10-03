<template>
  <div>
    <q-tabs style="25px; max-width: 70vw; width: 70vw" outside-arrows class="text-teal">
      <q-chip
        removable
        dense
        v-for="(alliance, index) in item.alliances"
        :key="index"
        color="webChip"
        @remove="remove(alliance.id)"
        ><q-avatar> <img :src="alliance.url" /></q-avatar> {{ alliance.ticker }}</q-chip
      >
    </q-tabs>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import axios from "axios";

let store = useMainStore();
const props = defineProps({
  item: [Array, Object],
});

let remove = async (id) => {
  await axios({
    method: "delete",
    withCredentials: true, //you can set what request you want to be
    url: "/api/affiliation/alliance/" + id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  // Find the index of the entity in the array
  const entityIndex = store.affiliations.findIndex(
    (entity) => entity.id === props.item.id
  );

  // If the entity is found, remove the alliance with the specified ID
  if (entityIndex !== -1) {
    store.affiliations[entityIndex].alliances = store.affiliations[
      entityIndex
    ].alliances.filter((alliance) => alliance.id !== id);
  }
};
</script>

<style lang="scss"></style>
