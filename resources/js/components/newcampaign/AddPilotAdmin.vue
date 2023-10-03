<template>
  <div>
    <q-btn
      color="primary"
      icon="fa-solid fa-plus"
      flat
      rounded
      padding="none"
      size="xs"
      @click="confirm = !confirm"
    />
    <q-dialog v-model="confirm" persistent>
      <q-card class="myRoundTop" style="width: 1200px; max-width: 80vw">
        <q-table
          class="myAdminAddHackUserTable myRoundTop bg-webBack"
          :rows="filteredItems"
          hide-bottom
          :columns="columns"
          table-class=" text-webway"
          table-header-class=" text-weight-bolder"
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
                <span class="text-h4">All Free Hackers</span>
              </div>
            </div>
          </template>

          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <q-btn
                color="positive"
                icon="fa-solid fa-plus"
                label="Add"
                rounded
                outline
                class="myOutLineButton"
                @click="add(props.row)"
              />
            </q-td>
          </template>
        </q-table>

        <q-card-actions align="right">
          <q-btn flat label="Close" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  operationID: Number,
  node: [Array, Object],
});
let pagination = $ref({
  sortBy: "name",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let add = async (item) => {
  let data = {
    opUserID: item.id,
    id: props.node.id,
  };
  await axios({
    method: "POST",
    url: "/api/addcharadmin",
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let filteredItems = $computed(() => {
  return store.opUsers.filter((o) => o.role_id == 1 && o.userstatus.id != 4);
});

let columns = $ref([
  {
    name: "name",
    align: "left",
    classes: "ellipsis ",
    required: false,
    label: "Name",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "role",
    required: false,
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
    required: true,
    align: "left",
    label: "Ship",
    classes: "text-no-wrap",
    field: (row) => row.ship,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "entosis",
    align: "left",
    classes: "text-no-wrap",
    label: "Entosis",
    field: (row) => row.entosis,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },

  {
    name: "actions",
    label: "",
    align: "right",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.id,
    format: (val) => `${val}`,
  },
]);
let confirm = $ref(false);
</script>

<style lang="sass">
.myAdminAddHackUserTable
  /* height or max-height is important */


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
