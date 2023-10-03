<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myMultiCampaignAddTables myRound bg-webBack"
      :rows="store.newOperationList"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      no-data-label="No Custom Campaigns"
      row-key="id"
      dark
      dense
      ref="tableRef"
      rounded
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Custom Operations</span> <AddMultiCampaign />
          </div>
        </div>
      </template>
      <template v-slot:body-cell-campaign="props">
        <q-td :props="props"> <NewSystemItemList :campaigns="props.row.campaign" /></q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td>
          <div class="row justify-end items-baseline">
            <div class="col-auto"><EditOperation :operation="props.row" /></div>
            <div class="col-auto">
              <NewCustomCampaignDeleteButton :item="props.row" />
            </div>
            <div class="col-auto">
              <q-btn
                color="primary"
                rounded
                size="sm"
                label="view"
                @click="clickCampaign(props.row)"
              />
            </div>
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";
let router = useRouter();
let store = useMainStore();
const AddMultiCampaign = defineAsyncComponent(() =>
  import("../multicampaigns/AddMultiCampaign.vue")
);
const NewSystemItemList = defineAsyncComponent(() => import("./NewSystemItemList.vue"));
const NewCustomCampaignDeleteButton = defineAsyncComponent(() =>
  import("./NewCustomCampaignDeleteButton.vue")
);
const EditOperation = defineAsyncComponent(() => import("./EditOperation.vue"));

onMounted(async () => {
  await store.getNewCampaignsList();
  await store.getCustomOperationList();
});

onBeforeUnmount(async () => {});

let clickCampaign = (item) => {
  router.push({ path: `/op/${item.link}` }); // -> /user/123
};

let operationStatus = (item) => {
  if (item.status == 1) {
    return "Active";
  }
};

let pagination = $ref({
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let columns = $ref([
  {
    name: "title",
    align: "left",
    required: false,
    label: "Name",
    classes: "text-no-wrap",
    field: (row) => row.title,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "campaign",
    required: true,
    align: "center",
    label: "System - Target",
    field: (row) => row.campaign,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "actions",
    align: "right",
    classes: "text-no-wrap",
    label: "",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
]);

let h = $computed(() => {
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myMultiCampaignAddTables
  /* height or max-height is important */
  height: v-bind(h)

  .q-table__top
    padding-top: 0 !important
    padding-left: 0 !important
    padding-right: 0 !important



  .q-table__bottom,
  thead tr:first-child th
    /* bg color is important for th; just specify one */
    background-color: #202020

  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0

  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>
