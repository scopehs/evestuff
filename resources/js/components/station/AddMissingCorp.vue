<template>
  <div>
    <q-btn color="secondary" size="md" rounded label="Add Missing">
      <q-menu>
        <q-card class="my-card">
          <q-card-section>
            <div class="text-h6 text-center">Add Missing Corp</div>
            <div class="text-subtitle2">ONLY USE IF CORP TICKER IS NOT FOUND</div>
          </q-card-section>
          <q-card-section>
            <q-input v-model="text" type="text" label="Corp Ticker" />
          </q-card-section>
          <q-card-actions align="center">
            <q-btn flat color="positive" label="Submit" @click="submit()" v-close-popup />
            <q-btn flat color="negative" label="Close" @click="close()" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-menu>
    </q-btn>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";

const emit = defineEmits(["missingCorpDone"]);
let store = useMainStore();

let text = $ref();

let submit = async () => {
  await store.updateTickList(text);
  emit("missingCorpDone");
  text = null;
};

let close = () => {
  text = null;
};
</script>

<style lang="scss"></style>
