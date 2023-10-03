<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myHackNotificationsTable myRound bg-webBack"
      :rows="filterEnd"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      no-data-label="No hacking notifications to show"
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
            <span class="text-h4">Hack Notifications</span>
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
            </div>
            <div class="row justify-end q-pt-sm">
              <div class="col-auto">
                <q-btn-toggle
                  v-model="filterTypeSelected"
                  rounded
                  unelevated
                  class="q-pr-md"
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
                  v-model="filterStatusSelcted"
                  rounded
                  unelevated
                  clearable
                  color="webDark"
                  text-color="gray"
                  toggle-color="primary"
                  toggle-text-color="gray"
                  :options="[
                    { label: 'Scouting', value: 6 },
                    { label: 'Reffed', value: 2 },
                    { label: 'Repairing', value: 3 },
                    { label: 'Secured', value: 4 },
                    { label: 'Hostile', value: 5 },
                    { label: 'New', value: 1 },
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
            <div>{{ props.row.system.region.region_name }}</div>
          </q-td>
          <q-td key="constellation" :props="props">
            <div>{{ props.row.system.constellation.constellation_name }}</div>
          </q-td>
          <q-td key="system" :props="props">
            <div>{{ props.row.system.system_name }}</div>
          </q-td>
          <q-td key="type" :props="props">
            <div>{{ props.row.item.item_name }}</div>
          </q-td>
          <q-td key="timeStamp" :props="props">
            <div>
              {{ props.row.timestamp }}
            </div>
          </q-td>
          <q-td :props="props" key="age">
            <div>
              <VueCountUp
                class="q-pl-sm"
                :emit-events="false"
                :time="countUpTimeMil(props.row.timestamp)"
                :interval="1000"
                v-slot="{ hours, minutes, seconds }"
              >
                <span class="text-negative">
                  {{ hours }}:{{ minutes }}:{{ seconds }}
                </span>
              </VueCountUp>
            </div>
          </q-td>
          <q-td key="status" :props="props">
            <div class="row flex-center">
              <q-btn
                v-if="can('edit_notifications')"
                class="myOutLineButtonLarge"
                size="md"
                rounded
                :color="statusButtonColor(props.row)"
                :label="props.row.status.name"
                ><q-menu>
                  <q-list style="min-width: 100px">
                    <q-item
                      v-close-popup
                      clickable
                      v-ripple
                      v-for="(list, index) in statusDropDownFilter(props.row.status_id)"
                      :key="index"
                      @click="updateStatus(list.value, props.row.id)"
                    >
                      <q-item-section>{{ list.label }}</q-item-section>
                    </q-item>
                  </q-list>
                </q-menu></q-btn
              >

              <q-chip
                class="myOutLineButtonLarge q-ma-none"
                v-else
                dense
                :ripple="false"
                rounded
                :color="statusButtonColor(props.row)"
                :label="props.row.status.name"
              />
            </div>
          </q-td>
          <q-td key="actions" :props="props">
            <div class="row flex-center">
              <div class="col">
                <q-btn
                  no-caps
                  color="accent"
                  label="Dscan"
                  dense
                  :outline="props.row.dscan_id ? false : true"
                  ><q-menu>
                    <div>
                      <q-card class="my-card" style="width: 20vw">
                        <q-card-section>
                          <q-input
                            v-model="dScanText"
                            type="textarea"
                            label="Paste your Dscan or local here"
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
                          <q-btn
                            v-else
                            rounded
                            color="primary"
                            label="Update"
                            v-close-popup
                            @click="subUpdateScan(props.row.dscan.link)"
                          />
                          <q-btn rounded color="negative" label="close" v-close-popup />

                          <q-btn
                            v-if="props.row.dscan_id"
                            rounded
                            color="info"
                            label="open"
                            :href="dscanLink(props.row.dscan.link)"
                            target="_blank"
                            v-close-popup
                          />
                        </q-card-actions>
                      </q-card>
                    </div> </q-menu
                ></q-btn>
              </div>
              <div class="col"><StagingDscan :item="props.row" /></div>
              <div class="col">
                <NotificationTimer
                  v-if="props.row.status_id == 5 || props.row.status_id == 3"
                  :item="props.row"
                />
              </div>
            </div>
          </q-td>
          <q-td key="editedBy" :props="props">
            <div>
              {{ props.row.user && props.row.user.name ? props.row.user.name : null }}
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
import { useRouter } from "vue-router";

const VueCountUp = defineAsyncComponent(() => import("../components/countup/index"));
const NotificationTimer = defineAsyncComponent(() =>
  import("@/components/notification/NotificationTimer.vue")
);

const StagingDscan = defineAsyncComponent(() =>
  import("@/components/staging/StagingDscan.vue")
);

let store = useMainStore();
let can = inject("can");
let loadingf = $ref(true);
let loadingt = $ref(true);
let loadingr = $ref(true);
let search = $ref("");
let filterTypeSelected = $ref();
let filterStatusSelcted = $ref();
let dScanText = $ref("");

let pagination = $ref({
  sortBy: "region",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let abortFilterFn = () => {};

onMounted(async () => {
  Echo.private("notes")
    .listen("NotificationChanged", (e) => {
      store.updateNotification(e.notifications);
    })

    .listen("NotificationNew", (e) => {
      loadTimers();
    });
  store.getNotifications().then(() => {
    loadingt = false;
    loadingf = false;
    loadingr = false;
  });
});

onBeforeUnmount(async () => {
  Echo.leave("notes");
});

let subNewScan = async (id) => {
  var data = {
    text: dScanText,
  };
  await axios({
    method: "post",
    withCredentials: true,
    data: data,
    url: "/api/notifications/dscan/" + id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((response) => {
    dScanText = "";
  });
};

let subUpdateScan = async (link) => {
  var data = {
    text: dScanText,
  };
  await axios({
    method: "post",
    withCredentials: true,
    data: data,
    url: "/api/dscan/" + link,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((response) => {
    dScanText = "";
  });
};

let loadTimers = () => {
  loadingr = true;
  store.getNotifications().then(() => {
    loadingr = false;
  });
};

let statusDropDown = $ref([
  { label: "Scouting", value: 6 },
  { label: "Reffed", value: 2 },
  { label: "Repairing", value: 3 },
  { label: "Secured", value: 4 },
  { label: "Hostile", value: 5 },
  { label: "New", value: 1 },
]);

let statusDropDownFilter = (status_id) => {
  return statusDropDown.filter((s) => s.value != status_id);
};

let updateStatus = async (status_id, id) => {
  let data = {
    status_id: status_id,
  };
  await axios({
    method: "put",
    withCredentials: true, //you can set what request you want to be
    url: "/api/notifications/" + id,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let statusButtonColor = (item) => {
  if (item.status_id == 1) {
    return "accent";
  } else if (item.status_id == 2) {
    return "negative";
  } else if (item.status_id == 3) {
    return "warning";
  } else if (item.status_id == 4) {
    return "primary";
  } else if (item.status_id == 5) {
    return "red-6";
  } else if (item.status_id == 6) {
    return "light-green darken-1";
  }
};

let region_id = $ref([]);
let regionText = $ref();
let regionDropDownList = $computed(() => {
  var data = filterByStatusSelected.map((s) => ({
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
  });
};

let constellation_id = $ref([]);
let constellationText = $ref();

let conDropDownList = $computed(() => {
  var data = filterByStatusSelected.map((s) => ({
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
  });
};

let filterRegion = $computed(() => {
  if (region_id.length > 0) {
    const filteredHacks = store.notifications.filter((t) => {
      return region_id.some((r) => {
        return r.value === t.system.region_id;
      });
    });
    return filteredHacks;
  }
  return store.notifications;
});

let filterConstellation = $computed(() => {
  if (constellation_id.length > 0) {
    const filteredHacks = filterRegion.filter((t) => {
      return constellation_id.some((c) => {
        return c.value === t.system.constellation_id;
      });
    });
    return filteredHacks;
  }
  return filterRegion;
});

let filterByTypeSelected = $computed(() => {
  if (filterTypeSelected) {
    return filterConstellation.filter((n) => n.item_id == filterTypeSelected);
  }
  return filterConstellation;
});

let filterByStatusSelected = $computed(() => {
  if (filterStatusSelcted) {
    return filterByTypeSelected.filter((n) => n.status_id == filterStatusSelcted);
  }
  return filterByTypeSelected;
});

let filterEnd = $computed(() => {
  return filterByStatusSelected;
});

let columns = $ref([
  {
    name: "region",
    align: "left",
    required: false,
    label: "Region",
    classes: "text-no-wrap",
    field: (row) => row.system.region.region_name,
    format: (val) => `${val}`,
    sortable: false,
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
    required: true,
    align: "left",
    label: "System",
    classes: "text-no-wrap",
    field: (row) => row.system.system_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "type",
    required: true,
    align: "left",
    label: "Type",
    classes: "text-no-wrap",
    field: (row) => row.item.item_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "timeStamp",
    align: "left",
    classes: "text-no-wrap",
    label: "Time Stamp",
    field: (row) => row.timestamp,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "age",
    align: "left",
    required: true,
    label: "Age",
    classes: "text-no-wrap",
    field: (row) => row.age,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "status",
    align: "center",
    classes: "text-no-wrap",
    label: "Status",
    field: (row) => row.status.name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "actions",
    label: "",
    align: "center",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.id,
    format: (val) => `${val}`,
  },
  {
    name: "editedBy",
    label: "Edited By",
    align: "right",
    classes: "text-no-wrap",
    field: (row) => (row.user && row.user.name ? row.user.name : "Unknown"),
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
]);

let dscanLink = (link) => {
  return "dscan/" + link;
};

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
.myHackNotificationsTable
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
