<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myTableMoveRC myRound bg-webBack"
      :rows="store.stations"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      dark
      dense
      :no-data-label="notDataLableText"
      :filter="search"
      ref="tableRef"
      rounded
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Timers to input to RC</span>
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
            </div>
          </div>
          <div class="col-auto">
            <div class="row q-pr-md q-gutter-sm"><AddStation /></div>
          </div>
        </div>
      </template>

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="region" :props="props">{{ props.cols[0].value }}</q-td>
          <q-td key="constellation" :props="props">{{ props.cols[1].value }}</q-td>
          <q-td
            key="system"
            :props="props"
            @click="copyText(props.cols[2].value)"
            class="cursor-pointer"
            >{{ props.cols[2].value }}</q-td
          >
          <q-td
            key="corp"
            :props="props"
            @click="copyText(props.cols[3].value)"
            class="cursor-pointer"
          >
            <q-avatar size="lg" class="q-pr-xl">
              <img :src="props.row.corp.url" />
            </q-avatar>

            <span :class="standingCheckCorp(props.row)"> {{ props.cols[3].value }}</span>
          </q-td>
          <q-td
            key="alliance"
            :props="props"
            @click="copyText(props.cols[4].value)"
            class="cursor-pointer"
          >
            <q-avatar v-if="props.cols[4].value" size="lg" class="q-pr-xl">
              <img :src="props.row.corp.alliance.url" />
            </q-avatar>
            <span :class="standingCheck(props.row)">
              {{ props.cols[4].value }}</span
            ></q-td
          >
          <q-td
            key="type"
            :props="props"
            @click="copyText(props.cols[5].value)"
            class="cursor-pointer"
            >{{ props.cols[5].value }}</q-td
          >
          <q-td
            key="name"
            :props="props"
            @click="copyText(props.cols[6].value)"
            class="cursor-pointer"
            >{{ props.cols[6].value }}</q-td
          >
          <q-td
            class="cursor-pointer"
            key="timeStamp"
            :props="props"
            @click="timeClick(props.cols[7].value)"
            >{{ props.cols[7].value }}</q-td
          >
          <q-td key="count" :props="props">
            <VueCountDown
              :time="countDownTime(props.cols[8].value)"
              :interval="1000"
              :transform="transformSlotProps"
              v-slot="{ days, hours, minutes, seconds, totalMilliseconds }"
            >
              <span class="text-red" v-if="totalMilliseconds > 0"
                ><span v-if="days > 0">{{ days }} Days -</span> {{ hours }}:{{
                  minutes
                }}:{{ seconds }}
              </span>
              <span v-else> Timer Done</span>
            </VueCountDown>
          </q-td>
          <q-td
            key="status"
            :props="props"
            @click="copyText(props.cols[9].value)"
            class="cursor-pointer"
          >
            <q-chip
              clickable
              :color="chipColor(props.row.station_status_id)"
              :icon="chipIcon(props.row.station_status_id)"
              :label="props.cols[9].value"
            />
          </q-td>
          <q-td key="actions1" :props="props">
            <q-btn
              color="none"
              flat
              rounded
              text-color="positive"
              icon="fa-solid fa-image"
              :href="props.row.timer_image_link"
              target="_blank"
          /></q-td>
          <q-td key="actions2" :props="props">
            <div class="row spacear">
              <div class="col-auto">
                <AddStation :from="3" :station="props.row" />
              </div>
              <div class="col-auto">
                <q-btn
                  v-if="can('finish_move_timer')"
                  flat
                  round
                  padding="none"
                  size="md"
                  color="positive"
                  icon="fa-solid fa-circle-check"
                  @click="timerStatus(1, props.row.id)"
                />
                <q-btn
                  flat
                  round
                  padding="none"
                  size="md"
                  color="negative"
                  icon="fa-solid fa-circle-xmark"
                  @click="timerStatus(2, props.row.id)"
                />
              </div>
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
import axios from "axios";

let can = inject("can");
let store = useMainStore();
const $q = useQuasar();
const AddStation = defineAsyncComponent(() =>
  import("../components/station/AddStation.vue")
);

const VueCountDown = defineAsyncComponent(() => import("../components/countdown/index"));

let search = $ref();

onMounted(async () => {
  if (can("finish_move_timer")) {
    Echo.private("rcmovesheet")
      .listen("RcMoveUpdate", (e) => {
        if (e.flag.message != null) {
          store.updateStationNotification(e.flag.message);
        }
      })
      .listen("RcMoveDelete", (e) => {
        store.deleteStationNotification(e.flag.id);
      });

    await store.getStationData();
  } else {
    Echo.private("rcmovesheet")
      .listen("RcMoveUpdate", (e) => {
        if (e.flag.message != null) {
          if (e.flag.message.added_by_user_id == this.user_id) {
            store.updateStationNotification(e.flag.message);
          }
        }
      })
      .listen("RcMoveDelete", (e) => {
        store.deleteStationNotification(e.flag.id);
      });
    store.getStationDataByUserId();
  }

  setTitle();
});

onBeforeUnmount(async () => {
  Echo.leave("finsish_move_timer");
  Echo.leave("RcMoveDelete");
});

let setTitle = () => {
  if (can("finish_move_timer")) {
    document.title = "Evestuff - Timers to move";
  } else {
    document.title = "Evestuff - Add Timer";
  }
};

let transformSlotProps = (props) => {
  const formattedProps = {};

  Object.entries(props).forEach(([key, value]) => {
    formattedProps[key] = value < 10 ? `0${value}` : String(value);
  });

  return formattedProps;
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

let copyText = (text) => {
  copyToClipboard(text).then(() => {
    $q.notify({
      type: "info",
      message: text + " copied to your clipboard",
    });
  });
};

let chipColor = (statusId) => {
  if (statusId == 1) {
    return "success";
  }
  if (statusId == 2) {
    return "primary";
  }
  if (statusId == 3) {
    return "#FF5EEA";
  }
  if (statusId == 4 || statusId == 11 || statusId == 13) {
    return "orange";
  }
  if (statusId == 5) {
    return "indigo accent-4";
  }
  if (statusId == 6) {
    return "primary";
  }
  if (statusId == 7) {
    return "red";
  }
  if (statusId == 8 || statusId == 9) {
    return "warning";
  }
  if (statusId == 14) {
    return "deep-orange darken-2";
  }
};

let chipIcon = (statusId) => {
  if (statusId == 1) {
    return "fa-solid fa-plus";
  }
  if (statusId == 2) {
    return "fa-solid fa-route";
  }
  if (statusId == 3) {
    return "fa-solid fa-hand-fist";
  }
  if (statusId == 4) {
    return "fa-solid fa-thumbs-up";
  }
  if (statusId == 5 || statusId == 13) {
    return "fa-solid fa-clock";
  }
  if (statusId == 6) {
    return "fa-solid fa-life-ring";
  }
  if (statusId == 7) {
    return "fa-solid fa-dumpster-fire";
  }
  if (statusId == 8) {
    return "fa-solid fa-shield-halved";
  }
  if (statusId == 9) {
    return "fa-solid fa-house-chimney-crack";
  }
  if (statusId == 11) {
    return "fa-solid fa-toolbox";
  }
  if (statusId == 14) {
    return "fa-solid fa-anchor";
  }
};

let countDownTime = (time) => {
  const utcTime = new Date(time + "Z");
  const currentTimestamp = Date.now();
  const timeDiff = utcTime.getTime() - currentTimestamp;
  return timeDiff;
};

let timeClick = (time) => {
  var str = time.replace(/\s+/g, "");
  str = str.replace(/[:]/g, "");
  str = str.replace(/[-]/g, "");
  str = str.substring(2);

  copyToClipboard(str).then(() => {
    $q.notify({
      type: "info",
      message: "Absolute time copied to your clipboard",
    });
  });
};

let timerStatus = async (status, id) => {
  var request = {
    status: status,
  };

  await axios({
    method: "put", //you can set what request you want to be
    url: "api/timer/statusupdate/" + id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let pagination = $ref({
  sortBy: "time",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let columns = $ref([
  {
    name: "region",
    align: "center",
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
    sortable: false,
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
    sortable: false,
    filter: true,
  },
  {
    name: "corp",
    align: "left",
    classes: "text-no-wrap",
    label: "Corp",
    field: (row) => row.corp.ticker,
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
    field: (row) => {
      if (row.alliance_id > 0) {
        return row.alliance.ticker;
      } else {
        return "";
      }
    },
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "type",
    align: "left",
    required: true,
    label: "Type",
    classes: "text-no-wrap",
    field: (row) => row.item.item_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "name",
    label: "Name",
    classes: "text-no-wrap",
    sortable: false,
    filter: false,
    field: (row) => row.name,
    format: (val) => `${val}`,
  },
  {
    name: "timeStamp",
    label: "Time",
    classes: "text-no-wrap",
    align: "center",
    sortable: false,
    field: (row) => row.out_time,
    format: (val) => `${val}`,
  },
  {
    name: "count",
    label: "Countdown",
    align: "center",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.out_time,
    format: (val) => `${val}`,
  },

  {
    name: "status",
    label: "Status",
    align: "right",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.status.name,
    format: (val) => {
      return val.replace("Upcoming - ", "");
    },
  },
  {
    name: "actions1",
    label: "",
    align: "right",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.id,
    format: (val) => `${val}`,
  },
  {
    name: "actions2",
    label: "",
    align: "right",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.id,
    format: (val) => `${val}`,
  },
]);

let notDataLableText = $computed(() => {
  if (can("finish_move_timer")) {
    return "No timers found";
  } else {
    return "No timers found. You can only see timers you created.";
  }
});

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableMoveRC
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
