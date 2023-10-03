<template>
  <div>
    <q-btn
      class="myOutLineButtonLarge"
      rounded
      @click="showFit = true"
      color="accent"
      label="Fit"
    >
    </q-btn>
    <q-dialog v-model="showFit" persistent>
      <q-card class="my-card q-pa-none myRoundTop" style="width: 25vw">
        <q-card-section class="q-pa-none">
          <q-table
            class="myTowerItemTable myRound bg-webBack"
            :rows="props.tower.fit"
            :columns="columns2"
            table-class=" text-webway"
            table-header-class=" text-weight-bolder"
            row-key="id"
            no-data-label="No Fit"
            dark
            :style="{ maxHeight: `${hs}` }"
            dense
            ref="tableRef"
            rounded
            visible-columns="icon, item"
            flat
            hide-bottom
            hide-header
            v-model:pagination="pagination2"
          >
            <template v-slot:top="props">
              <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
                <div class="col-12 flex flex-center">
                  <span class="text-h4">{{ tower.moon.name }}</span>
                </div>
              </div></template
            >
            <template v-slot:body-cell-icon="props">
              <q-td class="q-pa-none" :props="props">
                <q-avatar class="q-pa-none" size="md">
                  <img :src="url(props.row)" class="q-pa-none" />
                </q-avatar>
              </q-td>
            </template>
          </q-table>
        </q-card-section>
        <q-card-section>
          <q-input
            v-model="dScanText"
            type="textarea"
            label="Update The Dscan of the fit here"
          />
        </q-card-section>
        <q-card-actions align="between">
          <q-btn
            rounded
            color="primary"
            label="Update"
            v-close-popup
            @click="subNewScan(props.row.id)"
          />

          <q-btn rounded color="negative" label="close" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let pagination2 = $ref({
  sortBy: "itemid",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let showFit = $ref(false);
let dScanText = $ref("");
const props = defineProps({
  tower: Object,
});

let url = (item) => {
  return "https://images.evetech.net/types/" + item.item_id + "/icon";
};

let columns2 = $ref([
  {
    name: "icon",
    align: "left",
    required: false,
    label: "Icon",
    classes: "text-no-wrap",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "item",
    required: true,
    align: "left",
    label: "Constellation",
    classes: "text-no-wrap",
    field: (row) => row.item.item_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },

  {
    name: "itemid",
    align: "left",
    required: false,
    label: "itemId",
    classes: "text-no-wrap",
    field: (row) => row.item_id,
    format: (val) => `${val}`,
    sortable: false,
  },
]);

let hs = $computed(() => {
  let mins = 325;
  let window = store.size.height;
  return window - mins + "px";
});
</script>

<style lang="sass">
.myTowerItemTable
  /* height or max-height is important */
  height: v-bind(hs)

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
