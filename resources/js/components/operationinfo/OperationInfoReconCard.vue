<template>
  <div>
    <q-card class="myRoundTop myOperationInfoReconCard">
      <q-card-section class="bg-positive text-center">
        <div class="row full-width justify-start">
          <div class="col-auto"><h5 class="no-margin">Recon</h5></div>
          <div class="col-auto">
            <AddOperationReconButton />
          </div>
        </div>
      </q-card-section>
      <q-card-section class="overflow-auto myReconNames">
        <transition-group
          enter-active-class="animate__animated animate__flash"
          v-if="show"
        >
          <OperationInfoReconCardNames
            v-if="store.userList"
            :recon="recon"
            no-gutters
            v-for="recon in opInfo.recons"
            :key="`${recon.id}-${recon.operation_info_recon_status_id}-${recon.dead}-${recon.online}-card`"
          />
        </transition-group>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();

onMounted(async () => {
  setTimeout(() => {
    show = true;
  }, 500);
});

let show = $ref(false);

const OperationInfoReconCardNames = defineAsyncComponent(() =>
  import("./OperationInfoReconCardNames.vue")
);
const AddOperationReconButton = defineAsyncComponent(() =>
  import("./AddOperationReconButton.vue")
);

let opInfo = $computed(() => {
  return store.operationInfoPage;
});

let someMethod = () => {};
</script>

<style lang="scss"></style>
