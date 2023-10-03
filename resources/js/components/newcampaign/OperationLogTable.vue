<template>
  <div>
    <q-table
      class="myHacKLogTable myRound bg-webBack"
      title="Table Title"
      :rows="filteredItems"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      :columns="columns"
      row-key="id"
      dense
      ref="tableRef"
      :pagination="pagination"
      hide-bottom
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Log for this Operation</span>
          </div>
        </div>
      </template>

      <template v-slot:body-cell-details="props">
        <q-td :props="props"> <NewCampaignLogText :item="props.row" /> </q-td>
      </template>

      <template v-slot:body-cell-date="props">
        <q-td :props="props"> {{ converTime(props.row.created_at) }} </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

const NewCampaignLogText = defineAsyncComponent(() => import("./NewCampaignLogText.vue"));

const store = useMainStore();

let filteredItems = $computed(() => {
  return store.loggingNewCampaign;
});

let pagination = $ref({
  sortBy: "date",
  descending: true,
  page: 1,
  rowsPerPage: 0,
});

let columns = $ref([
  {
    name: "type",
    align: "left",
    required: false,
    label: "Type",
    classes: "text-no-wrap",
    field: (row) => row.log_name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "event",
    required: false,
    align: "center",
    label: "Event",
    field: (row) => row.description,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "details",
    align: "left",
    required: false,
    label: "Details",
    classes: "text-no-wrap",
    field: (row) => row.text,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "by",
    required: false,
    align: "center",
    label: "By",
    field: (row) => row.causer.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "date",
    align: "left",
    required: false,
    label: "Date",
    style: "width: 8%",
    classes: "text-no-wrap",
    field: (row) => row.created_at,
    format: (val) => `${val}`,
    sortable: false,
  },
]);

// 25px; max-width: 1200px; width: 1200px

let tableW = $computed(() => {
  let sub = 600;
  let window = windowWidth;
  let num = window - sub;

  return "max-width: " + num + "px; width: " + num + "px";
});

let converTime = (inputDate) => {
  var dateObj = new Date(inputDate);
  var isoString = dateObj.toISOString();
  var formattedDate = isoString.slice(0, 19).replace("T", " ");
  return formattedDate;
};

let h = $computed(() => {
  let mins = 300;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myHacKLogTable
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
