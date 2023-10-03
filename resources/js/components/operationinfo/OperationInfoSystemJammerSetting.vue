<template>
  <div>
    <q-btn color="primary" padding="none" flat icon="fa-solid fa-poo-storm">
      <q-menu @before-show="open()" class="myRoundTop">
        <q-card class="myRoundTop">
          <q-card-section class="bg-primary text-center">
            Jammer Setting for {{ props.item.system_name }}
          </q-card-section>
          <q-card-section class="text-center">
            <div class="column">
              <span> Jammer Status</span>
              <q-btn-toggle
                v-model="pickedJammerStatus"
                class="my-custom-toggle"
                no-caps
                rounded
                unelevated
                toggle-color="primary"
                color="white"
                text-color="primary"
                :options="store.operationInfoJamList"
              />
            </div>
          </q-card-section>
          <q-card-section class="text-center">
            <div class="column">
              <span> Cynos Needed</span>
              <q-slider
                v-model="pickedCynosNeeded"
                label
                label-always
                switch-label-side
                :min="0"
                :max="10"
                color="primary"
              />
            </div>
          </q-card-section>
          <q-card-actions align="between" class="q-pt-lg">
            <q-btn
              rounded
              color="positive"
              label="Submint"
              v-close-popup
              @click="done()"
            />
            <q-btn rounded color="negative" label="Close" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-menu>
    </q-btn>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let pickedJammerStatus = $ref();
let pickedCynosNeeded = $ref();
const props = defineProps({
  item: [Array, Object],
});
let open = () => {
  pickedJammerStatus = props.item.pivot.jammed_status;
  pickedCynosNeeded = props.item.pivot.cynos_needed;
};
let done = () => {
  var request = {
    jam: pickedJammerStatus,
    cynos: pickedCynosNeeded,
  };

  axios({
    method: "post", //you can set what request you want to be
    url: "/api/operationinfosystemjamupdate/" + props.item.id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss"></style>
