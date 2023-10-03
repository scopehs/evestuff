<template>
  <div>
    <q-table
      title="Connections"
      class="myStationWatchListTable myRound bg-webBack"
      :rows="store.watchListList"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      card-container-class="justify-evenly overflow-auto "
      grid
      dark
      dense
      ref="tableRef"
      rounded
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex justify-center">
            <div class="row full-width justify-between">
              <div class="col-1"></div>
              <div class="col-8 text-h4 flex justify-center">Watch Lists</div>
              <div class="col-2 flex justify-end"><StationWatchListAddList /></div>
            </div>
          </div>
        </div>
      </template>

      <template v-slot:item="props">
        <transition
          appear
          name="fade"
          enter-active-class="animate__animated animate__zoomIn animate__slow"
          leave-active-class="animate__animated animate__zoomOut animate__slow"
        >
          <StationWatchListTableCard
            class="q-pa-xs"
            :key="`${props.row.id}-card`"
            :list="props.row"
          />
        </transition>
      </template>

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="name" :props="props">
            {{ props.row.name }}
          </q-td>
          <q-td key="description" :props="props"> {{ props.row.description }} </q-td>
          <q-td key="regions" :props="props">
            <q-chip v-for="(region, index) in props.row.regions" :key="index">
              {{ region.region_name }}
            </q-chip>
          </q-td>
          <q-td key="constellations" :props="props">
            <q-chip
              v-for="(constellation, index) in props.row.constellations"
              :key="index"
            >
              {{ constellation.constellation_name }}
            </q-chip>
          </q-td>
          <q-td key="systems" :props="props">
            <q-chip v-for="(system, index) in props.row.systems" :key="index">
              {{ system.system_name }}
            </q-chip>
          </q-td>
          <q-td key="stations" :props="props">
            <q-chip v-for="(station, index) in props.row.stations" :key="index">
              {{ station.name }}
            </q-chip>
          </q-td>
          <q-td key="roles" :props="props">
            <q-chip v-for="(role, index) in props.row.roles" :key="index">
              {{ role.name }}
            </q-chip>
          </q-td>
          <q-td key="users" :props="props">
            <q-chip v-for="(user, index) in props.row.users" :key="index">
              {{ user.name }}
            </q-chip>
          </q-td>
          <q-td key="actions" :props="props"> </q-td>
        </q-tr>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";

const StationWatchListAddList = defineAsyncComponent(() =>
  import("@/components/StationWatchList/StationWatchListAddList.vue")
);

const StationWatchListTableCard = defineAsyncComponent(() =>
  import("@/components/StationWatchList/StationWatchListTableCard.vue")
);

let store = useMainStore();

let pagination = $ref({
  sortBy: "id",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let columns = $ref([
  {
    name: "name",
    align: "left",
    required: false,
    label: "Name",

    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "description",
    required: true,
    align: "left",
    label: "Description",

    field: (row) => row.description,
    format: (val) => `${val}`,
    sortable: false,
    filter: false,
  },

  {
    name: "regions",
    label: "Regions",

    field: (row) => row.regions,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },

  {
    name: "constellations",
    align: "left",
    required: true,
    label: "Constellations",

    field: (row) => row.constellations,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },

  {
    name: "systems",
    align: "left",

    label: "Systems",
    field: (row) => row.systems,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "stations",
    required: true,
    align: "left",
    label: "Stations",

    field: (row) => row.stations,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },

  {
    name: "roles",
    align: "left",
    required: true,

    label: "Roles",
    field: (row) => row.roles,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },

  {
    name: "users",
    label: "Users",

    sortable: false,
    filter: false,
    field: (row) => row.users,
    format: (val) => `${val}`,
  },
  {
    name: "active",
    label: "Active",

    align: "center",
    style: "width: 25%",
    sortable: false,
    field: (row) => row.active,
    format: (val) => `${val}`,
  },

  {
    name: "actions",
    label: "",
    align: "right",

    sortable: false,
    field: (row) => row.id,
    format: (val) => `${val}`,
  },
]);

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myStationWatchListTable
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
