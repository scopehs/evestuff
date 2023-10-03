<template>
  <div>
    <q-table
      title="Logs"
      class="myTableStations myRound bg-webBack"
      :rows="props.station.logs"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      no-data-label="All Hostile Stations our reffed!!!!!!"
      dark
      dense
      style="max-height: 300px"
      ref="tableRef"
      rounded
      :pagination="pagination"
      hide-bottom
    >
      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="name" :props="props">
            {{ props.row.causer.name }}
          </q-td>
          <q-td key="description" :props="props">
            {{ props.row.description }}
          </q-td>
          <q-td key="logs" :props="props">
            <StationSheetLogText :row="props.row" />
          </q-td>
          <q-td key="date" :props="props">
            {{ time(props.row.created_at) }}
          </q-td></q-tr
        >
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { defineAsyncComponent } from "vue";
const StationSheetLogText = defineAsyncComponent(() =>
  import("./StationSheetLogText.vue")
);
let store = useMainStore();
const props = defineProps({
  station: Object,
});

let pagination = $ref({
  sortBy: "progress",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let time = (time) => {
  const timestamp = time;
  const date = new Date(timestamp);
  const formattedDate = date.toISOString().slice(0, 10);
  const formattedTime = date.toISOString().slice(11, 16);
  const formattedTimestamp = `${formattedDate} ${formattedTime}`;
  return formattedTimestamp;
};

let columns = $ref([
  {
    name: "name",
    required: true,
    align: "left",
    label: "User",
    classes: "text-no-wrap",
    field: (row) => row.causer.name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "description",
    required: true,
    align: "left",
    label: "Description",
    classes: "text-no-wrap",
    field: (row) => row.description,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "logs",
    required: true,
    align: "left",
    label: "Logs",
    classes: "text-no-wrap",
    field: (row) => row.text,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },

  {
    name: "date",
    required: true,
    align: "left",
    label: "Date",
    classes: "text-no-wrap",
    field: (row) => row.created_at,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
]);
</script>

<style lang="scss"></style>
