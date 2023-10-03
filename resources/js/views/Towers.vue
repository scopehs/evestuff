<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myTablePoS myRound bg-webBack"
      :rows="filterEnd"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      no-data-label="All Hostile Stations our reffed!!!!!!"
      dark
      dense
      :filter="search"
      ref="tableRef"
      rounded
      hide-bottom
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-11 flex flex-center">
            <span class="text-h4">Towers</span>
          </div>
        </div>
        <div class="row full-width q-pt-md justify-between">
          <div class="col-12">
            <div class="row q-gutter-sm q-pl-md">
              <div class="col-1">
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
              </div>
              <div class="col-2">
                <q-select
                  rounded
                  dense
                  clearable
                  standout
                  input-debounce="0"
                  label-color="webway"
                  option-value="value"
                  option-label="text"
                  v-model="region_id"
                  :options="regionList"
                  ref="toRegionRef"
                  label="Region"
                  @clear="region_id = []"
                  @filter="filterFnRegionFinish"
                  @filter-abort="abortFilterFn"
                  map-options
                  use-input
                  use-chips
                  multiple
                  input-style=" max-width: 10px; min-width: 10px"
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
                      text-color="white"
                      class="q-ma-none"
                      color="webChip"
                    >
                      <span class="text-xs"> {{ scope.opt.text }} </span>
                    </q-chip>
                  </template></q-select
                >
              </div>
              <div class="col-4">
                <q-select
                  rounded
                  clearable
                  dense
                  standout
                  input-debounce="0"
                  label-color="webway"
                  option-value="value"
                  option-label="text"
                  v-model="constellation_id"
                  :options="constellationList"
                  ref="toConstellationRef"
                  label="Constellations"
                  @clear="constellation_id = []"
                  @filter="filterFnConstellationFinish"
                  @filter-abort="abortFilterFn"
                  map-options
                  use-input
                  use-chips
                  multiple
                  input-style=" max-width: 10px; min-width: 10px"
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
                      text-color="white"
                      class="q-ma-none"
                      color="webChip"
                    >
                      <span class="text-xs"> {{ scope.opt.text }} </span>
                    </q-chip>
                  </template></q-select
                >
              </div>
              <div class="col-4">
                <q-select
                  rounded
                  clearable
                  dense
                  standout
                  input-debounce="0"
                  label-color="webway"
                  option-value="value"
                  option-label="text"
                  v-model="type_id"
                  :options="typeList"
                  ref="typeRef"
                  label="Type"
                  @filter="filterFnTypeFinish"
                  @filter-abort="abortFilterFn"
                  map-options
                  use-input
                  use-chips
                  multiple
                  input-style=" max-width: 10px; min-width: 10px"
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
                      text-color="white"
                      class="q-ma-none"
                      color="webChip"
                    >
                      <span class="text-xs"> {{ scope.opt.text }} </span>
                    </q-chip>
                  </template></q-select
                >
              </div>
            </div>
            <div class="row justify-end q-pt-sm">
              <div class="col"><AddTower /></div>
              <div class="col-auto">
                <q-btn-toggle
                  v-model="filterStandingSelected"
                  rounded
                  unelevated
                  class="q-pr-md"
                  clearable
                  color="webDark"
                  text-color="gray"
                  toggle-color="primary"
                  toggle-text-color="gray"
                  :options="[
                    { label: 'Hostile', value: 1 },
                    { label: 'Friendly', value: 2 },
                  ]"
                />
                <q-btn-toggle
                  v-model="filterStatusSelcted"
                  rounded
                  unelevated
                  clearable
                  color="webDark"
                  text-color="gray"
                  toggle-color="primary"
                  toggle-text-color="gray"
                  :options="[
                    { label: 'Scouting', value: 2 },
                    { label: 'Anchoring', value: 3 },
                    { label: 'Online', value: 4 },
                    { label: 'Reffed', value: 5 },
                    { label: 'Dead', value: 6 },
                  ]"
                />
              </div>
            </div>
          </div>
        </div>
      </template>
      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="region" :props="props">
            <div>{{ props.row.moon.region.region_name }}</div>
          </q-td>
          <q-td key="constellation" :props="props">
            <div>{{ props.row.moon.constellation.constellation_name }}</div>
          </q-td>
          <q-td key="system" :props="props">
            <div>{{ props.row.moon.system.system_name }}</div>
          </q-td>
          <q-td key="corpTicker" :props="props">
            <div>
              <q-avatar size="md" class="q-pa-none">
                <img :src="props.row.corp.url" />
              </q-avatar>
              {{ props.row.corp.ticker }}
            </div>
          </q-td>
          <q-td :props="props" key="allianceTicker">
            <div v-if="props.row.corp.alliance">
              <q-avatar size="md" class="q-pa-none">
                <img :src="props.row.corp.alliance.url" />
              </q-avatar>
              {{ props.row.corp.alliance.ticker }}
            </div>
          </q-td>
          <q-td key="moon" :props="props">
            <div>{{ props.row.moon.name }}</div>
          </q-td>
          <q-td key="type" :props="props">
            <div>{{ props.row.item.item_name }}</div>
          </q-td>
          <!-- <q-td key="time" :props="props">
            <div>{{ props.row.out_time }}</div>
          </q-td>
          <q-td :props="props" key="timeLeft">
            <div>
              <VueCountDown
                v-if="props.row.out_time"
                :time="countDownTimeMil(props.row.out_time)"
                :interval="1000"
                :emit-events="false"
                v-slot="{ hours, minutes, seconds, days }"
                :transform="transformSlotProps"
              >
                <span class="text-red">
                  <span v-if="days > 0"
                    >{{ days }}:{{ hours }}:{{ minutes }}:{{ seconds }}</span
                  >
                  <span v-else-if="hours > 0">
                    {{ hours }}:{{ minutes }}:{{ seconds }}
                  </span>
                  <span v-else> {{ minutes }}:{{ seconds }} </span>
                </span>
              </VueCountDown>
            </div>
          </q-td> -->
          <q-td key="status" :props="props">
            <div>
              <q-btn
                class="myOutLineButtonLarge"
                :color="chipColor(props.row.status.id)"
                size="md"
                rounded
                :label="props.row.status.name"
                ><q-menu>
                  <q-list style="min-width: 100px">
                    <q-item
                      v-for="(list, index) in statusDropDownFilter(props.row.status.id)"
                      :key="index"
                      @click="menuClick(props.row.id, list.value)"
                      clickable
                      v-close-popup
                    >
                      <q-item-section>{{ list.title }}</q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </q-btn>
            </div>
          </q-td>

          <q-td key="actions" :props="props">
            <div class="row flex-center">
              <TowerMessage class="q-pr-lg" :tower="props.row" :type="4" />
              <q-btn
                class="myOutLineButtonLarge"
                :outline="fitOutLine(props.row)"
                v-if="fitOutLine(props.row)"
                rounded
                color="accent"
                label="Fit"
              >
                <q-menu>
                  <q-card class="my-card" style="width: 20vw">
                    <q-card-section>
                      <q-input
                        v-model="dScanText"
                        type="textarea"
                        label="Paste The Dscan of the fit here"
                      />
                    </q-card-section>
                    <q-card-actions align="between">
                      <q-btn
                        v-if="!props.row.dscan_id"
                        rounded
                        color="primary"
                        label="Submit"
                        v-close-popup
                        @click="subNewScan(props.row.id)"
                      />

                      <q-btn rounded color="negative" label="close" v-close-popup />
                    </q-card-actions>
                  </q-card>
                </q-menu>
              </q-btn>
              <TowerFit v-else :tower="props.row" />
            </div>
          </q-td>
          <q-td key="editedBy" :props="props">
            <div>{{ props.row.user.name }}</div>
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import axios from "axios";

let store = useMainStore();
let search = $ref("");
let can = inject("can");
let filterStandingSelected = $ref();
let filterStatusSelcted = $ref();
let dScanText = $ref("");

const VueCountDown = defineAsyncComponent(() => import("../components/countdown/index"));
const AddTower = defineAsyncComponent(() => import("../components/tower/AddTower.vue"));
const TowerMessage = defineAsyncComponent(() =>
  import("../components/tower/TowerMessage.vue")
);
const TowerFit = defineAsyncComponent(() => import("../components/tower/TowerFit.vue"));

onMounted(async () => {
  document.title = "Evestuff - Towers";
  Echo.private("towers")
    .listen("TowerChanged", (e) => {
      store.updateTowers(e.flag.message);
    })
    .listen("TowerNew", (e) => {
      store.getTowerData();
    })
    .listen("TowerDelete", (e) => {
      store.deleteTower(e.flag.id);
    })
    .listen("TowerSheetMessageUpdate", (e) => {
      store.updateTowers(e.flag.message);
    });

  await store.getTowerData();
  //   await store.getTowerFilter();
});

onBeforeUnmount(async () => {
  Echo.leave("towers");
});

let pagination = $ref({
  sortBy: "region",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let statusDropDown = $ref([
  { title: "New", value: 1 },
  { title: "Scouted", value: 2 },
  { title: "Online", value: 4 },
  { title: "Reffed", value: 5 },
  { title: "Anchored", value: 9 },
  { title: "Anchoring", value: 3 },
  { title: "Dead", value: 6 },
]);

let chipColor = (statusId) => {
  if (statusId == 1) {
    return "webway";
  }
  if (statusId == 2) {
    return "secondary";
  }
  if (statusId == 3) {
    return "warning";
  }
  if (statusId == 4) {
    return "primary";
  }
  if (statusId == 5) {
    return "negative";
  }
  if (statusId == 6) {
    return "negative";
  }
  if (statusId == 7) {
    return "orange";
  }
  if (statusId == 8) {
    return "warning";
  }

  if (statusId == 9) {
    return "teal";
  }
};

let fitOutLine = (item) => {
  if (item.fit.length > 0) {
    return false;
  }
  return true;
};

let subNewScan = async (id) => {
  var data = {
    text: dScanText,
  };
  await axios({
    method: "post",
    withCredentials: true,
    data: data,
    url: "/api/tower/addfit/" + id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((response) => {
    dScanText = "";
  });
};

let statusDropDownFilter = (id) => {
  let list = statusDropDown.filter((s) => s.value != id);
  return list;
};

let menuClick = (id, statusID) => {
  var request = {
    status_id: statusID,
  };
  axios({
    method: "put",
    url: "api/tower/updatestatus/" + id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let countDownTimeMil = (time) => {
  const utcTime = new Date(time + "Z");
  const currentTimestamp = Date.now();
  const timeDiff = utcTime.getTime() - currentTimestamp;
  return timeDiff;
};

let transformSlotProps = (props) => {
  const formattedProps = {};

  Object.entries(props).forEach(([key, value]) => {
    formattedProps[key] = value < 10 ? `0${value}` : String(value);
  });

  return formattedProps;
};

let filterRegion = $computed(() => {
  if (region_id.length > 0) {
    const filteredTowers = store.towers.filter((t) => {
      return region_id.some((r) => {
        return r.value === t.moon.region_id;
      });
    });
    return filteredTowers;
  }
  return store.towers;
});

let filterConstellation = $computed(() => {
  if (constellation_id.length > 0) {
    const filteredTowers = filterRegion.filter((t) => {
      return constellation_id.some((c) => {
        return c.value === t.moon.constellation_id;
      });
    });
    return filteredTowers;
  }
  return filterRegion;
});

let filterType = $computed(() => {
  if (type_id.length > 0) {
    const filteredTowers = filterConstellation.filter((t) => {
      return type_id.some((c) => {
        return c.value === t.item_id;
      });
    });
    return filteredTowers;
  }
  return filterConstellation;
});

let filterStanding = $computed(() => {
  if (filterStandingSelected) {
    if (filterStandingSelected == 1) {
      return filterType.filter((f) => {
        if (f.corp.alliance_id) {
          if (f.corp.alliance.standing <= 0) {
            return true;
          } else {
            return false;
          }
        } else {
          if (f.corp.standing <= 0) {
            return true;
          } else {
            return false;
          }
        }
      });
    } else {
      return filterType.filter((f) => {
        if (f.corp.alliance_id) {
          if (f.corp.alliance.standing > 0) {
            return true;
          } else {
            return false;
          }
        } else {
          if (f.corp.standing > 0) {
            return true;
          } else {
            return false;
          }
        }
      });
    }
  } else {
    return filterType;
  }
});

let filterStatus = $computed(() => {
  if (filterStatusSelcted) {
    return filterStanding.filter((f) => f.status.id == filterStatusSelcted);
  } else {
    return filterStanding;
  }
});

let filterEnd = $computed(() => {
  return filterStatus;
});

let region_id = $ref([]);
let regionText = $ref();
let regionDropDownList = $computed(() => {
  var data = filterType.map((s) => ({
    id: s.moon.region.id,
    name: s.moon.region.region_name,
  }));
  const result = [];
  const map = new Map();
  for (const item of data) {
    if (!map.has(item.id)) {
      map.set(item.id, true);
      result.push({
        value: item.id,
        text: item.name,
      });
    }
  }
  result.sort((a, b) => a.text.localeCompare(b.text));
  return result;
});

let regionList = $computed(() => {
  if (regionText) {
    return regionDropDownList.filter(
      (d) => d.text.toLowerCase().indexOf(regionText) > -1
    );
  }
  return regionDropDownList;
});

let filterFnRegionFinish = (val, update, abort) => {
  update(() => {
    regionText = val.toLowerCase();
  });
};

let constellation_id = $ref([]);
let constellationText = $ref();

let conDropDownList = $computed(() => {
  var data = filterType.map((s) => ({
    id: s.moon.constellation.id,
    name: s.moon.constellation.constellation_name,
  }));
  const result = [];
  const map = new Map();
  for (const item of data) {
    if (!map.has(item.id)) {
      map.set(item.id, true);
      result.push({
        value: item.id,
        text: item.name,
      });
    }
  }
  result.sort((a, b) => a.text.localeCompare(b.text));
  return result;
});

let constellationList = $computed(() => {
  if (constellationText) {
    return conDropDownList.filter(
      (d) => d.text.toLowerCase().indexOf(constellationText) > -1
    );
  }
  return conDropDownList;
});

let filterFnConstellationFinish = (val, update, abort) => {
  update(() => {
    constellationText = val.toLowerCase();
  });
};

let type_id = $ref([]);
let typeText = $ref();

let typeDropDownList = $computed(() => {
  var data = filterType.map((s) => ({
    id: s.item.id,
    type: s.item.item_name,
  }));
  const result = [];
  const map = new Map();
  for (const item of data) {
    if (!map.has(item.id)) {
      map.set(item.id, true);
      result.push({
        value: item.id,
        text: item.type,
      });
    }
  }
  result.sort((a, b) => a.text.localeCompare(b.text));
  return result;
});

let typeList = $computed(() => {
  if (typeText) {
    return typeDropDownList.filter((d) => d.text.toLowerCase().indexOf(typeText) > -1);
  }
  return typeDropDownList;
});

let filterFnTypeFinish = (val, update, abort) => {
  update(() => {
    typeText = val.toLowerCase();
  });
};
let abortFilterFn = () => {};

let columns = $ref([
  {
    name: "region",
    align: "left",
    required: false,
    label: "Region",
    classes: "text-no-wrap",
    field: (row) => row.moon.region.region_name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "constellation",
    required: true,
    align: "left",
    label: "Constellation",
    classes: "text-no-wrap",
    field: (row) => row.moon.constellation.constellation_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "system",
    required: true,
    align: "left",
    label: "System",
    classes: "text-no-wrap",
    field: (row) => row.moon.system.system_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "corpTicker",
    align: "left",
    classes: "text-no-wrap",
    label: "Corp",
    field: (row) => row.corp.ticker,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "allianceTicker",
    align: "left",
    required: true,
    label: "Alliance",
    // style: "width: 5%",
    classes: "text-no-wrap",
    field: (row) => {
      if (row.corp.alliance) {
        return row.corp.alliance.ticker;
      } else {
        return null;
      }
    },
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "moon",
    align: "left",
    classes: "text-no-wrap",
    label: "Moon",
    field: (row) => row.moon.name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "type",
    label: "Type",
    align: "left",
    classes: "text-no-wrap",
    field: (row) => row.item.item_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  //   {
  //     name: "time",
  //     label: "Time",
  //     classes: "text-no-wrap",
  //     align: "left",
  //     sortable: true,
  //     field: (row) => row.out_time,
  //     format: (val) => `${val}`,
  //   },
  //   {
  //     name: "timeLeft",
  //     label: "Time Left",
  //     classes: "text-no-wrap",
  //     align: "left",
  //     sortable: true,
  //     field: (row) => row.out_time,
  //     format: (val) => `${val}`,
  //   },

  {
    name: "status",
    label: "Status",
    classes: "text-no-wrap",
    align: "center",
    sortable: true,
    field: (row) => row.status.name,
    format: (val) => `${val}`,
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
  {
    name: "editedBy",
    label: "Edited By",
    classes: "text-no-wrap",
    align: "right",
    sortable: true,
    field: (row) => row.user.name,
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
.myTablePoS
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
