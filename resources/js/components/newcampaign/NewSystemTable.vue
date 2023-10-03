<template>
  <div class="row">
    <div class="col">
      <q-table
        v-if="props.activeCampaigns"
        class="myNewCampaignSystemTable myRound bg-webBack"
        :rows="props.item.new_nodes"
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
        v-model:expanded="expanded"
      >
        <template v-slot:header-cell-actions="props">
          <q-th :props="props">
            <AddNode
              :item="item"
              :operationID="operationID"
              :activeCampaigns="activeCampaigns"
            />
          </q-th>
        </template>

        <template v-slot:body="props">
          <q-tr :props="props" :class="itemRowBackground(props.row)">
            <q-td key="campaign" :props="props">
              <span v-if="props.row.node_status.id != 7 && props.row.node_status.id != 8">
                {{ props.row.campaign.name }}</span
              >
              <span v-else>
                <q-chip> {{ props.row.campaign.name }}</q-chip>
              </span>
            </q-td>
            <q-td key="name" :props="props">
              <span v-if="props.row.node_status.id != 7 && props.row.node_status.id != 8">
                {{ props.row.name }}</span
              >
              <span v-else>
                <q-chip> {{ props.row.name }}</q-chip>
              </span>
            </q-td>
            <q-td key="pilot" :props="props">
              <div class="row">
                <div class="col-auto">
                  <AddPilot :node="props.row" :operationID="operationID" />
                </div>
                <div class="col-auto">
                  <NewNodeExtraChar :node="props.row" :operationID="operationID" />
                </div>
                <div class="col-auto" v-if="can('campaigns_admin_access')">
                  <AddPilotAdmin :node="props.row" :operationID="operationID" />
                </div>
              </div>
            </q-td>
            <q-td key="main" :props="props">
              <NewSystemTableSimpleText :node="props.row" :type="1" />
            </q-td>
            <q-td key="ship" :props="props">
              <NewSystemTableSimpleText :node="props.row" :type="2" />
            </q-td>
            <q-td key="status" :props="props">
              <NewSystemTableStatusButton :node="props.row" :operationID="operationID" />
            </q-td>
            <q-td key="age" :props="props">
              <NewSystemTableTimer
                :node="props.row"
                :operationID="operationID"
                :extra="1"
                :tidiProp="props.row.system.tidi"
                :systemIDProp="props.row.system_id"
              />
            </q-td>
            <q-td key="actions" :props="props">
              <q-btn
                color="warning"
                icon="fa-solid fa-trash-can"
                rounded
                flat
                size="xs"
                padding="none"
                @click="removenode(props.row)"
              />
            </q-td>
          </q-tr>
          <q-tr v-show="props.expand" :props="props">
            <q-td colspan="100%">
              <div class="text-left">
                <NewJoinNodeTable :node="props.row" :operationID="operationID" />
              </div>
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

const props = defineProps({
  item: Object,
  operationID: Number,
  activeCampaigns: Array,
});

const AddNode = defineAsyncComponent(() => import("./AddNode.vue"));
const AddPilot = defineAsyncComponent(() => import("./AddPilot.vue"));
const NewNodeExtraChar = defineAsyncComponent(() => import("./NewNodeExtraChar.vue"));
const AddPilotAdmin = defineAsyncComponent(() => import("./AddPilotAdmin.vue"));
const NewSystemTableSimpleText = defineAsyncComponent(() =>
  import("./NewSystemTableSimpleText.vue")
);
const NewSystemTableStatusButton = defineAsyncComponent(() =>
  import("./NewSystemTableStatusButton.vue")
);
const NewSystemTableTimer = defineAsyncComponent(() =>
  import("./NewSystemTableTimer.vue")
);

const NewJoinNodeTable = defineAsyncComponent(() => import("./NewJoinNodeTable.vue"));
let store = useMainStore();
let can = inject("can");

let pagination = $ref({
  sortBy: "name",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let removenode = async (item) => {
  var id = item.id;
  await axios({
    method: "DELETE",
    url: "/api/deletenode/" + id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let expanded = $computed(() => {
  if (props.item.new_nodes) {
    var data = props.item.new_nodes.filter((f) => f.none_prime_node_user.length > 0);
    var ids = data.map((data) => data.id);
    return ids;
  }
});

let columns = $computed(() => {
  if (props.activeCampaigns.length == 1) {
    return [
      {
        name: "name",
        align: "center",
        required: false,
        label: "NodeID",
        classes: "text-no-wrap",
        field: (row) => row.name,
        format: (val) => `${val}`,
        sortable: false,
      },
      {
        name: "pilot",
        align: "center",
        required: false,
        label: "Pilot",
        classes: "text-no-wrap",
        field: (row) => row.op_users,
        format: (val) => `${val}`,
        sortable: false,
      },
      {
        name: "main",
        align: "center",
        required: false,
        label: "Main",
        classes: "text-no-wrap",
        field: (row) => row.id,
        format: (val) => `${val}`,
        sortable: false,
      },
      {
        name: "ship",
        align: "center",
        required: false,
        label: "Ship",
        classes: "text-no-wrap",
        field: (row) => row.id,
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
    ];
  } else {
    return [
      {
        name: "campaign",
        align: "center",
        required: false,
        label: "Campaign",
        classes: "text-no-wrap",
        field: (row) => row.campaign.name,
        format: (val) => `${val}`,
        sortable: false,
      },
      {
        name: "name",
        align: "center",
        required: false,
        label: "NodeID",
        classes: "text-no-wrap",
        field: (row) => row.name,
        format: (val) => `${val}`,
        sortable: false,
      },
      {
        name: "pilot",
        align: "center",
        required: false,
        label: "Pilot",
        classes: "text-no-wrap",
        field: (row) => row.op_users,
        format: (val) => `${val}`,
        sortable: false,
      },
      {
        name: "main",
        align: "center",
        required: false,
        label: "Main",
        classes: "text-no-wrap",
        field: (row) => row.id,
        format: (val) => `${val}`,
        sortable: false,
      },
      {
        name: "ship",
        align: "center",
        required: false,
        label: "Ship",
        classes: "text-no-wrap",
        field: (row) => row.id,
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
    ];
  }
});

let itemRowBackground = (item) => {
  if (item.node_status.id == 7) {
    return "style-1-table";
  } else if (item.node_status.id == 8) {
    return "style-2-table";
  }
};
</script>

<style lang="sass">
.myNewCampaignSystemTable
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

<style>
.style-4-table {
  background-color: rgba(255, 153, 0, 0.199);
}
.style-3-table {
  background-color: rgb(255, 172, 77);
}
.style-2-table {
  background: linear-gradient(-45deg, #00768b, #00d9ff, #5fccff, #00a2ff);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

.style-1-table {
  background: linear-gradient(-45deg, #8b0000, #ff0000, #ff5f5f, #ff0000);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}
</style>
