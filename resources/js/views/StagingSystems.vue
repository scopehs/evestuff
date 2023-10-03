<template>
  <div class="q-ma-md">
    <div class="row justify-center">
      <div class="col-10">
        <q-table
          title="Connections"
          class="myTableStagingSystem myRound bg-webBack"
          :rows="store.stagingSystems"
          :columns="columns"
          table-class=" text-webway"
          table-header-class=" text-weight-bolder"
          row-key="id"
          dark
          dense
          hide-pagination=""
          ref="tableRef"
          rounded
          :pagination="pagination"
        >
          <template v-slot:top="props">
            <div
              class="row justify-between full-width flex-center q-pt-xs myRoundTop bg-primary"
            >
              <div class="col-auto"></div>
              <div class="col-auto">
                <span class="text-h4">Staging</span>
              </div>

              <div class="col-auto"><StagingEditPannel /></div>
            </div>
          </template>

          <template v-slot:header-cell-webway="props">
            <q-th :props="props">
              <div class="row">
                <div class="col">
                  <span class="myFont">Webway</span>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <q-btn
                    v-if="webwayButton"
                    color="webChip"
                    size="sm"
                    :label="store.webwaySelectedStartSystem.text"
                  >
                    <q-menu>
                      <q-card class="my-card">
                        <q-list bordered>
                          <q-item
                            v-close-popup
                            clickable
                            v-ripple
                            v-for="(list, index) in webwayDropdownList(
                              store.webwaySelectedStartSystem.value
                            )"
                            :key="index"
                            @click="updateWebwaySelectedStartSystem(list)"
                          >
                            <q-item-section>{{ list.text }}</q-item-section>
                          </q-item>
                        </q-list>
                      </q-card>
                    </q-menu></q-btn
                  >
                  <span v-else>{{ store.webwaySelectedStartSystem.text }}</span>
                </div>
              </div>
            </q-th>
          </template>

          <template v-slot:body-cell-webway="props">
            <q-td :props="props">
              <SoloCampaginWebWay
                v-if="webwayJumps(props.row) && webwayLink(props.row)"
                :jumps="webwayJumps(props.row)"
                :web="webwayLink(props.row)"
              ></SoloCampaginWebWay>
            </q-td>
          </template>

          <template v-slot:body-cell-dscan="props">
            <q-td :props="props"> <StagingDscan :item="props.row" /> </q-td>
          </template>
          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <div class="row q-gutter-sm justify-end">
                <div class="col-auto">
                  <StagingEditPannel :edit="true" :item="props.row" />
                </div>
                <div class="col-auto">
                  <q-btn
                    color="negative"
                    icon="fa-solid fa-trash-alt"
                    size="sm"
                    round
                    padding="none"
                    flat
                    @click="removeSystem(props.row.id)"
                  />
                </div>
              </div>
            </q-td>
          </template>
        </q-table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useQuasar, copyToClipboard } from "quasar";
import { useMainStore } from "@/store/useMain.js";

const StagingEditPannel = defineAsyncComponent(() =>
  import("@/components/staging/StagingEditPannel.vue")
);

const StagingDscan = defineAsyncComponent(() =>
  import("@/components/staging/StagingDscan.vue")
);

const SoloCampaginWebWay = defineAsyncComponent(() =>
  import("@/components/operations/SoloCampaginWebWay.vue")
);

let pagination = $ref({
  sortBy: "progress",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let store = useMainStore();
onMounted(async () => {
  document.title = "Evestuff - Staging Systems";
  await store.getWebwayStartSystems();
  await store.getStagingSystemInfo();
  await store.getSystemList();
  Echo.private("stagingsystem").listen("StagingSystemUpdate", (e) => {
    if (e.flag.flag == 1) {
      store.updateStagingSystem(e.flag.message);
    }

    if (e.flag.flag == 2) {
      store.deleteStagingSystem(e.flag.message);
    }
  });
});

onBeforeUnmount(() => {
  Echo.leave("stagingsystem");
});

let menu = $ref(false);

let removeSystem = async (id) => {
  await axios({
    method: "delete", //you can set what request you want to be
    url: "api/staging/" + id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let webwayButton = $computed(() => {
  if (store.webwayStartSystems) {
    if (store.webwayStartSystems.length > 0) {
      return true;
    }
  }
  return false;
});

let webwayDropdownList = (value) => {
  var list = webwayButtonList.filter((f) => f.value != value);
  return list;
};

let updateWebwaySelectedStartSystem = (item) => {
  var data = {
    value: item.value,
    text: item.text,
  };

  menu = false;

  store.updateWebwaySelectedStartSystem(data);
};

let webwayButtonList = $computed(() => {
  var list = store.webwayStartSystems;
  var data = {
    value: 30004759,
    text: "1DQ1-A",
  };
  list.push(data);
  list.sort(function (a, b) {
    return a.value - b.value || a.text.localeCompare(b.text);
  });

  return list;
});

let webwayJumps = (item) => {
  if (item.system.webway.length > 0) {
    var base = item.system.webway;
    var filter = base.filter(
      (f) => f.start_system_id == store.webwaySelectedStartSystem.value
    );
    if (filter.length > 0) {
      var jumps = filter[0].jumps;
      return jumps;
    } else {
      return null;
    }
  } else {
    return null;
  }
};

let webwayLink = (item) => {
  if (item.system.webway.length > 0) {
    var base = item.system.webway;
    var filter = base.filter(
      (f) => f.start_system_id == store.webwaySelectedStartSystem.value
    );
    if (filter.length > 0) {
      var link = filter[0].webway;
      return link;
    } else {
      return null;
    }
  } else {
    return null;
  }
};

let columns = $ref([
  {
    name: "webway",
    align: "left",
    required: false,
    label: "Webway",
    style: "width: 5%",
    classes: "text-no-wrap",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
  },

  {
    name: "name",
    align: "left",
    required: false,
    label: "Name",
    style: "width: 15%",
    classes: "text-no-wrap",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "region",
    required: true,
    align: "left",
    style: "width: 7%",
    label: "Region",
    classes: "text-no-wrap",
    field: (row) => row.system.constellation.region.region_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "constellation",
    required: true,
    align: "left",
    style: "width: 7%",
    label: "Constellation",
    classes: "text-no-wrap",
    field: (row) => row.system.constellation.constellation_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "system",
    required: true,
    align: "left",
    style: "width: 7%",
    label: "System",
    classes: "text-no-wrap",
    field: (row) => row.system.system_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "dscan",
    required: true,
    align: "center",
    label: "Dscan",
    classes: "text-no-wrap",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "actions",
    required: true,
    align: "right",
    label: "",
    classes: "text-no-wrap",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
]);

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableStagingSystem
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
