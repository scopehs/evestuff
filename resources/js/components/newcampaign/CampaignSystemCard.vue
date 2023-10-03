<template>
  <div>
    <q-expansion-item
      class="shadow-1 overflow-hidden"
      style="border-radius: 30px"
      label="dance"
      v-model:model-value="showPannel"
      expand-icon-toggle
      header-class=" q-py-none bg-webBack text-webway text-center"
      @show="10"
      @hide="10"
      dark
    >
      <template v-slot:header>
        <div class="row q-gutter-none full-width items-center">
          <div class="col-1">{{ props.item.system_name }}</div>
          <q-separator vertical class="" color="webChip" />
          <div class="col-3">
            <SystemNodeCount :item="item.new_nodes" />
          </div>
          <div class="col-6 flex justify-end full-height align-center">
            <q-separator vertical class="" color="webChip" />
            <OnTheWay class="q-px-md" :operationID="operationID" :item="item" />
            <q-separator vertical class="" color="webChip" />
            <ReadyToGo class="q-px-md" :operationID="operationID" :item="item" />
            <q-separator vertical class="" color="webChip" />
          </div>
        </div>
      </template>

      <q-card>
        <q-card-section>
          <CampaignSystemCardContent
            :item="props.item"
            :operationID="props.operationID"
          />
        </q-card-section>
      </q-card>
    </q-expansion-item>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

let store = useMainStore();
const props = defineProps({
  item: Object,
  operationID: Number,
});

const SystemNodeCount = defineAsyncComponent(() => import("./SystemNodeCount.vue"));
const OnTheWay = defineAsyncComponent(() => import("./OnTheWay.vue"));
const ReadyToGo = defineAsyncComponent(() => import("./ReadyToGo.vue"));
const CampaignSystemCardContent = defineAsyncComponent(() =>
  import("./CampaignSystemCardContent.vue")
);

onMounted(() => {
  Echo.private("operationsown." + store.user_id + "-" + props.operationID).listen(
    "OperationOwnUpdate",
    (e) => {
      if (e.flag.flag == 8) {
        if (e.flag.type == 1) {
          openPannel();
        } else {
          closePannel();
        }
      }
    }
  );
});

onBeforeUnmount(() => {
  Echo.leave("operationsown." + store.user_id + "-" + props.operationID);
});

let showPannel = $ref(true);

let openPannel = () => {
  showPannel = true;
};

let closePannel = () => {
  showPannel = false;
};
</script>

<style lang="scss"></style>
