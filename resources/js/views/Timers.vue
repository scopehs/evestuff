<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myTableWindows myRound bg-webBack"
      :rows="filterEnd"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      dark
      dense
      :filter="search"
      ref="tableRef"
      rounded
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Vulnerability Windows</span>
          </div>
        </div>
        <div class="row full-width q-pt-md justify-between">
          <div class="col-9">
            <div class="row q-gutter-sm q-pl-md">
              <q-input
                rounded
                standout
                dense
                debounce="300"
                v-model="search"
                clearable
                placeholder="Search"
              >
                <template v-slot:append>
                  <q-icon name="fa-solid fa-magnifying-glass" />
                </template>
              </q-input>
              <q-select
                rounded
                dense
                standout
                input-debounce="0"
                label-color="webway"
                option-value="value"
                option-label="text"
                v-model="regionPicked"
                :options="regionPickedEnd"
                label="Region"
                ref="regionDropDown"
                @filter="regionPickStart"
                map-options
                use-input
                use-chips
                multiple
              >
                <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                  <q-item v-bind="itemProps">
                    <q-item-section>
                      <q-item-label v-html="opt.text" />
                    </q-item-section>
                    <q-item-section side>
                      <q-toggle
                        :model-value="selected"
                        @update:model-value="toggleOption(opt)"
                      />
                    </q-item-section>
                  </q-item>
                </template>
                <template v-slot:selected-item="scope">
                  <q-chip
                    removable
                    @remove="scope.removeAtIndex(scope.index)"
                    :tabindex="scope.tabindex"
                    color="webChip"
                    text-color="white"
                    class="q-ma-none"
                  >
                    <span class="text-xs"> {{ scope.opt.text }} </span>
                  </q-chip>
                </template></q-select
              >
            </div>
          </div>
          <div class="col-auto">
            <div class="row q-pr-md q-gutter-sm">
              <q-btn-toggle
                v-model="filterItemStatusSelect"
                rounded
                unelevated
                color="webDark"
                text-color="gray"
                toggle-color="primary"
                toggle-text-color="gray"
                :options="[
                  { label: 'Open', value: 1 },
                  { label: 'Closed', value: 2 },
                ]"
              />
              <q-btn-toggle
                v-model="filterStandingSelectList"
                rounded
                unelevated
                clearable
                color="webDark"
                text-color="gray"
                toggle-color="primary"
                toggle-text-color="gray"
                :options="[
                  { label: 'Goon', value: 3 },
                  { label: 'Friendly', value: 2 },
                  { label: 'Hostile', value: 1 },
                ]"
              />
            </div>
          </div>
        </div>
      </template>

      <template v-slot:header-cell-progress="props">
        <q-th :props="props">
          <div class="row">
            <div class="col">
              <span>{{ headText }}</span>
            </div>
          </div>
        </q-th>
      </template>

      <template v-slot:body-cell-alliance="props">
        <q-td :props="props">
          <div class="">
            <q-avatar size="lg" class="q-pr-xl">
              <img :src="props.row.url" />
            </q-avatar>

            <span v-if="props.row.color >= 2" class="text-blue"
              >{{ props.row.alliance }}
            </span>
            <span v-else-if="props.row.color == 1" class="text-red"
              >{{ props.row.alliance }}
            </span>
            <span v-else class="">{{ props.row.alliance }} </span>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-window="props">
        <q-td :props="props"
          ><span v-if="props.row.window_station === 'Open'" class="text-positive">{{
            props.value
          }}</span>
          <span v-else class="text-negative">{{ props.value }}</span>
        </q-td>
      </template>

      <template v-slot:body-cell-countdown="props">
        <q-td :props="props">
          <TimerCountDown :row="props.row" />
        </q-td>
      </template>
      <template v-slot:body-cell-age="props">
        <q-td :props="props">
          <VueCountUp
            class="q-pl-sm"
            :time="countUpTimeMil(props.row.age)"
            :interval="60000"
            v-slot="{ hours, days }"
          >
            <span class="text-positive">
              <span v-if="days != 0"> {{ days }} Days - </span>
              {{ hours }} Hours
            </span>
          </VueCountUp>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

let store = useMainStore();
let filterItemStatusSelect = $ref(1);
let filterStandingSelectList = $ref(3);
const VueCountUp = defineAsyncComponent(() => import("../components/countup/index"));
const TimerCountDown = defineAsyncComponent(() =>
  import("../components/timers/TimerCountDown.vue")
);

let search = $ref("");

onMounted(async () => {
  await store.getTimerDataAll();
  await store.getTimerDataAllRegion();

  document.title = "Evestuff - Vulnerability Windows";
});
onBeforeUnmount(async () => {});

let abortFilterFn = () => {};

let regionPickText = $ref();
let regionPicked = $ref([]);
let regionPickedEnd = $computed(() => {
  if (regionPickText) {
    return store.timersRegions.filter(
      (d) => d.text.toLowerCase().indexOf(regionPickText) > -1
    );
  }
  return store.timersRegions;
});

let regionPickStart = (val, update, abort) => {
  update(() => {
    regionPickText = val.toLowerCase();
    if (systemlistFinishEnd.length > 0 && val) {
      finish_system_id = systemlistFinishEnd[0];
    }
  });
};

let finish_system_id = $ref(null);
let finsishSystemText = $ref();

let systemlistFinishEnd = $computed(() => {
  if (finsishSystemText) {
    return store.timersRegions.filter(
      (d) => d.text.toLowerCase().indexOf(finsishSystemText) > -1
    );
  }
  return store.timersRegions;
});

let filteredItems_start = $computed(() => {
  if (filterStandingSelectList == 3) {
    return store.timers.filter((t) => t.color == 3 && t.status == 0);
  }

  if (filterStandingSelectList == 2) {
    return store.timers.filter((t) => t.color == 2 && t.status == 0);
  }

  if (filterStandingSelectList == 1) {
    return store.timers.filter((t) => t.color == 1 && t.status == 0);
  }

  return store.timers.filter((t) => t.status == 0);
});

let filteredItems = $computed(() => {
  if (filterItemStatusSelect == 1) {
    return filteredItems_start.filter((t) => t.window_station === "Open");
  }
  if (filterItemStatusSelect == 2) {
    return filteredItems_start.filter((t) => t.window_station === "Closed");
  }
  return filteredItems_start;
});

let filterRegion = $computed(() => {
  if (regionPicked.length > 0) {
    return filteredItems.filter((t) => {
      if (regionPicked.map((p) => p.value).includes(t.region_id)) {
        return true;
      } else {
        return false;
      }
    });
  }
  return filteredItems;
});

let filterEnd = $computed(() => {
  return filterRegion;
});

let countUpTimeMil = (time) => {
  const timestamp = Date.parse(time);
  return timestamp;
};

let pagination = $ref({
  sortBy: "progress",
  descending: false,
  page: 1,
  rowsPerPage: 50,
});

let headText = $computed(() => {
  if (filterItemStatusSelect == 1) {
    return "Time Till Close";
  } else {
    return "Time Till Open";
  }
});

let columns = $ref([
  {
    name: "region",
    align: "region",
    label: "Region",
    classes: "text-no-wrap",
    field: (row) => row.region,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "constellation",
    align: "left",
    label: "Constellation",
    classes: "text-no-wrap",
    field: (row) => row.constellation,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "system",
    align: "left",
    label: "System",
    classes: "text-no-wrap",
    field: (row) => row.system,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "alliance",
    align: "left",
    classes: "text-no-wrap",
    label: "Alliance",
    field: (row) => row.alliance,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },

  {
    name: "ticker",
    align: "left",
    label: "Ticker",
    style: "width: 5%",
    classes: "text-no-wrap",
    field: (row) => row.ticker,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "window",
    label: "Window",
    classes: "text-no-wrap",
    field: (row) => row.window_station,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "structure",
    label: "Structure",
    classes: "text-no-wrap",
    sortable: false,
    filter: false,
    field: (row) => row.type,
    format: (val) => {
      if (val == 32458) {
        return "IHUB";
      }
      return "TCU";
    },
  },

  {
    name: "adm",
    align: "left",
    classes: "text-no-wrap",
    label: "ADM",
    field: (row) => row.adm,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "progress",
    label: "Start/Progress",
    classes: "text-no-wrap",
    align: "center",
    style: "width: 25%",
    sortable: false,
    field: (row) => row.time,
    format: (val) => `${val}`,
  },
  {
    name: "countdown",
    label: "Countdown",
    align: "center",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.standing,
    format: (val) => `${val}`,
  },

  {
    name: "age",
    label: "Age",
    align: "right",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.age,
    format: (val) => `${val}`,
  },
]);
let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableWindows
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
