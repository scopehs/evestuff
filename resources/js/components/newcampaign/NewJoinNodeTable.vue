<template>
  <div>
    <q-table
      v-if="props.node.none_prime_node_user.length > 0"
      class="myNewCampaignSystemTable myRound bg-webBack"
      :rows="props.node.none_prime_node_user"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      dark
      dense
      ref="tableRef"
      hide-bottom
      rounded
      :pagination="pagination"
    >
      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="pilot" :props="props"> {{ props.row.op_user.name }}</q-td>
          <q-td key="main" :props="props"> {{ props.row.op_user.user.name }}</q-td>
          <q-td key="ship" :props="props">
            <span v-if="props.row.op_user.name != null">
              {{ props.row.op_user.ship }} - T{{ props.row.op_user.entosis }}
            </span></q-td
          >
          <q-td key="status" :props="props">
            <NewSystemTableStatusButton
              :node="props.row"
              :operationID="operationID"
              :extra="2"
          /></q-td>
          <q-td key="age" :props="props">
            <NewSystemTableTimer
              :node="props.row"
              :operationID="operationID"
              :extra="2"
              :tidiProp="node.system.tidi"
              :systemIDProp="node.system_id"
            />
          </q-td>
          <q-td key="actions" :props="props">
            <q-btn
              flat
              rounded
              size="xs"
              color="warning"
              icon="fa-solid fa-trash-can"
              @click="deleteNode(props.row)"
            />
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";

const NewSystemTableStatusButton = defineAsyncComponent(() =>
  import("./NewSystemTableStatusButton.vue")
);

const NewSystemTableTimer = defineAsyncComponent(() =>
  import("./NewSystemTableTimer.vue")
);

const props = defineProps({
  node: Object,
  operationID: Number,
});

let pagination = $ref({
  sortBy: "name",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let deleteNode = async (item) => {
  await axios({
    method: "delete", //you can set what request you want to be
    url: "/api/newdeleteextanode/" + item.id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let columns = $ref([
  {
    name: "pilot",
    align: "center",
    required: false,
    label: "Pilot",
    classes: "text-no-wrap",
    field: (row) => row.op_user.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "main",
    align: "center",
    required: false,
    label: "Main",
    classes: "text-no-wrap",
    field: (row) => row.op_user.user.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "ship",
    align: "center",
    required: false,
    label: "Ship",
    classes: "text-no-wrap",
    field: (row) => row.op_user.ship,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "status",
    align: "center",
    required: false,
    label: "Status",
    classes: "text-no-wrap",
    field: (row) => row.node_status.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "age",
    align: "center",
    required: false,
    label: "Age/Hack",
    classes: "text-no-wrap",
    field: (row) => row.created_at,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "actions",
    align: "center",
    required: false,
    label: "",
    classes: "text-no-wrap",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
  },
]);
</script>

<style lang="sass">
.myNewCampaignSystemExtraTable
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
