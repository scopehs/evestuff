<template>
  <q-card class="myRoundTop">
    <q-card-section class="bg-primary text-center text-h3"> Setting </q-card-section>
    <q-card-section class="text-h3 text-center"> Regions to Watch </q-card-section>
    <q-card-section class="text-center">
      Stations that are in the selcted regions will be update/added from the Recon Tool.
      Stations, systems, constellation and region which you want to add to watch lists
      need to have there regions selected here.
    </q-card-section>
    <q-card-section>
      <q-select
        rounded
        dense
        standout
        input-debounce="0"
        label-color="webway"
        option-value="value"
        option-label="text"
        v-model="store.stationWatchListPullRegions"
        :options="pickedRegions"
        label="Region To Watch"
        ref="regionDropDown"
        @filter="pickedRegionStart"
        @filter-abort="abortFilterFn"
        map-options
        use-input
        use-chips
        multiple
        @update:model-value="submit"
      >
        <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
          <q-item v-bind="itemProps">
            <q-item-section>
              <q-item-label v-html="opt.text" />
            </q-item-section>
            <q-item-section side>
              <q-toggle :model-value="selected" @update:model-value="toggleOption(opt)" />
            </q-item-section>
          </q-item>
        </template>

        <template v-slot:selected-item="scope">
          <q-chip
            removable
            @remove="scope.removeAtIndex(scope.index)"
            :tabindex="scope.tabindex"
            color="webWay"
            text-color="white"
            class="q-ma-none"
          >
            <span class="text-xs"> {{ scope.opt.text }} </span>
          </q-chip>
        </template></q-select
      >
    </q-card-section>
    <q-separator color="webChip" />
    <q-card-section class="text-center text-h4"> Webway Staging Systems </q-card-section>
    <q-card-section class="text-center">
      Staging systems are used by webway to determine the total number of jumps from a
      system to a destination system.
    </q-card-section>
    <q-card-section>
      <q-select
        rounded
        dense
        standout
        input-debounce="0"
        label-color="webway"
        option-value="value"
        option-label="text"
        v-model="store.webwayStartSystems"
        :options="webwaySystems"
        label="Staging Systems (1DQ is defaulted)"
        ref="regionDropDown"
        @filter="pickedWebwayStart"
        map-options
        use-input
        use-chips
        multiple
        @update:model-value="webwaySubmit"
      >
        <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
          <q-item v-bind="itemProps">
            <q-item-section>
              <q-item-label v-html="opt.text" />
            </q-item-section>
            <q-item-section side>
              <q-toggle :model-value="selected" @update:model-value="toggleOption(opt)" />
            </q-item-section>
          </q-item>
        </template>

        <template v-slot:selected-item="scope">
          <q-chip
            removable
            @remove="scope.removeAtIndex(scope.index)"
            :tabindex="scope.tabindex"
            color="webWay"
            text-color="white"
            class="q-ma-none"
          >
            <span class="text-xs"> {{ scope.opt.text }} </span>
          </q-chip>
        </template></q-select
      >
    </q-card-section>
  </q-card>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();

let regionUpdateText = $ref();
let pickedRegions = $computed(() => {
  if (regionUpdateText) {
    return store.stationWatchListRegionList.filter(
      (d) => d.text.toLowerCase().indexOf(regionUpdateText) > -1
    );
  }
  return store.stationWatchListRegionList;
});

let pickedRegionStart = (val, update, abort) => {
  update(() => {
    regionUpdateText = val.toLowerCase();
  });
};

let abortFilterFn = () => {};

let submit = async () => {
  var request = {
    pull: store.stationWatchListPullRegions,
  };

  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/watchlist/getneededinfo",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let webwaySubmit = async () => {
  var request = {
    system_ids: store.webwayStartSystems,
  };

  await axios({
    method: "post",
    url: "/api/updatewebwaystartsystems",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let webwayUpdateText = $ref();
let webwaySystems = $computed(() => {
  if (webwayUpdateText) {
    return store.systemlist.filter(
      (d) => d.text.toLowerCase().indexOf(webwayUpdateText) > -1
    );
  }
  return store.systemlist;
});

let pickedWebwayStart = (val, update, abort) => {
  update(() => {
    webwayUpdateText = val.toLowerCase();
  });
};
</script>

<style lang="scss"></style>
