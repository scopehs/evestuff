<template>
  <div>
    <q-table
      class="myOperationSystemTable myRound bg-webBack"
      :rows="store.operationInfoPage.systems"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      no-data-label="All Hostile Stations our reffed!!!!!!"
      dark
      dense
      ref="tableRef"
      rounded
      hide-bottom
      :pagination="pagination"
    >
      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="region" :props="props"> {{ props.row.region.region_name }}</q-td>
          <q-td key="constellation" :props="props"
            >{{ props.row.constellation.constellation_name }}
          </q-td>
          <q-td key="system" :props="props"> {{ props.row.system_name }}</q-td>

          <q-td key="recon" :props="props">
            <div class="row">
              <div :class="colCount(props.row)">
                <OperationInfoSystemReconChips :item="props.row" />
              </div>
              <div class="col-1">
                <OperationInfoSystemAddRecon :item="props.row" />
              </div>
              <div class="col-l" v-if="showReconCount(props.row)">
                {{ reconCount(props.row) }} / {{ props.row.pivot.cynos_needed }}
              </div>
            </div>
          </q-td>
          <q-td key="jammed" :props="props"
            >{{ jamerText(props.row.pivot.jammed_status) }}
          </q-td>

          <q-td key="notes" :props="props"> {{ props.row.pivot.notes }}</q-td>
          <q-td key="actions" :props="props">
            <div class="row justify-end">
              <OperationInfoSystemAddNotes :item="props.row" />
              <OperationInfoSystemJammerSetting :item="props.row" />
            </div>
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

const OperationInfoSystemReconChips = defineAsyncComponent(() =>
  import("./OperationInfoSystemReconChips.vue")
);

const OperationInfoSystemAddRecon = defineAsyncComponent(() =>
  import("./OperationInfoSystemAddRecon.vue")
);

const OperationInfoSystemAddNotes = defineAsyncComponent(() =>
  import("./OperationInfoSystemAddNotes.vue")
);
const OperationInfoSystemJammerSetting = defineAsyncComponent(() =>
  import("./OperationInfoSystemJammerSetting.vue")
);

let store = useMainStore();
let pagination = $ref({
  sortBy: "id",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let jamerText = (num) => {
  switch (num) {
    case 1:
      return "Unknown";
    case 2:
      return "No";
    case 3:
      return "Yes";
  }
};

let colCount = (item) => {
  if (item.pivot.cynos_needed > 0) {
    return "col-10";
  } else {
    return "col-11";
  }
};

let showReconCount = (item) => {
  if (item.pivot.cynos_needed > 0) {
    return true;
  } else {
    return false;
  }
};

let reconCount = (item) => {
  return item.recons.length;
};

let columns = $ref([
  {
    name: "region",
    align: "left",
    style: "width: 10%",
    required: false,
    label: "Region",
    classes: "text-no-wrap",
    field: (row) => row.region.region_name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "constellation",
    align: "left",
    style: "width: 5%",
    label: "Constellation",
    classes: "text-no-wrap",
    field: (row) => row.constellation.constellation_name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "system",
    align: "left",
    style: "width: 5%",
    label: "System",
    classes: "text-no-wrap",
    field: (row) => row.system_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "recon",
    align: "center",
    classes: "text-no-wrap",
    label: "Recon",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
    filter: false,
  },
  {
    name: "jammed",
    align: "right",
    style: "width: 5%",
    classes: "text-no-wrap",
    label: "Jammed",
    field: (row) => row.pivot.jammed_status,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "notes",
    align: "right",
    label: "Notes",
    style: "width: 13%",
    classes: "text-no-wrap",
    field: (row) => row.pivot.notes,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },

  {
    name: "actions",
    label: "",
    style: "width: 8%",
    align: "right",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.id,
    format: (val) => `${val}`,
  },
]);
</script>
