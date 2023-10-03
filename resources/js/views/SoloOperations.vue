<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myTableOperations myRound bg-webBack"
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
            <span class="text-h4">Operations</span>
          </div>
        </div>
        <div class="row full-width q-pt-md justify-between">
          <div class="col-auto">
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
                hide-selected
                fill-input
              />
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
                hide-selected
                fill-input
              />
              <q-btn dense flat round :icon="text" @click="clickBell()" />
            </div>
          </div>
          <div class="col-auto">
            <div class="row q-pr-md q-gutter-sm">
              <q-btn-toggle
                v-model="filterItemTypeSelect"
                rounded
                unelevated
                clearable
                color="webDark"
                text-color="gray"
                toggle-color="primary"
                toggle-text-color="gray"
                :options="[
                  { label: 'Ihub', value: 32458 },
                  { label: 'TCU', value: 32226 },
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
              <q-btn-toggle
                v-model="filterStatusSelect"
                rounded
                unelevated
                color="webDark"
                text-color="gray"
                toggle-color="primary"
                toggle-text-color="gray"
                :options="[
                  { label: 'Upcoming', value: 1 },
                  { label: 'Active', value: 2 },
                  { label: 'Finished', value: 3 },
                ]"
              />
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

      <template v-slot:body="props">
        <q-tr :props="props" :class="tableRow(props.row)" @click="onRowClick(props.row)">
          <q-td key="webway" :props="props">
            <SoloCampaginWebWay
              v-if="webwayJumps(props.row) && webwayLink(props.row)"
              :jumps="webwayJumps(props.row)"
              :web="webwayLink(props.row)"
            ></SoloCampaginWebWay>
          </q-td>
          <q-td key="region" :props="props">
            <span v-if="props.row.priority == 0">
              {{ props.row.campaign[0].constellation.region.region_name }}
            </span>
            <span v-else>
              <q-chip :label="props.row.campaign[0].constellation.region.region_name" />
            </span>
          </q-td>
          <q-td key="constellation" :props="props">
            <span v-if="props.row.priority == 0">
              {{ props.row.campaign[0].constellation.constellation_name }}
            </span>
            <span v-else>
              <q-chip
                size="md"
                :label="props.row.campaign[0].constellation.constellation_name"
            /></span>
          </q-td>
          <q-td key="system" :props="props">
            <span v-if="props.row.priority == 0">
              {{ props.row.campaign[0].system.system_name }}</span
            >
            <span v-else>
              <q-chip :label="props.row.campaign[0].system.system_name"
            /></span>
          </q-td>
          <q-td key="alliance" :props="props">
            <div class="row items-baseline">
              <div class="col-auto" v-if="props.row.priority == 1">
                <span class="rainbow-2 pr-2">
                  <q-icon size="xs" name="fa-solid fa-wand-magic-sparkles fa-bounce" />
                </span>
              </div>
              <div class="col-auto">
                <q-avatar size="lg" class="q-pr-xl">
                  <img :src="props.row.campaign[0].alliance.url" />
                </q-avatar>

                <span v-if="props.row.priority == 0">
                  <span
                    v-if="props.row.campaign[0].alliance.standing > 0"
                    class="text-blue"
                    >{{ props.row.campaign[0].alliance.name }}
                  </span>
                  <span
                    v-else-if="props.row.campaign[0].alliance.standing < 0"
                    class="text-red"
                    >{{ props.row.campaign[0].alliance.name }}
                  </span>
                  <span v-else class=""
                    >{{ props.row.campaign[0].alliance.name }}
                  </span></span
                >
                <span v-else>
                  <q-chip
                    v-if="props.row.campaign[0].alliance.standing > 0"
                    color="primary"
                    >{{ props.row.campaign[0].alliance.name }}</q-chip
                  >
                  <q-chip
                    v-else-if="props.row.campaign[0].alliance.standing < 0"
                    color="red"
                    text-color="white"
                    >{{ props.row.campaign[0].alliance.name }}</q-chip
                  >
                  <q-chip v-else>{{ props.row.campaign[0].alliance.name }}</q-chip></span
                >
              </div>
              <div class="col-auto" v-if="props.row.priority == 1">
                <span class="rainbow-2 pr-2">
                  <q-icon size="xs" name="fa-solid fa-wand-magic-sparkles fa-bounce" />
                </span>
              </div>
            </div>
          </q-td>
          <q-td key="ticker" :props="props">
            <span v-if="props.row.priority == 0">
              {{ props.row.campaign[0].alliance.ticker }}
            </span>
            <span v-else> <q-chip :label="props.row.campaign[0].alliance.ticker" /></span>
          </q-td>
          <q-td key="adm" :props="props">
            <span v-if="props.row.priority == 0">
              {{ props.row.campaign[0].system.adm }}</span
            >
            <span v-else> <q-chip :label="props.row.campaign[0].system.adm" /></span>
          </q-td>
          <q-td key="structure" :props="props">
            <span v-if="props.row.priority == 0">
              <span v-if="props.row.campaign[0].event_type == 32458">IHUB</span>
              <span v-else>TCU</span></span
            >
            <span v-if="props.row.priority == 1">
              <span v-if="props.row.campaign[0].event_type == 32458"
                ><q-chip label="IHUB"
              /></span>
              <span v-else><q-chip label="TCU" /></span>
            </span>
          </q-td>
          <q-td key="progress" :props="props">
            <div
              v-if="
                props.row.campaign[0].status_id == 1 ||
                props.row.campaign[0].status_id == 5
              "
            >
              <span v-if="props.row.priority == 0">
                {{ props.row.campaign[0].start_time }}
              </span>
              <span v-else> <q-chip :label="props.row.campaign[0].start_time" /></span>
            </div>
            <SoloCampaignProgressLine
              v-else-if="props.row.campaign[0].status_id == 2"
              :attackScore="props.row.campaign[0].attackers_score"
              :defenderScore="props.row.campaign[0].defenders_score"
              :attackScoreOld="props.row.campaign[0].attackers_score_old"
              :defenderScoreOld="props.row.campaign[0].defenders_score_old"
            />
            <span
              v-else-if="
                props.row.campaign[0].status_id == 3 ||
                props.row.campaign[0].status_id == 4
              "
            >
              <p
                v-if="props.row.campaign[0].attackers_score == 0"
                class="text-md-center text-green"
              >
                {{ props.row.campaign[0].alliance.name }}
                <span class="font-weight-bold"> WON </span> the
                {{ itemType(props.row.campaign[0].event_type) }} timer.
              </p>
              <p v-else class="text-md-center text-red">
                {{ props.row.campaign[0].alliance.name }}
                <span class="font-weight-bold"> LOST </span> the
                {{ itemType(props.row.campaign[0].event_type) }} timer.
              </p>
            </span>
          </q-td>
          <q-td key="count" :props="props">
            <div class="row no-gutters justify-start items-center">
              <SoloCampaignOperationChip :row="props.row" />
              <CampaignMap
                @click.stop
                :system_name="props.row.campaign[0].system.system_name"
                :region_name="props.row.campaign[0].constellation.region.region_name"
              />
              <div class="col-5">
                <VueCountUp
                  class="q-pl-sm"
                  :emit-events="false"
                  v-if="props.row.campaign[0].structure != null"
                  :time="countUpTimeMil(props.row.campaign[0].structure.age)"
                  :interval="60000"
                  v-slot="{ hours, days }"
                >
                  <span v-if="props.row.priority == 0">
                    <span class="text-positive">
                      <span v-if="days != 0"> {{ days }} Days - </span>
                      {{ hours }} Hours
                    </span>
                  </span>
                  <span v-if="props.row.priority == 1">
                    <span class="text-positive">
                      <q-chip class="text-positive">
                        <span v-if="days != 0"> {{ days }} Days - </span>
                        {{ hours }} Hours
                      </q-chip>
                    </span>
                  </span>
                </VueCountUp>
              </div>
            </div>
          </q-td>
          <q-td
            key="actions"
            :props="props"
            v-if="can('edit_hack_priority')"
            @click.stop="onSingleCellClick()"
          >
            <NewCampaginPriorityButton
              v-if="can('edit_hack_priority')"
              :row="props.row"
            />
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";

let store = useMainStore();
let can = inject("can");
let pOnly = $ref(0);
let router = useRouter();
let search = $ref("");

const SoloCampaignProgressLine = defineAsyncComponent(() =>
  import("../components/operations/SoloCampaignProgressLine.vue")
);
const VueCountUp = defineAsyncComponent(() => import("../components/countup/index"));

const SoloCampaignOperationChip = defineAsyncComponent(() =>
  import("../components/operations/SoloCampaignOperationChip.vue")
);

const CampaignMap = defineAsyncComponent(() =>
  import("../components/campaign/CampaignMap.vue")
);
const SoloCampaginWebWay = defineAsyncComponent(() =>
  import("../components/operations/SoloCampaginWebWay.vue")
);

const NewCampaginPriorityButton = defineAsyncComponent(() =>
  import("../components/newcampaign/NewCampaginPriorityButton.vue")
);

onMounted(async () => {
  await store.getWebwayStartSystems();
  await store.getSoloOperationList();

  Echo.private("solooperation").listen("SoloOperationUpdate", (e) => {
    if (e.flag.flag == 1) {
      store.updateSoloOperationList(e.flag.message);
    }
  });
});

onBeforeUnmount(async () => {
  Echo.leave("solooperation");
});

document.title = "Evestuff - Operations";

let abortFilterFn = () => {};

let region_id = $ref(null);
let regionText = $ref();
let menu = $ref(false);
let filterItemTypeSelect = $ref(32458);
let filterStandingSelectList = $ref(null);
let filterStatusSelect = $ref(1);

let regionList = $computed(() => {
  if (regionText) {
    return store.newSoloOperationsRegionList.filter(
      (d) => d.text.toLowerCase().indexOf(regionText) > -1
    );
  }
  return store.newSoloOperationsRegionList;
});

let filterFnRegionFinish = (val, update, abort) => {
  update(() => {
    regionText = val.toLowerCase();
    if (regionList.length > 0 && val) {
      region_id = regionList[0];
    }
  });
};

let constellation_id = $ref(null);
let constellationText = $ref();

let constellationList = $computed(() => {
  if (constellationText) {
    return store.newSoloOperationsConstellationList.filter(
      (d) => d.text.toLowerCase().indexOf(constellationText) > -1
    );
  }
  return store.newSoloOperationsConstellationList;
});

let filterFnConstellationFinish = (val, update, abort) => {
  update(() => {
    constellationText = val.toLowerCase();
    if (constellationList.length > 0 && val) {
      constellation_id = constellationList[0];
    }
  });
};

let text = $computed(() => {
  if (pOnly == 0) {
    return "fa-regular fa-bell";
  } else {
    return "fa-solid fa-bell";
  }
});

let onSingleCellClick = () => {};

let clickBell = () => {
  if (pOnly == 0) {
    pOnly = 1;
  } else {
    pOnly = 0;
  }
};

let filterStart = $computed(() => {
  if (filterItemTypeSelect) {
    return store.newSoloOperations.filter(
      (d) => d.campaign[0].event_type == filterItemTypeSelect
    );
  } else {
    return store.newSoloOperations;
  }
});

let filterMind = $computed(() => {
  if (region_id) {
    return filterStart.filter(
      (d) => d.campaign[0].constellation.region_id == region_id.value
    );
  } else {
    return filterStart;
  }
});

let filterMind1 = $computed(() => {
  if (constellation_id) {
    return filterMind.filter(
      (d) => d.campaign[0].constellation_id == constellation_id.value
    );
  } else {
    return filterMind;
  }
});

let filterMind2 = $computed(() => {
  if (filterStandingSelectList) {
    if (filterStandingSelectList == 3) {
      return filterMind1.filter((d) => d.campaign[0].alliance.color == 3);
    } else if (filterStandingSelectList == 2) {
      return filterMind1.filter((d) => d.campaign[0].alliance.standing > 0);
    } else {
      return filterMind1.filter((d) => d.campaign[0].alliance.standing <= 0);
    }
  } else {
    return filterMind1;
  }
});

let filterMind3 = $computed(() => {
  if (pOnly == 1) {
    return filterMind2.filter((d) => d.priority == 1);
  } else {
    return filterMind2;
  }
});

let filterEnd = $computed(() => {
  if (filterStatusSelect == 1) {
    return filterMind3.filter(
      (d) =>
        d.campaign[0].status_id == 5 ||
        d.campaign[0].status_id == 1 ||
        d.campaign[0].status_id == 2
    );
  } else if (filterStatusSelect == 2) {
    return filterMind3.filter(
      (d) => d.campaign[0].status_id == 5 || d.campaign[0].status_id == 2
    );
  } else {
    return filterMind3.filter(
      (d) => d.campaign[0].status_id == 3 || d.campaign[0].status_id == 4
    );
  }
});

let itemType = (typeID) => {
  if (typeID == 32458) {
    return "Ihub";
  } else {
    return "TCU";
  }
};

let webwayJumps = (item) => {
  if (item.campaign[0].system.webway.length > 0) {
    var base = item.campaign[0].system.webway;
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

  menu = false;

  store.updateWebwaySelectedStartSystem(data);
};

let webwayLink = (item) => {
  if (item.campaign[0].system.webway.length > 0) {
    var base = item.campaign[0].system.webway;
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

let tableRow = (item) => {
  if (item.priority == 1) {
    return "style-2";
  }
};

let pagination = $ref({
  sortBy: "progress",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let onRowClick = (item) => {
  if (can("access_campaigns")) {
    if (item.campaign[0].status_id == 2 || item.campaign[0].status_id == 5) {
      router.push({ path: `/op/${item.link}` });
    }
  }
};

let columns = $ref([
  {
    name: "webway",
    align: "center",
    required: false,
    label: "Webway",
    classes: "text-no-wrap",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "region",
    required: true,
    align: "left",
    label: "Region",
    classes: "text-no-wrap",
    style: "width: 7%",
    field: (row) => row.campaign[0].constellation.region.region_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "constellation",
    required: true,
    align: "left",
    label: "Constellation",
    classes: "text-no-wrap",
    field: (row) => row.campaign[0].constellation.constellation_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "system",
    align: "left",
    classes: "text-no-wrap",
    label: "System",
    field: (row) => row.campaign[0].system.system_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "alliance",
    align: "left",
    required: true,
    classes: "text-no-wrap",
    label: "Alliance",
    field: (row) => row.campaign[0].alliance.name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "ticker",
    align: "left",
    required: true,
    label: "Ticker",
    style: "width: 5%",
    classes: "text-no-wrap",
    field: (row) => row.campaign[0].alliance.ticker,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "adm",
    label: "ADM",
    classes: "text-no-wrap",
    field: (row) => row.campaign[0].system.adm,
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
    field: (row) => row.campaign[0].event_type,
    format: (val) => {
      if (val == 32458) {
        return "IHUB";
      }
      return "TCU";
    },
  },
  {
    name: "progress",
    label: "Start/Progress",
    classes: "text-no-wrap",
    align: "center",
    style: "width: 25%",
    sortable: false,
    field: (row) => row.campaign[0].start_time,
    format: (val) => `${val}`,
  },
  {
    name: "count",
    label: "Countdown/Age",
    align: "center",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.campaign[0].system.adm,
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

let countUpTimeMil = (time) => {
  const timestamp = Date.parse(time);
  return timestamp;
};

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableOperations
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

<style lang="sass" scoped>
.my-custom-toggle
  border: 1px solid #027be3
</style>

<style>
.style-2 {
  background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
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

.follow {
  margin-top: 40px;
}

.follow a {
  color: black;
  padding: 8px 16px;
  text-decoration: none;
}

.rainbow-2 {
  animation: colorRotate 0.5s linear 0s infinite;
}

.style-1 {
  background-color: rgb(184, 22, 35);
}

@keyframes colorRotate {
  from {
    color: #6666ff;
  }

  10% {
    color: #0099ff;
  }

  50% {
    color: #00ff00;
  }

  75% {
    color: #ff3399;
  }

  100% {
    color: #6666ff;
  }
}
</style>
