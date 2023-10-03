<template>
  <div class="q-ma-md">
    <div class="row justify-center">
      <div class="col-10">
        <q-table
          title="Connections"
          class="myTableOperationInfo myRound bg-webBack"
          :rows="store.operationInfo"
          :columns="columns"
          table-class=" text-webway"
          table-header-class=" text-weight-bolder"
          row-key="id"
          no-data-label="No Operations"
          dark
          dense
          ref="tableRef"
          rounded
          hide-bottom
          :pagination="pagination"
        >
          <template v-slot:top="props">
            <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
              <div class="col-11 flex flex-center">
                <span class="text-h4">Operations</span>
              </div>
            </div>
            <div class="row full-width justify-end">
              <div class="col-3 flex justify-end q-pt-sm q-pr-sm"><AddOperation /></div>
            </div>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="title" :props="props">
                <div>{{ props.row.name }}</div>
              </q-td>
              <q-td key="status" :props="props">
                <div>{{ props.row.status.name }}</div>
              </q-td>
              <q-td key="start" :props="props">
                <div>{{ props.row.start }}</div>
              </q-td>
              <q-td key="actions" :props="props">
                <div>
                  <q-btn
                    rounded
                    push
                    color="primary"
                    @click="go(props.row.link)"
                    label="view"
                  />
                  <q-btn
                    rounded
                    padding="xs"
                    text-color="red"
                    flat
                    @click="remove(props.row.link)"
                    icon="fa-solid fa-trash-can"
                  />
                </div>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";

const AddOperation = defineAsyncComponent(() =>
  import("../components/operationinfo/AddOperationInfo.vue")
);

let store = useMainStore();
let can = inject("can");
let router = useRouter();

onMounted(async () => {
  document.title = "Evestuff - Operations";
  store.getOperationSheetInfo();

  Echo.private("operationinfopage").listen("OperationInfoPageUpdate", (e) => {
    if (e.flag.flag == 1) {
    }

    // add/update solo operationinfo
    if (e.flag.flag == 2) {
      store.updateOperationPageInfo(e.flag.message);
    }

    if (e.flag.flag == 3) {
      store.removeOperationPageInfo(e.flag.message);
    }
  });
});

onBeforeUnmount(async () => {
  Echo.leave("operationinfopage");
});

let pagination = $ref({
  sortBy: "actions",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let go = (id) => {
  router.push({ path: `/operationinfo/${id}` });
};

let remove = async (id) => {
  await axios({
    method: "DELETE",
    url: "/api/operationinfosheet/" + id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let columns = $ref([
  {
    name: "title",
    align: "left",
    required: false,
    label: "Title",
    classes: "text-no-wrap",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "status",
    required: false,
    align: "left",
    label: "Status",
    classes: "text-no-wrap",
    field: (row) => row.status.name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "start",
    required: false,
    align: "right",
    label: "Start",
    classes: "text-no-wrap",
    field: (row) => row.start,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "actions",
    required: false,
    align: "right",
    label: "",
    classes: "text-no-wrap",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
    filter: false,
  },
]);

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableOperationInfo
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
