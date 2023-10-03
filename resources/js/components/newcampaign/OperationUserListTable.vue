<template>
  <div>
    <q-table
      class="myHacKUserListTable myRound bg-webBack"
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
            <span class="text-h4">Table of all Users on this Campaign Page</span>
          </div>
        </div>
      </template>

      <template v-slot:body-cell-chars="props">
        <q-td :props="props">
          <div class="row justify-center">
            <q-tabs :style="tableW" outside-arrows class="text-teal">
              <q-chip
                v-for="(char, index) in props.row.op_users"
                :key="index"
                :label="chipLabel(char)"
                color="webChip"
              />
            </q-tabs>
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

const store = useMainStore();

let windowWidth = $ref(window.innerWidth);

let handleResize = () => {
  windowWidth = window.innerWidth;
};

let filteredItems = $computed(() => {
  return store.operationUserList;
});

onMounted(async () => {
  window.addEventListener("resize", handleResize);
});

onUnmounted(() => {
  window.removeEventListener("resize", handleResize);
});

let chipLabel = (char) => {
  var text = null;
  text = char.name + " - " + char.userrole.role;
  if (char.role_id == 1) {
    text = text + " - " + char.ship + " - " + char.entosis;
  }
  return text;
};

let pagination = $ref({
  sortBy: "name",
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
    style: "width: 8%",
    classes: "text-no-wrap",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "chars",
    required: false,
    align: "center",
    label: "Chars",
    field: (row) => row.id,
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

let h = $computed(() => {
  let mins = 300;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myHacKUserListTable
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
