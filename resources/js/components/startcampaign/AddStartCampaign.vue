<template>
  <div>
    <q-btn
      color="positive"
      class="myOutLineButton"
      label="Add"
      push
      size="xs"
      rounded
      @click="confirm = true"
    />
    <q-dialog v-model="confirm" persistent>
      <q-card class="myRoundTop" style="width: 1500px; max-width: 80vw">
        <q-card-section class="bg-primary text-h5 text-center">
          <h4 class="no-margin">Make your Inital-Campaign Here</h4>
        </q-card-section>
        <q-card-section>
          <div>
            <q-input
              class="q-mb-md"
              v-model="titleText"
              type="text"
              label="Title"
              rounded
              dense
              outlined=""
              autofocus
            />
          </div>
          <div>
            <q-select
              rounded
              dense
              standout
              input-debounce="0"
              label-color="webway"
              option-value="value"
              option-label="text"
              v-model="constellationPicked"
              :options="constellationPickedEnd"
              label="Constellation"
              ref="constellationDropDown"
              @filter="constellationPickStart"
              map-options
              use-input
              clearable
              use-chips
              multiple
            >
              <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                <q-item v-bind="itemProps" :class="listColor(opt)">
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
                  :color="chipColor(scope.opt)"
                  text-color="white"
                  class="q-ma-none"
                >
                  <span class="text-xs"> {{ scope.opt.text }} </span>
                </q-chip>
              </template></q-select
            >
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            rounded
            color="primary"
            label="Submit"
            @click="addCampaignDone"
            v-close-popup
          />
          <q-btn
            rounded
            color="negative"
            label="Close"
            @click="addCampaignClose"
            v-close-popup
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let confirm = $ref(false);
let titleText = $ref();

let constellationPickedText = $ref();
let constellationPicked = $ref([]);
let constellationPickedEnd = $computed(() => {
  if (constellationPickedText) {
    return store.constellationlist.filter(
      (d) => d.text.toLowerCase().indexOf(constellationPickedText) > -1
    );
  }
  return store.constellationlist;
});

let constellationPickStart = (val, update, abort) => {
  update(() => {
    constellationPickedText = val.toLowerCase();
    if (systemlistFinishEnd.length > 0 && val) {
      finish_system_id = systemlistFinishEnd[0];
    }
  });
};

let finish_system_id = $ref(null);
let finsishSystemText = $ref();

let systemlistFinishEnd = $computed(() => {
  if (finsishSystemText) {
    return store.constellationlist.filter(
      (d) => d.text.toLowerCase().indexOf(finsishSystemText) > -1
    );
  }
  return store.constellationlist;
});

let chipColor = (item) => {
  if (item.standing < 0) {
    return "red";
  } else if (item.standing > 0) {
    return "blue";
  }
  return "webChip";
};

let listColor = (item) => {
  if (item.standing >= 2) {
    return "bg-blue";
  } else if (item.standing == 1) {
    return "bg-red";
  }
};

let addCampaignDone = async () => {
  await axios({
    method: "POST",
    url: "/api/startcampaigns/" + titleText,
    withCredentials: true,
    data: constellationPicked,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  store.getStartCampaigns();
  store.getStartCampaignJoinData();
  addCampaignClose();
};

let addCampaignClose = () => {
  (titleText = null), (constellationPicked = []);
};
</script>

<style lang="scss"></style>
