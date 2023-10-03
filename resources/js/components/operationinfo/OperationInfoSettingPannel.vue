<template>
  <q-btn
    color="warning"
    icon="fa-solid fa-gears"
    flat
    padding="none"
    @click="showSetting = true"
  />
  <q-dialog v-model="showSetting" @before-show="open()" persistent>
    <q-card class="myRoundTop" style="width: 700px; max-width: 80vw">
      <q-card-section class="bg-primary text-h5 text-center">
        <h5 class="no-margin">
          Globle Setting for Operation - {{ store.operationInfoPage.name }}
        </h5>
      </q-card-section>
      <q-card-section>
        <q-select
          rounded
          dense
          clearable
          standout
          input-debounce="0"
          label-color="webway"
          option-value="id"
          option-label="title"
          v-model="oldOperation"
          :options="campaignList"
          ref="toRegionRef"
          label="Which Hacking Operation would you like to add"
          @filter="filterFnHackingOpFinish"
          @filter-abort="abortFilterFn"
          map-options
          use-input
          hide-selected
          fill-input
        />
        <q-select
          rounded
          class="q-pt-sm"
          dense
          standout
          input-debounce="0"
          label-color="webway"
          option-value="value"
          option-label="text"
          v-model="systemsToUpdate"
          :options="systemFindList"
          @filter="filterFnSystemFinish"
          @filter-abort="abortFilterFn"
          label="Which none hacking Operation Systems would you like to add"
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
              text-color="white"
              class="q-ma-none"
            >
              <span class="text-xs"> {{ scope.opt.text }} </span>
            </q-chip>
          </template>
        </q-select>
        <q-select
          rounded
          class="q-pt-sm"
          dense
          standout
          input-debounce="0"
          label-color="webway"
          option-value="value"
          option-label="text"
          v-model="systemsToUpdateWatched"
          :options="systemFindList"
          @filter="filterFnSystemFinish"
          @filter-abort="abortFilterFn"
          label="Which Systems would you like to see dscan info for"
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
              text-color="white"
              class="q-ma-none"
            >
              <span class="text-xs"> {{ scope.opt.text }} </span>
            </q-chip>
          </template>
        </q-select>
        <q-option-group
          class="q-pt-md"
          v-model="pickedOptions"
          :options="showOptions"
          color="yellow"
          dense
          type="toggle"
        >
          <template v-slot:label="opt">
            <div class="row items-center">
              <transition
                mode="out-in"
                enter-active-class="animate__animated animate__flash"
                leave-active-class="animate__animated animate__flash"
              >
                <span :key="lableKey(opt.value)" :class="labelText(opt.value)">{{
                  hideShow(opt)
                }}</span></transition
              >
            </div>
          </template></q-option-group
        ></q-card-section
      >
      <q-card-actions align="center">
        <q-btn color="positive" label="Update" @click="submit()" rounded v-close-popup />
        <q-btn label="Close" rounded color="negative" v-close-popup /> </q-card-actions
    ></q-card>
  </q-dialog>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let showSetting = $ref(false);
let systemsToUpdate = $ref([]);
let systemsToUpdateWatched = $ref([]);
let oldOperation = $ref();

let campaignText = $ref();

let pickedOptions = $ref([]);
let showOptions = $ref([
  {
    label: "Check List",
    value: "check_list",
  },
  {
    label: "Fleet Table",
    value: "fleet_table",
  },
  {
    label: "Recon List",
    value: "recon_table",
  },
  {
    label: "System Table",
    value: "system_table",
  },
  {
    label: "Watched Table",
    value: "watched_system_table",
  },
]);

let hideShow = (value) => {
  if (pickedOptions.includes(value.value)) {
    return value.label + " - show";
  } else {
    return value.label + " - hide";
  }
};

let lableKey = (value) => {
  let show = 0;
  if (pickedOptions.includes(value)) {
    show = 1;
  } else {
    show = 0;
  }

  return value + " - " + show;
};

let labelText = (value) => {
  if (pickedOptions.includes(value)) {
    return "text-primary";
  } else {
    return "text-negative";
  }
};

let campaignList = $computed(() => {
  if (campaignText) {
    return store.operationInfoOperationList.filter(
      (d) => d.title.toLowerCase().indexOf(campaignText) > -1
    );
  }
  return store.operationInfoOperationList;
});

let filterFnHackingOpFinish = (val, update, abort) => {
  update(() => {
    campaignText = val.toLowerCase();
    if (campaignList.length > 0 && val) {
      oldOperation = campaignList[0];
    }
  });
};

let systemFindText = $ref();
let systemFindList = $computed(() => {
  if (systemFindText) {
    return store.systemlist.filter(
      (d) => d.text.toLowerCase().indexOf(systemFindText) > -1
    );
  }
  return store.systemlist;
});

let filterFnSystemFinish = (val, update, abort) => {
  update(() => {
    systemFindText = val.toLowerCase();
  });
};

let open = () => {
  systemsToUpdate = store.getOperationInfoSystemList;
  pickedOptions = store.getOperationInfoTableStatus;
  systemsToUpdateWatched = store.getOperationInfoWatchedSystemList;
  oldOperation = store.operationInfoOperationList.find(
    (o) => o.id == store.operationInfoPage.operation_id
  );
};

let abortFilterFn = () => {};

let submit = () => {
  let opID = null;
  if (oldOperation) {
    opID = oldOperation.id;
  }
  var request = {
    operation_id: opID,
    systemsToUpdate: systemsToUpdate ?? [],
    systemsToUpdateWatched: systemsToUpdateWatched ?? [],
    show: pickedOptions,
  };

  axios({
    method: "post", //you can set what request you want to be
    url: "/api/operationinfosheet/" + store.operationInfoPage.id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss"></style>
