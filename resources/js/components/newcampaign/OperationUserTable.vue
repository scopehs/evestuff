<template>
  <div>
    <q-table
      class="myHackCharTable myRound bg-webBack"
      title="Table Title"
      :rows="filteredItems"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      :columns="columns"
      row-key="id"
      dense
      ref="tableRef"
      :filter="search"
      :pagination="pagination"
      hide-bottom
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Table of all Characters involved in this Campaign</span>
          </div>
        </div>
        <div class="row full-width q-pt-md justify-between">
          <div class="col-auto">
            <div class="row q-gutter-sm q-pl-md">
              <q-input
                rounded
                standout
                dense
                debounce="300"
                v-model="search"
                clearable
                placeholder="Search"
              >
                <template v-slot:append>
                  <q-icon name="fa-solid fa-magnifying-glass" />
                </template>
              </q-input>
            </div>
          </div>
          <div class="col-auto">
            <div class="row q-pr-md q-gutter-sm">
              <q-btn-toggle
                v-model="filterPickedRole"
                rounded
                clearable
                @clear="filterPickedRole = 0"
                unelevated
                color="webDark"
                text-color="gray"
                toggle-color="primary"
                toggle-text-color="gray"
                :options="[
                  { label: 'Commands', value: 4 },
                  { label: 'FCs', value: 3 },
                  { label: 'Hackers', value: 1 },
                  { label: 'Scouts', value: 2 },
                  { label: 'Support', value: 5 },
                ]"
              />
            </div>
          </div>
        </div>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";

const store = useMainStore();

let pagination = $ref({
  sortBy: "name",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let filterPickedRole = $ref(0);
let search = $ref("");

let filterStart = $computed(() => {
  if (filterPickedRole == 0) {
    return store.opUsers;
  } else {
    return store.opUsers.filter((item) => {
      return item.userrole.id == filterPickedRole;
    });
  }
});

let filteredItems = $computed(() => {
  return filterStart;
});

let columns = $ref([
  {
    name: "name",
    align: "left",
    required: false,
    label: "Name",
    classes: "text-no-wrap",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "main",
    required: false,
    align: "left",
    label: "Main",
    classes: "text-no-wrap",
    field: (row) => row.user.name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "role",
    required: true,
    align: "left",
    label: "Role",
    classes: "text-no-wrap",
    field: (row) => row.userrole.role,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "ship",
    align: "left",
    classes: "text-no-wrap",
    label: "Ship",
    field: (row) => (row.ship ? row.ship : ""),
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "entosis",
    align: "left",
    required: true,
    classes: "text-no-wrap",
    label: "Entosis",
    field: (row) => (row.entosis ? row.entosis : ""),
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "system",
    align: "left",
    required: true,
    label: "System",
    classes: "text-no-wrap",
    field: (row) => (row.system ? row.system.system_name : ""),
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "node",
    label: "Node",
    classes: "text-no-wrap",
    field: (row) => (row.user_node ? row.user_node.node.name : ""),
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "status",
    label: "Status",
    classes: "text-no-wrap",
    style: "width: 25%",
    sortable: false,
    field: (row) => row.userstatus.name,
    format: (val) => `${val}`,
  },
]);

let h = $computed(() => {
  let mins = 300;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myHackCharTable
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
