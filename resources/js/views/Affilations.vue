<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myAffilationsTable myRound bg-webBack"
      :rows="store.affiliations"
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
        <div
          class="row justify-between full-width flex-center q-pt-xs myRoundTop bg-primary"
        >
          <div class="col-auto"></div>
          <div class="col-auto">
            <span class="text-h4">Affiliations</span>
          </div>

          <div class="col-auto">
            <AffilationEditPannel />
          </div>
        </div>
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="row">
            <AffilationEditPannel :edit="true" :item="props.row" />
            <q-btn
              color="negative"
              padding="none"
              size="sm"
              flat
              round
              icon="fa-solid fa-trash-alt"
              @click="remove(props.row.id)"
            />
          </div>
        </q-td>
      </template>

      <template v-slot:body-cell-color="props">
        <q-td :props="props">
          <div class="row">
            {{ colorText(props.row.color) }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-alliances="props">
        <q-td :props="props">
          <div class="row">
            <AffilationAllianceChips :item="props.row" />
            <q-btn
              color="info"
              padding="none"
              size="sm"
              flat
              round
              icon="fa-solid fa-plus"
            >
              <q-menu @before-hide="closeMenu">
                <q-card class="my-card">
                  <q-card-section>
                    <q-select
                      v-model="pickedAlliance"
                      :options="tickerlistFilter"
                      option-value="value"
                      option-label="text"
                      label="Alliance Ticker"
                      outlined
                      rounded
                      use-input
                      style="width: 200px"
                      @filter="tickerFilterStart"
                    />
                  </q-card-section>
                  <q-card-actions align="between">
                    <q-btn
                      rounded
                      color="positive"
                      label="Submit"
                      v-close-popup
                      @click="addAlliance(props.row.id)"
                    />
                    <q-btn rounded color="negative" label="Close" v-close-popup />
                  </q-card-actions>
                </q-card>
              </q-menu>
            </q-btn>
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let can = inject("can");

let pickedAlliance = $ref();

const AffilationEditPannel = defineAsyncComponent(() =>
  import("@/components/affilation/AffilationEditPannel.vue")
);

const AffilationAllianceChips = defineAsyncComponent(() =>
  import("@/components/affilation/AffilationAllianceChips.vue")
);

onMounted(async () => {
  document.title = "Evestuff - Affilations";
  store.getAffilationTable();
  store.getAllianceTickList();
  Echo.private("affilation").listen("AffilationUpdate", (e) => {
    if (e.flag.flag == 1) {
      store.updateAffilation(e.flag.message);
    }

    if (e.flag.flag == 2) {
      store.affiliations = store.affiliations.filter((item) => item.id != e.flag.message);
    }
  });
});

onBeforeUnmount(async () => {
  Echo.leave("affilation");
});

let tickerText = $ref();
let tickerlistFilter = $computed(() => {
  if (tickerText) {
    return store.allianceticklist.filter(
      (d) => d.text.toLowerCase().indexOf(tickerText) > -1
    );
  }
  return store.allianceticklist;
});

let tickerFilterStart = (val, update, abort) => {
  update(() => {
    tickerText = val.toLowerCase();
    if (tickerlistFilter.length > 0 && val) {
      pickedAlliance = tickerlistFilter[0];
    }
  });
};

let remove = async (id) => {
  await axios({
    method: "delete",
    withCredentials: true, //you can set what request you want to be
    url: "/api/affiliation/" + id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  store.affiliations = store.affiliations.filter((item) => item.id != id);
};

let addAlliance = async (id) => {
  let data = {
    alliance_id: pickedAlliance.value,
  };

  await axios({
    method: "post",
    data: data,
    withCredentials: true, //you can set what request you want to be
    url: "/api/affiliation/alliance/" + id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((response) => {
    store.updateAffilation(response.data.affiliation);
  });
};

let closeMenu = () => {
  pickedAlliance = null;
  tickerText = null;
};

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
    name: "short_name",
    required: true,
    align: "left",
    label: "Short",
    classes: "text-no-wrap",
    field: (row) => row.short_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "color",
    required: true,
    align: "left",
    label: "Color",
    classes: "text-no-wrap",
    field: (row) => row.color,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "alliances",
    required: true,
    align: "center",
    label: "Alliances",
    classes: "text-no-wrap",
    style: "width: 80%",
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

let colorText = (color) => {
  if (color == 0) {
    return "Neutral";
  }

  if (color == 1) {
    return "Red";
  }

  if (color == 2) {
    return "Blue";
  }
};

let pagination = $ref({
  sortBy: "id",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myAffilationsTable
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
