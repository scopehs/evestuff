<template>
  <q-card class="my-card">
    <q-card-section>
      <q-btn color="primary" label="Recon Pull Test" @click="startReconRgionPull()" />
    </q-card-section>
    <q-card-section>
      <q-btn color="primary" label="ADD" href="/esi/add"
    /></q-card-section>
    <q-card-section
      ><q-btn color="primary" label="Prequal" @click="prequal()"
    /></q-card-section>
    <q-card-section
      ><q-btn color="primary" label="Horizon" @click="horizon()"
    /></q-card-section>
    <q-card-section>
      <q-btn color="primary" label="Logs" @click="logs()"
    /></q-card-section>
    <q-card-section>
      <q-input v-model="rcdata" type="text" label="RCdata" />
      <q-btn color="primary" label="Submit" @click="sumbitrcdata()" />
    </q-card-section>
  </q-card>
</template>

<script setup>
import { useRouter } from "vue-router";
let router = useRouter();
let startReconRgionPull = async () => {
  await axios({
    method: "get", //you can set what request you want to be
    url: "api/reconpullregion",
    withCredentials: true,
    data: this.rcdata,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let rcdata = $ref();

let prequal = () => {
  let route = router.resolve({
    path: "/hithere",
  });
  window.open(route.href);
};

let sumbitrcdata = async () => {
  await axios({
    method: "post", //you can set what request you want to be
    url: "api/rcInput",
    withCredentials: true,
    data: this.rcdata,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let horizon = () => {
  let route = router.resolve({
    path: "/hitherealso",
  });
  window.open(route.href);
};

let logs = () => {
  let route = router.resolve({
    path: "/hithereagain",
  });
  window.open(route.href);
};
</script>

<style lang="scss"></style>
