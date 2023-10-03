<template>
  <div class="">
    <div class="row justify-center">
      <div class="col">
        <q-table
          class="myTableWatchedSystem myRound bg-webBack"
          :rows="store.operationInfoPage.watch_systems"
          :columns="columns"
          table-class=" text-webway"
          table-header-class=" text-weight-bolder"
          row-key="id"
          dark
          dense
          hide-pagination=""
          hide
          ref="tableRef"
          rounded
          :pagination="pagination"
        >
          <template v-slot:top="props">
            <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
              <span class="text-h4">Watched Systems</span>
            </div>
          </template>
          <template v-slot:body-cell-dscan="props">
            <q-td :props="props"> <StagingDscan :item="props.row" /> </q-td>
          </template>
          <template v-slot:body-cell-age="props">
            <q-td :props="props">
              <VueCountUp
                v-if="props.row.dscan && props.row.dscan.updated_at"
                class="q-pl-sm"
                :emit-events="false"
                :time="countUpTimeMil(props.row.dscan.updated_at)"
                :interval="1000"
                v-slot="{ hours, minutes, seconds }"
              >
                <span class="text-red" v-if="hours >= 1"
                  >{{ hours }}:{{ minutes }}:{{ seconds }}</span
                >
                <span class="text-red" v-else-if="minutes >= 20"
                  >{{ minutes }}:{{ seconds }}</span
                >
                <span v-else class="text-primary">{{ minutes }}:{{ seconds }}</span>
              </VueCountUp>
            </q-td>
          </template>
        </q-table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

const StagingDscan = defineAsyncComponent(() =>
  import("@/components/staging/StagingDscan.vue")
);
const VueCountUp = defineAsyncComponent(() => import("@/components/countup/index"));

let pagination = $ref({
  sortBy: "progress",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let store = useMainStore();
onMounted(async () => {});

onBeforeUnmount(() => {});

let countUpTimeMil = (time) => {
  if (!time) {
    return 0;
  }
  const timestamp = Date.parse(time) ;
  return timestamp;
};

let columns = $ref([
  {
    name: "system",
    required: true,
    align: "left",
    style: "font-size: 0.8rem!important;",
    label: "System",
    classes: "text-no-wrap",
    field: (row) => row.system_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "dscan",
    required: true,
    align: "center",
    label: "Dscan",
    style: "font-size: 0.8rem!important;",
    classes: "text-no-wrap",
    field: (row) => row.dscan,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },

  {
    name: "age",
    required: true,
    align: "center",
    label: "Dscan",
    style: "font-size: 0.8rem!important;",
    classes: "text-no-wrap",
    field: (row) => (row.dscan.updated_at ? row.dscan.updated_at : null),
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
]);

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>
