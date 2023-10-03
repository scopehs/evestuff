<template>
  <div>
    <q-btn
      color="primary"
      icon="fa-solid fa-circle-info"
      padding="none"
      round
      flat
      @click="openStationInfo()"
    />
  </div>
  <q-dialog v-model="stationInfo" persistent>
    <q-card class="myRoundTop" style="width: 500px">
      <q-card-section class="bg-primary text-h5 text-center">
        <span class="no-margin">{{ props.station.name }}</span>
      </q-card-section>
      <q-card-section>
        <div class="column">
          <div>
            <div class="row justify-between">
              <div class="co-autol">
                Cored: <strong :class="textcolor"> {{ core }} </strong>
              </div>
              <div class="col-auto" v-if="showLinkButton">
                <q-btn
                  outline
                  rounded
                  class="myOutLineButton"
                  size="sm"
                  color="secondary"
                  label="View On Recon Tool"
                  @click="openRecon()"
                />
              </div>
            </div>
          </div>
          <div>
            Last Updated: {{ timeAgo }}
            <q-btn
              v-if="can('request_recon_task') && !taskFlag() && isMoreThanAMonthOld"
              outline
              rounded
              class="myOutLineButton"
              size="sm"
              color="secondary"
              label="Request Update"
              @click="taskRequest()"
            />
            <q-chip
              outline
              rounded
              class="myOutLineButton"
              size="sm"
              color="secondary"
              label="Request Made"
              v-if="can('request_recon_task') && taskFlag()"
            />
          </div>
          <div>
            <div v-if="showfit()">
              <q-chip small color="red" class="" v-if="props.station.r_anti_cap == 1">
                anti cap
              </q-chip>
              <q-chip small color="red" v-if="props.station.r_anti_subcap == 1">
                anti subcap
              </q-chip>
              <q-chip small color="red" v-if="props.station.r_dooms_day == 1">
                dooms day
              </q-chip>
              <q-chip small color="red" v-if="props.station.r_point_defense == 1">
                point defense
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_biochemical == 1">
                biochemical
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_capital_shipyard == 1">
                capital shipyard
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_cloning == 1">
                cloning
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_composite == 1">
                composite
              </q-chip>

              <q-chip small color="red" v-if="props.station.r_guide_bombs == 1">
                guide bombs
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_hyasyoda == 1">
                hyasyoda
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_hybrid == 1">
                hybrid
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_invention == 1">
                invention
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_manufacturing == 1">
                manufacturing
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_moon_drilling == 1">
                moon drilling
              </q-chip>

              <q-chip small color="blue" v-if="props.station.r_reprocessing == 1">
                reprocessing
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_research == 1">
                research
              </q-chip>
              <q-chip
                small
                color="blue"
                v-if="props.station.r_supercapital_shipyard == 1"
              >
                supercapital shipyard
              </q-chip>
              <q-chip small color="blue" v-if="props.station.r_t2_rigged == 1">
                t2 rigged
              </q-chip>
            </div>
            <div v-if="!showfit()">No Fit Info</div>
          </div>
          <div>
            <q-table
              class="myStationItemsTable myRound bg-webBack"
              :rows="props.station.fit"
              :columns="columns"
              table-class=" text-webway"
              table-header-class=" text-weight-bolder"
              row-key="id"
              no-data-label="No Fit"
              dark
              :style="{ maxHeight: `${h}` }"
              dense
              ref="tableRef"
              rounded
              flat
              hide-bottom
              hide-header
              v-model:pagination="pagination"
            >
              <template v-slot:body-cell-icon="props">
                <q-td class="q-pa-none" :props="props">
                  <q-avatar class="q-pa-none">
                    <img :src="url(props.row)" class="q-pa-none" />
                  </q-avatar>
                </q-td>
              </template>
            </q-table>
          </div>
        </div>
      </q-card-section>
      <q-card-actions align="between">
        <q-btn
          v-if="showfit()"
          rounded
          outline
          color="info"
          label="Copy Fit"
          @click="copyFit()"
        />
        <q-btn color="negative" label="Close" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { inject, defineAsyncComponent } from "vue";
import { useQuasar, copyToClipboard } from "quasar";
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const $q = useQuasar();
let can = inject("can");
const props = defineProps({
  station: Object,
});

// const SoloCampaginWebWay = defineAsyncComponent(() =>
//   import("../components/operations/SoloCampaginWebWay.vue")
// );
let pagination = $ref({
  sortBy: "slot_value",
  descending: true,
  page: 1,
  rowsPerPage: 0,
});
let fitted = $ref(false);

let openStationInfo = () => {
  stationInfo = true;
  checkfit();
};

let core = $computed(() => {
  if (props.station.r_cored == 1) {
    return "Yes";
  } else {
    return "No";
  }
});

let textcolor = $computed(() => {
  if (core == "Yes") {
    return "text-green";
  } else {
    return "text-red";
  }
});

let checkfit = () => {
  if (props.station.r_fitted == "Fitted") {
    fitted = true;
  }
};

let showfit = () => {
  if (fitted == true) {
    return true;
  } else {
    return false;
  }
};

let showLinkButton = $computed(() => {
  if (can("request_recon_task") && props.station.r_research != null) {
    return true;
  }
  return false;
});

let taskFlag = () => {
  if (props.station.system.task_flag) {
    return true;
  } else {
    return false;
  }
};

let openRecon = () => {
  var url = "https://recon.gnf.lt/structures/" + props.station.r_hash + "/view";
  var win = window.open(url, "_blank");
  win.focus();
};

let url = (item) => {
  return "https://images.evetech.net/types/" + item.id + "/icon";
};

let copyFit = () => {
  copyToClipboard(props.station.fit_text).then(() => {
    $q.notify({
      type: "info",
      message: "fit copied to your clipboard",
    });
  });
};

let taskRequest = () => {
  var request = {
    system_name: props.station.system.system_name,
    system_id: props.station.system_id,
    station_id: props.station.id,
    structure_name: props.station.name,
    username: store.user_name,
    show_on_main: props.station.show_on_main,
    show_on_chill: props.show_on_chill,
  };
  axios({
    method: "post", //you can set what request you want to be
    url: "api/taskrequest",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  $q.notify({
    type: "info",
    message: "Request Sent",
  });
};

let isMoreThanAMonthOld = $computed(() => {
  const date = new Date(props.station.r_updated_at);
  const currentDate = new Date();
  const diffInMonths = (currentDate.getFullYear() - date.getFullYear()) * 12;
  const monthDiff = currentDate.getMonth() - date.getMonth();
  const totalMonthDiff = diffInMonths + monthDiff;
  return totalMonthDiff > 1;
});

let timeAgo = $computed(() => {
  if (props.station.r_updated_at != null) {
    const date = new Date(props.station.r_updated_at);
    const seconds = Math.floor((new Date() - date) / 1000);
    const interval = Math.floor(seconds / 31536000);

    if (interval >= 1) {
      return `${interval} year${interval === 1 ? "" : "s"} ago`;
    }

    const interval2 = Math.floor(seconds / 2592000);
    if (interval2 >= 1) {
      return `${interval2} month${interval2 === 1 ? "" : "s"} ago`;
    }

    const interval3 = Math.floor(seconds / 86400);
    if (interval3 >= 1) {
      return `${interval3} day${interval3 === 1 ? "" : "s"} ago`;
    }

    const interval4 = Math.floor(seconds / 3600);
    if (interval4 >= 1) {
      return `${interval4} hour${interval4 === 1 ? "" : "s"} ago`;
    }

    const interval5 = Math.floor(seconds / 60);
    if (interval5 >= 1) {
      return `${interval5} minute${interval5 === 1 ? "" : "s"} ago`;
    }

    return `${seconds} second${seconds === 1 ? "" : "s"} ago`;
  } else {
    return "Never";
  }
});

let columns = $ref([
  {
    name: "icon",
    align: "left",
    required: false,
    label: "",
    classes: "text-no-wrap q-pa-none",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "item",
    required: true,
    align: "left",
    label: "Items",
    classes: "text-no-wrap ",
    field: (row) => row.item_name,
    format: (val) => `${val}`,
    sortable: false,
  },
]);

let stationInfo = $ref(false);
let h = $computed(() => {
  let mins = 325;
  let window = store.size.height;
  return window - mins + "px";
});
</script>
