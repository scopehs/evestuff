<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myTableStations myRound bg-webBack"
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
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-11 flex flex-center">
            <span class="text-h4">Stations</span>
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
              <div class="col-3">
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
              <div class="col-3">
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

              <div class="col-2">
                <q-select
                  rounded
                  clearable
                  dense
                  standout
                  input-debounce="0"
                  label-color="webway"
                  option-value="id"
                  option-label="name"
                  v-model="listID"
                  :options="store.watchListListForUser"
                  ref="typeRef"
                  label="WatchList"
                  map-options
                  use-input
                  use-chips
                  multiple
                  input-style=" max-width: 10px; min-width: 10px"
                >
                  <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                    <q-item v-bind="itemProps">
                      <q-item-section>
                        <q-item-label v-html="opt.name" />
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
                      <span class="text-xs"> {{ scope.opt.name }} </span>
                    </q-chip>
                  </template>
                </q-select>
              </div>
            </div>
          </div>
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
                size="sm"
                color="webChip"
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

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="webway" :props="props">
            <SoloCampaginWebWay
              v-if="webwayJumps(props.row) && webwayLink(props.row)"
              :jumps="webwayJumps(props.row)"
              :web="webwayLink(props.row)"
            ></SoloCampaginWebWay>
          </q-td>
          <q-td key="region" :props="props">
            {{ props.row.system.region.region_name }}
          </q-td>
          <q-td key="constellation" :props="props">
            {{ props.row.system.constellation.constellation_name }}
          </q-td>
          <q-td key="system" :props="props">
            <q-btn
              color="none"
              flat
              rounded
              text-color="positive"
              icon="fa-solid fa-map"
              :href="link(props.row)"
              target="_blank"
            />
            <span
              @click="copySystem(props.row.system.system_name)"
              class="cursor-pointer"
            >
              {{ props.row.system.system_name }}</span
            ></q-td
          >
          <q-td key="corpTicker" :props="props">
            <q-avatar size="lg" class="q-pr-xl">
              <img :src="props.row.corp.url" />
            </q-avatar>
            <span :class="standingCheckCorp(props.row)">
              {{ props.row.corp.ticker }}</span
            >
          </q-td>
          <q-td key="allianceTicker" :props="props">
            <span v-if="props.row.corp.alliance_id">
              <q-avatar size="lg" class="q-pr-xl">
                <img :src="props.row.corp.alliance.url" />
              </q-avatar>
              <span :class="standingCheck(props.row)">
                {{ props.row.corp.alliance.ticker }}
              </span></span
            >
          </q-td>
          <q-td key="type" :props="props">
            <q-avatar size="lg" class="q-pr-xl">
              <img :src="itemUrl(props.row.item_id)" />
            </q-avatar>
            {{ props.row.item.item_name }}
          </q-td>
          <q-td key="name" :props="props">
            {{ props.row.name }}
          </q-td>
          <q-td key="status" :props="props">
            <StatusButton :item="props.row" />
          </q-td>
          <q-td key="actions" :props="props">
            <div class="row">
              <div class="col"><AddStation :from="2" :station="props.row" /></div>
              <div class="col">
                <RcStationMessage :station="props.row" :type="4"></RcStationMessage>
              </div>
              <div class="col">
                <StationInfoSheet :station="props.row" v-if="showInfo(props.row)" />
              </div>
              <div class="col" v-if="can('view_station_logs')">
                <q-btn
                  size="md"
                  flat
                  color="none"
                  text-color="primary"
                  round
                  padding="none"
                  @click="props.expand = !props.expand"
                  icon="fa-solid fa-clock-rotate-left"
                />
              </div>
            </div>
          </q-td>
        </q-tr>
        <q-tr v-show="props.expand" :props="props">
          <q-td colspan="100%">
            <div>
              <StationSheetLogs :station="props.row" />
            </div>
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useQuasar, copyToClipboard } from "quasar";

let store = useMainStore();
let can = inject("can");
const $q = useQuasar();
let search = $ref("");

const SoloCampaginWebWay = defineAsyncComponent(() =>
  import("../components/operations/SoloCampaginWebWay.vue")
);

const StationSheetLogs = defineAsyncComponent(() =>
  import("../components/stationSheet/StationSheetLogs.vue")
);

const StatusButton = defineAsyncComponent(() =>
  import("../components/stationSheet/StatusButton.vue")
);
const StationInfoSheet = defineAsyncComponent(() =>
  import("../components/stationSheet/StationSheetInfo.vue")
);

const RcStationMessage = defineAsyncComponent(() =>
  import("../components/rcsheet/RcStationMessage.vue")
);

const AddStation = defineAsyncComponent(() =>
  import("../components/station/AddStation.vue")
);

onMounted(async () => {
  document.title = "Evestuff - Stations";
  await store.getStationList();
  await store.getWebwayStartSystems();
  await store.getWatchListListForUser();

  Echo.private("stationsheet")
    .listen("StationSheetUpdate", async (e) => {
      if (e.flag.message != null) {
        store.updateStationList(e.flag.message);
      }

      if (e.flag.flag == 1) {
        store.getWebwayStartSystems();
      }

      if (e.flag.flag == 2) {
        store.getStationList();
      }
      if (e.flag.flag == 3) {
        await store.getStationList();
        await store.getWatchListListForUser();
        joinWatchListChannels();
      }
    })
    .listen("StationDeadStationSheet", (e) => {
      store.deleteStationSheetNotification(e.flag.id);
    })
    .listen("StationSheetUpdateWebway", (e) => {
      updateWebwaySystem(e.flag.id);
    })
    .listen("StationSheetMessageUpdate", (e) => {
      store.updateStationList(e.flag.message);
    });

  joinWatchListChannels();
});

onBeforeUnmount(async () => {
  leaveWatchListChannels();
  Echo.leave("stationsheet");
});

let joinWatchListChannels = () => {
  store.watchListListForUser.forEach((w) => {
    Echo.private("watchliststationpage." + w.id).listen(
      "WatchListStationPageUpdate",
      (e) => {
        if (e.flag.flag == 1) {
          store.updateStationList(e.flag.message);
        }
        if (e.flag.flag == 2) {
        }
        if (e.flag.flag == 3) {
        }
      }
    );
  });
};

let leaveWatchListChannels = () => {
  store.watchListListForUser.forEach((w) => {
    Echo.leave("watchliststationpage." + w.id);
  });
};

let updateWebwaySystem = (id) => {
  axios({
    method: "put",
    url: "/api/stationsheetupdatewebway/" + id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let abortFilterFn = () => {};

let region_id = $ref([]);
let regionText = $ref();
let regionDropDownList = $computed(() => {
  var data = store.stationList.map((s) => ({
    id: s.system.region.id,
    name: s.system.region.region_name,
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
    if (regionList.length > 0 && val) {
      region_id = regionList[0];
    }
  });
};

let constellation_id = $ref([]);
let constellationText = $ref();

let conDropDownList = $computed(() => {
  var data = store.stationList.map((s) => ({
    id: s.system.constellation.id,
    name: s.system.constellation.constellation_name,
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
    if (constellationList.length > 0 && val) {
      constellation_id = constellationList[0];
    }
  });
};

let type_id = $ref([]);
let typeText = $ref();

let typeDropDownList = $computed(() => {
  var data = store.stationList.map((s) => ({
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
    if (typeList.length > 0 && val) {
      type_id = typeList[0];
    }
  });
};

// let filterList = $computed(() => {
//   if (listID.length > 0) {
//     return store.stationList.filter((s) => {
//       if (listID.map((p) => p.id).includes(s.list.id)) {
//         return true;
//       } else {
//         return false;
//       }
//     });
//   }
//   return store.stationList;
// });

let filterList = $computed(() => {
  if (listID.length > 0) {
    return store.stationList.filter((station) => {
      return station.list.some((id) => listID.some((item) => item.id === id.id));
    });
  }

  return store.stationList;
});

let filterStart = $computed(() => {
  if (region_id.length > 0) {
    return filterList.filter((s) => {
      if (region_id.map((p) => p.value).includes(s.system.region.id)) {
        return true;
      } else {
        return false;
      }
    });
  }
  return filterList;
});

let filterCon = $computed(() => {
  if (constellation_id.length > 0) {
    return filterStart.filter((s) => {
      if (constellation_id.map((p) => p.value).includes(s.system.constellation.id)) {
        return true;
      } else {
        return false;
      }
    });
  }
  return filterStart;
});

let filterEnd = $computed(() => {
  if (type_id.length > 0) {
    return filterCon.filter((s) => {
      if (type_id.map((p) => p.value).includes(s.item_id)) {
        return true;
      } else {
        return false;
      }
    });
  }

  return filterCon;
});

let listID = $ref([]);

let itemUrl = (item) => {
  return "https://images.evetech.net/types/" + item + "/icon";
};

let webwayJumps = (item) => {
  if (item.system.webway) {
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

let updateWebwaySelectedStartSystem = (item) => {
  var data = {
    value: item.value,
    text: item.text,
  };

  store.updateWebwaySelectedStartSystem(data);
};

let webwayLink = (item) => {
  if (item.system.webway) {
    var base = item.system.webway;
    var filter = base.filter(
      (f) => f.start_system_id == store.webwaySelectedStartSystem.value
    );
    if (filter.length > 0) {
      var link = filter[0].webway ?? null;
      return link;
    } else {
      return null;
    }
  } else {
    return null;
  }
};

let webwayButton = $computed(() => {
  if (store.webwayStartSystems) {
    if (store.webwayStartSystems.length > 0) {
      return true;
    }
  }
  return false;
});

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

let webwayDropdownList = (value) => {
  var list = webwayButtonList.filter((f) => f.value != value);
  return list;
};

let standingCheckCorp = (item) => {
  var standing = 0;
  if (item.corp.alliance) {
    standing = item.corp.alliance.standing;
  } else {
    standing = item.corp.standing;
  }
  if (standing > 0) {
    return "text-blue";
  } else if (standing < 0) {
    return "text-red";
  } else {
    return "";
  }
};

let standingCheck = (item) => {
  var standing = 0;
  if (item.corp.alliance) {
    standing = item.corp.alliance.standing;
  } else {
    standing = item.corp.standing;
  }
  if (standing > 0) {
    return "text-blue";
  } else if (standing < 0) {
    return "text-red";
  } else {
    return "";
  }
};

let showInfo = (item) => {
  if (!can("view_station_info_killsheet")) {
    return false;
  }
  if (item.item.id == 37534 || item.item.id == 35841 || item.item.id == 35840) {
    return false;
  }
  if (item.added_from_recon == 1) {
    return true;
  } else {
    return false;
  }
};

let pagination = $ref({
  sortBy: "progress",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let columns = $ref([
  {
    name: "webway",
    align: "center",
    required: false,
    label: "Webway",
    classes: "text-no-wrap",
    field: (row) => {
      if (row.system.webway[0]) {
        return row.system.webway[0].jumps;
      } else {
        return null;
      }
    },
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "region",
    required: true,
    align: "left",
    label: "Region",
    classes: "text-no-wrap",
    field: (row) => row.system.region.region_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "constellation",
    required: true,
    align: "left",
    label: "Constellation",
    classes: "text-no-wrap",
    field: (row) => row.system.constellation.constellation_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "system",
    align: "left",
    classes: "text-no-wrap",
    label: "System",
    field: (row) => row.system.system_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "corpTicker",
    align: "left",
    required: true,
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
    name: "type",
    label: "Type",
    align: "left",
    classes: "text-no-wrap",
    field: (row) => row.item.item_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "name",
    label: "Name",
    align: "left",
    classes: "text-no-wrap",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "status",
    label: "Status",
    classes: "text-no-wrap",
    align: "right",
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
]);

let copySystem = (text) => {
  copyToClipboard(text).then(() => {
    $q.notify({
      type: "info",
      message: text + " copied to your clipboard",
    });
  });
};

let link = (item) => {
  if (item.system.region.region_name == "Black Rise") {
    return (
      "https://evemaps.dotlan.net/map/Black_Rise/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "The Bleak Lands") {
    return (
      "https://evemaps.dotlan.net/map/The_Bleak_Lands/" +
      item.system.system_name +
      "#const"
    );
  }
  if (item.system.region.region_name == "The Citadel") {
    return (
      "https://evemaps.dotlan.net/map/The_Citadel/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Cloud Ring") {
    return (
      "https://evemaps.dotlan.net/map/Cloud_Ring/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Cobalt Edge") {
    return (
      "https://evemaps.dotlan.net/map/Cobalt_Edge/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Etherium Reach") {
    return (
      "https://evemaps.dotlan.net/map/Etherium_Reach/" +
      item.system.system_name +
      "#const"
    );
  }
  if (item.system.region.region_name == "The Forge") {
    return (
      "https://evemaps.dotlan.net/map/The_Forge/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "The Kalevala Expanse") {
    return (
      "https://evemaps.dotlan.net/map/The_Kalevala_Expanse/" +
      item.system.system_name +
      "#const"
    );
  }
  if (item.system.region.region_name == "Molden Heath") {
    return (
      "https://evemaps.dotlan.net/map/Molden_Heath/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Outer Passage") {
    return (
      "https://evemaps.dotlan.net/map/Outer_Passage/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Outer Ring") {
    return (
      "https://evemaps.dotlan.net/map/Outer_Ring/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Paragon Soul") {
    return (
      "https://evemaps.dotlan.net/map/Paragon_Soul/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Period Basis") {
    return (
      "https://evemaps.dotlan.net/map/Period_Basis/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Perrigen Falls") {
    return (
      "https://evemaps.dotlan.net/map/Perrigen_Falls/" +
      item.system.system_name +
      "#const"
    );
  }
  if (item.system.region.region_name == "Pure Blind") {
    return (
      "https://evemaps.dotlan.net/map/Pure_Blind/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Scalding Pass") {
    return (
      "https://evemaps.dotlan.net/map/Scalding_Pass/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Sinq Laison") {
    return (
      "https://evemaps.dotlan.net/map/Sinq_Laison/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "The Spire") {
    return (
      "https://evemaps.dotlan.net/map/The_Spire/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Vale of the Silent") {
    return (
      "https://evemaps.dotlan.net/map/Vale_of_the_Silent/" +
      item.system.system_name +
      "#const"
    );
  }
  if (item.system.region.region_name == "Verge Vendor") {
    return (
      "https://evemaps.dotlan.net/map/Verge_Vendor/" + item.system.system_name + "#const"
    );
  }
  if (item.system.region.region_name == "Wicked Creek") {
    return (
      "https://evemaps.dotlan.net/map/Wicked_Creek/" + item.system.system_name + "#const"
    );
  }
  return (
    "https://evemaps.dotlan.net/map/" +
    item.system.region.region_name +
    "/" +
    item.system.system_name +
    "#const"
  );
};

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableStations
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
