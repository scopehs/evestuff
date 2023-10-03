<template>
  <div>
    <q-btn
      color="positive"
      class="myOutLineButtonMid"
      label="characters"
      @click="store.newOperationMessageAddChar = !store.newOperationMessageAddChar"
      outline
      rounded
    />

    <q-dialog v-model="store.newOperationMessageAddChar" persistent>
      <q-card class="myRoundTop" style="width: 1200px; max-width: 80vw">
        <q-table
          class="myHackCharTable myRoundTop bg-webBack"
          :rows="store.ownChars"
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
                <span class="text-h4">Table of all your Saved Characters</span>
              </div>
            </div>
          </template>

          <template v-slot:header-cell-actions="props">
            <q-th :props="props">
              <div class="row">
                <div class="col">
                  <span class="myFont">
                    <AddOperationUserButton :operationID="operationID"
                  /></span>
                </div>
              </div>
            </q-th>
          </template>

          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="name" :props="props">
                <span> {{ props.row.name }}</span>
              </q-td>
              <q-td key="role" :props="props">
                <span> {{ props.row.userrole.role }} </span>
              </q-td>
              <q-td key="ship" :props="props">
                <span> {{ props.row.ship }}</span>
              </q-td>
              <q-td key="entosis" :props="props">
                <span>{{ props.row.entosis }}</span>
              </q-td>
              <q-td class="" key="addRove" :props="props">
                <transition
                  mode="out-in"
                  enter-active-class="animate__animated animate__flash"
                  leave-active-class="animate__animated animate__flash"
                >
                  <q-btn
                    :key="`${props.row.operation_id}-addButton`"
                    :color="pillColor(props.row)"
                    outline
                    rounded
                    class="myOutLineButton"
                    :icon="pillIcon(props.row)"
                    :label="pillText(props.row)"
                    @click="pillClick(props.row)"
                  />
                </transition>
              </q-td>
              <q-td key="actions" :props="props">
                <div class="row q-gutter-md justify-end">
                  <AddOperationUserButton
                    :char="props.row"
                    :type="2"
                    :operationID="operationID"
                  />
                  <q-btn
                    color="negative"
                    icon="fa-solid fa-trash-can"
                    flat
                    padding="none"
                    size="sm"
                    @click="removeChar(props.row)"
                  />
                </div>
              </q-td>
            </q-tr>
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
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";

const AddOperationUserButton = defineAsyncComponent(() =>
  import("./AddOperationUserButton.vue")
);

let store = useMainStore();
const props = defineProps({
  operationID: Number,
});

let confirm = $ref(false);

let pagination = $ref({
  sortBy: "name",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let pillColor = (item) => {
  return item.operation_id == props.operationID ? "negative" : "positive";
};

let pillText = (item) => {
  return item.operation_id == props.operationID ? "Remove" : "Add";
};

let pillIcon = (item) => {
  return item.operation_id == props.operationID
    ? "fa-solid fa-minus"
    : "fa-solid fa-plus";
};

let pillClick = async (item) => {
  if (item.operation_id == props.operationID) {
    var data = {
      operation_id: null,
      system_id: null,
      user_status_id: 1,
    };

    await axios({
      //removes char from campaign
      method: "PUT",
      url:
        "/api/newcampaignusersremove/" +
        item.id +
        "/" +
        props.operationID +
        "/" +
        store.user_id,
      withCredentials: true,
      data: data,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
  } else {
    var data = {
      operation_id: props.operationID,
      system_id: null,
      user_status_id: 1,
    };

    await axios({
      method: "PUT",
      url:
        "/api/newcampaignusersadd/" +
        item.id +
        "/" +
        props.operationID +
        "/" +
        store.user_id,
      withCredentials: true,
      data: data,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
  }
};

let removeChar = async (item) => {
  await axios({
    method: "DELETE",
    url:
      "/api/newcampaignusers/" + item.id + "/" + props.operationID + "/" + store.user_id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  store.getCampaignSystemsRecords();
};

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
    name: "addRove",
    align: "left",
    required: true,
    classes: "text-no-wrap",
    label: "",
    field: (row) => row.id,
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
</script>

<style lang="sass">
.myHackCharTable
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
