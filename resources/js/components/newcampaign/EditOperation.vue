<template>
  <div>
    <q-btn
      color="none"
      text-color="warning"
      icon="fa-solid fa-pen-to-square"
      size="xs"
      flat
      round
      @click="open()"
    />
    <q-dialog v-model="confirm" persistent>
      <q-card class="myRoundTop" style="width: 1500px; max-width: 80vw">
        <q-card-section class="bg-primary text-h5 text-center">
          <h4 class="no-margin">Edit your Mulit-Campaign Here</h4>
        </q-card-section>
        <q-card-section>
          <div>
            <q-input
              class="q-mb-md"
              v-model="operation.title"
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
              v-model="campaignPicked"
              :options="campaignPickedEnd"
              label="Region"
              ref="regionDropDown"
              @filter="regionPickStart"
              map-options
              use-input
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

const props = defineProps({
  operation: Object,
});

let campaignPickedText = $ref();
let campaignPicked = $ref([]);
let campaignPickedEnd = $computed(() => {
  if (campaignPickedText) {
    return store.newCampaignsList.filter(
      (d) => d.text.toLowerCase().indexOf(campaignPickedText) > -1
    );
  }
  return store.newCampaignsList;
});

let open = () => {
  campaignPicked = props.operation.campaign.map((c) => c.id);
  confirm = true;
};

let regionPickStart = (val, update, abort) => {
  update(() => {
    campaignPickedText = val.toLowerCase();
    if (systemlistFinishEnd.length > 0 && val) {
      finish_system_id = systemlistFinishEnd[0];
    }
  });
};

let finish_system_id = $ref(null);
let finsishSystemText = $ref();

let systemlistFinishEnd = $computed(() => {
  if (finsishSystemText) {
    return store.newCampaignsList.filter(
      (d) => d.text.toLowerCase().indexOf(finsishSystemText) > -1
    );
  }
  return store.newCampaignsList;
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
  var request = {
    OpID: props.operation.id,
    title: titleText,
    picked: campaignPicked,
  };

  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/editoperation",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(addCampaignClose);
};

let addCampaignClose = () => {
  (titleText = null), (campaignPicked = []);
};
</script>

<style lang="scss"></style>
