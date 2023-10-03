<template>
  <div class="q-pl-md">
    <q-btn color="primary" label="Add Tower" @click="showAddTower = true" />
    <q-dialog
      class="myRoundTop"
      max-width="700px"
      min-width="700px"
      v-model="showAddTower"
      persistent
      @before-hide="close()"
    >
      <q-card class="myRoundTop" flat style="width: 700px; max-width: 80vw">
        <q-card-section class="bg-primary text-center">
          <h5 class="no-margin">Add Tower</h5>
        </q-card-section>
        <q-card-section class="q-py-xs">
          <q-select
            v-model="pickedType"
            :options="typeList"
            option-value="value"
            @filter="filterFnTypeFinish"
            @filter-abort="abortFilterFn"
            option-label="text"
            input-debounce="0"
            label="Structure Type"
            filled
            clearable
            use-input
          />
        </q-card-section>
        <q-card-section class="q-py-xs">
          <div class="row q-gutter-xs">
            <div class="col">
              <q-select
                v-model="pickedSystem"
                :options="systemList"
                option-value="value"
                @filter="filterFnSystemFinish"
                @filter-abort="abortFilterFn"
                option-label="text"
                input-debounce="0"
                label="System Name"
                filled
                clearable
                @update:model-value="getMoon"
                use-input
              />
            </div>
            <div class="col">
              <q-select
                v-model="pickedMoon"
                :options="moonList"
                option-value="value"
                @filter="filterFnMoonFinish"
                @filter-abort="abortFilterFn"
                option-label="text"
                input-debounce="0"
                label="Moon"
                filled
                clearable
                :disable="store.moonList.length > 0 ? false : true"
                @update:model-value="getMoon"
                use-input
              />
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <div class="row flex-center">
            <div class="col-auto">
              <q-select
                v-model="corpSelect"
                :options="tickerlistFilter"
                option-value="value"
                option-label="text"
                label="Corp Ticker"
                use-input
                clearable
                filled
                @filter="tickerFilterStart"
              />
            </div>
            <div class="col-auto">
              <AddMissingCorp
                v-if="checkIfMissingCorp"
                class="pt-3 pl-1"
                @missingCorpDone="setTicker()"
              ></AddMissingCorp>
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <q-card-section>
            <span class="text-subtitle1 text-bold"> Status Type</span>
            <q-option-group
              v-model="timerTypePick"
              color="secondary"
              :options="timerTypeOptions"
              inline
            />
          </q-card-section>
        </q-card-section>
        <q-card-actions align="center">
          <q-btn
            color="positive"
            label="Submit"
            @click="submit()"
            rounded
            v-close-popup
          />
          <q-btn label="Close" rounded color="negative" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import axios from "axios";
let store = useMainStore();
let can = inject("can");

const emit = defineEmits(["missingCorpDone"]);
const AddMissingCorp = defineAsyncComponent(() =>
  import("../station/AddMissingCorp.vue")
);

let showAddTower = $ref(false);

onMounted(async () => {
  await store.getTowerFilter();
  await store.getTickList();
});

onBeforeUnmount(async () => {});

let abortFilterFn = () => {};

let pickedType = $ref([]);
let timerTypePick = $ref({});
let timerTypeOptions = $ref([
  {
    label: "Anchoring",
    value: 3,
  },
  {
    label: "Anchored",
    value: 9,
  },
  {
    label: "Onine",
    value: 4,
  },
  {
    label: "Reffed",
    value: 5,
  },
]);
let typeText = $ref();
let typeList = $computed(() => {
  if (typeText) {
    return store.towerTypes.filter((d) => d.text.toLowerCase().indexOf(typeText) > -1);
  }
  return store.towerTypes;
});

let filterFnTypeFinish = (val, update, abort) => {
  update(() => {
    typeText = val.toLowerCase();
  });
};

let pickedSystem = $ref([]);
let systemText = $ref();
let systemList = $computed(() => {
  if (systemText) {
    return store.systemlist.filter((d) => d.text.toLowerCase().indexOf(systemText) > -1);
  }
  return store.systemlist;
});

let filterFnSystemFinish = (val, update, abort) => {
  update(() => {
    systemText = val.toLowerCase();
  });
};

let pickedMoon = $ref([]);
let moonText = $ref();
let moonList = $computed(() => {
  if (moonText) {
    return store.moonList.filter((d) => d.text.toLowerCase().indexOf(moonText) > -1);
  }
  return store.moonList;
});

let filterFnMoonFinish = (val, update, abort) => {
  update(() => {
    moonText = val.toLowerCase();
  });
};

let corpSelect = $ref();

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

let checkIfMissingCorp = $computed(() => {
  if (tickerlistFilter.length == 0) {
    return true;
  }
  return false;
});

let getMoon = () => {
  store.getMoonList(pickedSystem.value);
};

let setTicker = async () => {
  await store.getTickList();
  tickerText = [];
  corpSelect = {
    text: store.missingCorpTick,
    value: store.missingCorpID,
  };
};

let submit = async () => {
  var request = {
    corp_id: corpSelect.value,
    item_id: pickedType.value,
    moon_id: pickedMoon.value,
    tower_status_id: timerTypePick,
  };

  await axios({
    method: "post",
    url: "api/towerrecords",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
let close = () => {
  pickedType = {};
  timerTypePick = [];
  typeText = null;
  pickedSystem = [];
  systemText = null;
  pickedMoon = [];
  moonText = null;
  corpSelect = null;
  tickerText = null;
};
</script>

<style lang="scss"></style>
