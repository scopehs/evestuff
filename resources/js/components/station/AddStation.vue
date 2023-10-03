<template>
  <div class="q-pa-none">
    <q-btn
      v-if="props.from == 1"
      color="primary"
      icon="fa-solid fa-plus"
      label="Add Timer"
      @click="showAddStation = true"
    />
    <q-btn
      v-if="props.from == 2"
      color="primary"
      icon="fa-solid fa-clock"
      round
      padding="none"
      flat
      @click="showAddStation = true"
    />
    <q-btn
      v-if="props.from == 3"
      color="primary"
      icon="fa-solid fa-pen-to-square"
      round
      padding="none"
      flat
      @click="showAddStation = true"
    />
    <q-dialog
      class="myRoundTop"
      @before-show="open()"
      max-width="700px"
      v-model="showAddStation"
      persistent
      @before-hide="close()"
    >
      <q-card class="myRoundTop" max-width="700px">
        <q-tab-panels
          v-model="panel"
          animated
          class="shadow-2 rounded-borders myStationPannel"
        >
          <q-tab-panel class="q-pa-none" name="addName">
            <q-card class="my-card" flat>
              <img src="https://i.imgur.com/1ASFxRr.gif" />
              <q-card-section>
                <div class="text-h6 text-center">Enter Structure Name</div>
              </q-card-section>
              <q-card-section>
                <q-input
                  v-model="stationName"
                  type="text"
                  label="Structure Name"
                  rounded
                  label-color="webway"
                  standout
                />
              </q-card-section>
              <q-card-actions horizontal align="right">
                <q-btn
                  rounded
                  color="primary"
                  :disable="addNameDisable"
                  label="Submit"
                  @click="stationNameAdd()"
                />
                <q-btn rounded color="negative" label="Close" v-close-popup />
              </q-card-actions>
            </q-card>
          </q-tab-panel>

          <q-tab-panel class="q-pa-none myRoundTop" name="details">
            <q-card class="myRoundTop" flat>
              <q-card-section class="bg-primary text-center">
                <h5 class="no-margin">Enter Details for {{ stationName }}</h5>
              </q-card-section>
              <q-card-section class="q-py-xs">
                <q-input
                  v-model="stationName"
                  type="text"
                  label="Structure Name"
                  label-color="webway"
                  rounded
                  outlined
                  hide-bottom-space
                  standout
                  :readonly="stationSetReadOnly()"
                />
              </q-card-section>
              <q-card-section class="q-py-xs">
                <q-select
                  v-model="structureSelect"
                  :options="structureListFilter"
                  :autofocus="focusStructure"
                  option-value="value"
                  option-label="text"
                  label="Structure Type"
                  rounded
                  outlined
                  use-input
                  clearable
                  @filter="structureFilterStart"
                  :readonly="setReadOnly()"
                />
              </q-card-section>
              <q-card-section class="q-py-xs">
                <div class="row">
                  <div class="col-6">
                    <q-select
                      v-model="systemSelect"
                      :options="systemListFilter"
                      option-value="value"
                      option-label="text"
                      label="System Name"
                      outlined
                      rounded
                      use-input
                      clearable
                      @filter="systemFilterStart"
                      :readonly="setReadOnly()"
                    />
                  </div>
                  <div class="col-6 flex justify-center">
                    <q-select
                      v-model="corpSelect"
                      :options="tickerlistFilter"
                      option-value="value"
                      option-label="text"
                      label="Corp Ticker"
                      outlined
                      rounded
                      use-input
                      clearable
                      @filter="tickerFilterStart"
                      :readonly="setReadOnly()"
                    />
                    <AddMissingCorp
                      v-if="checkIfMissingCorp"
                      class="pt-3 pl-1"
                      @missingCorpDone="setTicker()"
                    ></AddMissingCorp>
                  </div>
                </div>
              </q-card-section>
              <q-separator inset color="webway" />
              <q-card-section>
                <span class="text-subtitle1 text-bold"> Timer Type</span>
                <q-option-group
                  v-model="timerTypePick"
                  color="secondary"
                  :options="timerTypeOptions"
                  inline
                />
              </q-card-section>
              <q-separator inset color="webway" />
              <q-card-section>
                <span class="text-subtitle1 text-bold"> Image Link</span>
                <q-img :src="imageUrl" alt="My Image" />
                <q-input
                  class="q-pt-xs"
                  v-model="imgLink"
                  type="text"
                  label="Selected Items Screen Shot"
                  rounded
                  hide-bottom-space
                  outlined
                  label-color="webway"
                  standout
                />
              </q-card-section>
              <q-card-section class="q-pt-none q-pb-none">
                Enter Reinforced Until Timer enter
                <q-input
                  v-model="timer"
                  mask="####.##.## ##:##:##"
                  type="text"
                  :error-message="timeErrorMessage"
                  ref="fieldRef"
                  label="Reinforced unit YYYY.MM.DD hh:mm:ss"
                  rounded
                  outlined
                  :error="dateError"
                  label-color="webway"
                  standout
                />
              </q-card-section>
              <q-card-actions align="right">
                <q-btn
                  v-if="props.from != 3"
                  :disable="!isFormCorrect"
                  color="positive"
                  label="Submit"
                  @click="submit()"
                  rounded
                  v-close-popup
                />
                <q-btn
                  v-if="props.from == 3"
                  :disable="!isFormCorrect"
                  color="positive"
                  label="Update"
                  @click="submit()"
                  rounded
                  v-close-popup
                />
                <q-btn label="Close" rounded color="negative" v-close-popup />
              </q-card-actions>
            </q-card>
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { inject, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
import myImage from "@/img/info.png";

let store = useMainStore();
let can = inject("can");
const emit = defineEmits(["missingCorpDone"]);
const props = defineProps({
  from: { type: Number, default: 1 },
  station: { type: Object, required: false },
});

const AddMissingCorp = defineAsyncComponent(() => import("./AddMissingCorp.vue"));

let imageUrl = $ref(myImage);
let showAddStation = $ref(false);
let panel = $ref("addName");
let stationName = $ref(null);
let systemSelect = $ref();
let corpSelect = $ref();
let timerTypePick = $ref({});
let imgLink = $ref();
let timer = $ref("");
let structureSelect = $ref();
let state = $ref();
let stationId = $ref();

let timerTypeOptions = $ref([
  {
    label: "Anchoring",
    value: 14,
  },
  {
    label: "Armor",
    value: 5,
  },
  {
    label: "Hull",
    value: 13,
  },
]);

let open = async () => {
  if (props.from == 2) {
    panel = "details";
    state = 3;

    corpSelect = {
      text: props.station.corp.ticker,
      value: props.station.corp_id,
    };
    systemSelect = {
      text: props.station.system.system_name,
      value: props.station.system_id,
    };
    structureSelect = {
      text: props.station.item.item_name,
      value: props.station.item_id,
    };
    stationName = props.station.name;
    stationId = props.station.id;
  }

  if (props.from == 3) {
    panel = "details";
    state = 4;

    corpSelect = {
      text: props.station.corp.ticker,
      value: props.station.corp_id,
    };
    systemSelect = {
      text: props.station.system.system_name,
      value: props.station.system_id,
    };
    structureSelect = {
      text: props.station.item.item_name,
      value: props.station.item_id,
    };
    stationName = props.station.name;
    stationId = props.station.id;

    timer = convertTimeBack();
    timerTypePick = props.station.status.id;
    imgLink = props.station.timer_image_link;
  }
  await store.getSystemList();
  await store.getTickList();
  await store.getStructureList();
};

let convertTimeBack = () => {
  const outputString = props.station.out_time.replace(/-/g, ".");
  return outputString;
};

let close = () => {
  panel = "addName";
  stationName = null;
  systemSelect = null;
  corpSelect = null;
  timerTypePick = {};
  imgLink = null;
  timer = "";
  structureSelect = null;
  state = null;
  stationId = null;
};

let structureText = $ref();
let structureListFilter = $computed(() => {
  if (structureText) {
    return store.structurelist.filter(
      (d) => d.text.toLowerCase().indexOf(structureText) > -1
    );
  }
  return store.structurelist;
});

let structureFilterStart = (val, update, abort) => {
  update(() => {
    structureText = val.toLowerCase();
    if (structureListFilter.length > 0 && val) {
      structureSelect = structureListFilter[0];
    }
  });
};

let systemText = $ref();
let systemListFilter = $computed(() => {
  if (systemText) {
    return store.systemlist.filter((d) => d.text.toLowerCase().indexOf(systemText) > -1);
  }
  return store.systemlist;
});

let systemFilterStart = (val, update, abort) => {
  update(() => {
    systemText = val.toLowerCase();
    if (systemListFilter.length > 0 && val) {
      systemSelect = systemListFilter[0];
    }
  });
};

let tickerText = $ref();
let tickerlistFilter = $computed(() => {
  if (tickerText) {
    return store.ticklist.filter((d) => d.text.toLowerCase().indexOf(tickerText) > -1);
  }
  return store.ticklist;
});

let tickerFilterStart = (val, update, abort) => {
  update(() => {
    tickerText = val.toLowerCase();
    if (tickerlistFilter.length > 0 && val) {
      corpSelect = tickerlistFilter[0];
    }
  });
};

let setTicker = async () => {
  await store.getTickList();
  tickerText = [];
  corpSelect = {
    text: store.missingCorpTick,
    value: store.missingCorpID,
  };
};

let checkIfMissingCorp = $computed(() => {
  if (tickerlistFilter.length == 0) {
    return true;
  }
  return false;
});

let focusStructure = $computed(() => {
  if (state == 2) {
    return true;
  }
  return false;
});

let stationNameAdd = async () => {
  var request = {
    stationName: stationName,
  };
  await axios({
    method: "put", //you can set what request you want to be
    url: "api/stationname",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((response) => {
    let res = response.data;
    if (res.state == 2) {
      state = 2;
      panel = "details";
    }

    if (res.state == 3) {
      state = 3;
      corpSelect = {
        text: res.corp_ticker,
        value: res.corp_id,
      };
      systemSelect = {
        text: res.system_name,
        value: res.system_id,
      };
      structureSelect = {
        text: res.structure_name,
        value: res.structure_id,
      };
      stationName = res.station_name;
      stationId = res.station_id;

      panel = "details";
    }
  });
};

let setReadOnly = () => {
  if (state == 3) {
    return true;
  } else {
    return false;
  }
};

let stationSetReadOnly = () => {
  if (state != 4) {
    return true;
  } else {
    return false;
  }
};

let dateError = $computed(() => {
  if (dateCount) {
    if (!isDateValidFormat || !isDateWithinTime) {
      return true;
    }
  }
  return false;
});

let dateCount = $computed(() => {
  if (timer.length == 19) {
    return true;
  }
  return false;
});

let isDateValidFormat = $computed(() => {
  const pattern = /^\d{4}\.\d{2}\.\d{2} \d{2}:\d{2}:\d{2}$/;
  return pattern.test(timer);
});

let isDateWithinTime = $computed(() => {
  if (isDateValidFormat) {
    const start = Date.now();
    const end = start + 5 * 24 * 60 * 60 * 1000;
    const date = new Date(timer);
    const time = date.getTime();
    return time >= start && time <= end;
  }
  return false;
});

let timeErrorMessage = $computed(() => {
  if (!isDateValidFormat) {
    return "Format is incorrect";
  }
  if (!isDateWithinTime) {
    return "Make sure the date is correct";
  }
});

let isFormCorrect = $computed(() => {
  if (
    dateCount &&
    isDateValidFormat &&
    isDateWithinTime &&
    systemSelect &&
    corpSelect &&
    timerTypePick > 0 &&
    imgLink &&
    structureSelect
  ) {
    return true;
  } else {
    return false;
  }
});

let addNameDisable = $computed(() => {
  if (stationName) {
    return false;
  } else {
    return true;
  }
});

let submit = () => {
  if (state == 2) {
    submitNewStation();
  }
  if (state == 3) {
    updateStation();
  }

  if (state == 4) {
    editStation();
  }
};

let submitNewStation = async () => {
  const inputString = timer.replace(/\./g, "-");

  var request = {
    name: stationName,
    system_id: systemSelect.value,
    corp_id: corpSelect.value,
    item_id: structureSelect.value,
    station_status_id: timerTypePick,
    out_time: inputString,
    timer_image_link: imgLink,
  };

  await axios({
    method: "put", //you can set what request you want to be
    url: "api/stationnew",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let updateStation = async () => {
  const inputString = timer.replace(/\./g, "-");
  var request = {
    station_status_id: timerTypePick,
    out_time: inputString,
    timer_image_link: imgLink,
  };

  await axios({
    method: "put", //you can set what request you want to be
    url: "api/timer/addTimer/" + stationId,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let editStation = async () => {
  const inputString = timer.replace(/\./g, "-");

  var request = {
    name: stationName,
    system_id: systemSelect.value,
    corp_id: corpSelect.value,
    item_id: structureSelect.value,
    station_status_id: timerTypePick,
    out_time: inputString,
    timer_image_link: imgLink,
  };

  await axios({
    method: "put", //you can set what request you want to be
    url: "api/timer/editStation/" + props.station.id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="sass" scoped></style>
