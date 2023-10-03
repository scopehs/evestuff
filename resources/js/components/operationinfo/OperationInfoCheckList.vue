<template>
  <div>
    <q-btn :color="stageColor" :label="stageTitle" rounded>
      <q-circular-progress
        show-value
        class="q-ml-xs"
        :value="progressCount"
        :min="0"
        :max="progressMax"
        rounded
        font-size="0.5rem"
        size="sm"
        color="light-blue"
      >
        {{ progressCount }}/{{ progressMax }} </q-circular-progress
      ><q-menu class="myRoundTop">
        <q-card class="myRoundTop">
          <q-card-section :class="stageBgColor" class="text-h5 text-center">
            <h5 class="no-margin">{{ cardTitle }}</h5>
          </q-card-section>
          <q-tab-panels v-model="stage" animated>
            <q-tab-panel name="planning">
              <div class="column q-gutter-none text-webway">
                <OperationinfocheckListTickBox
                  title="Fourm OP Posted"
                  storeName="planing_op_posted"
                  tagName="PlanningPosted"
                />
                <OperationinfocheckListTickBox
                  title="OP Pre-Pinged"
                  storeName="planing_op_pre_ping"
                  tagName="PrePing"
                />
                <OperationinfocheckListTickBox
                  title="Recon Informed"
                  storeName="planing_op_recon_alerted"
                  tagName="ReconAlerted"
                />
                <OperationinfocheckListTickBox
                  title="Capaital FC's Found"
                  storeName="planing_op_capital_fc_found"
                  tagName="CapitalFCFound"
                />
                <OperationinfocheckListTickBox
                  title="FC's Found"
                  storeName="planing_op_fc_found"
                  tagName="FCFound"
                />
                <OperationinfocheckListTickBox
                  title="Doctrines decided"
                  storeName="planing_op_doctromes_decoded"
                  tagName="DoctrinesDecided"
                />
                <OperationinfocheckListTickBox
                  title="Allies Informed"
                  storeName="planing_op_allies_infored"
                  tagName="AlliesInformed"
                />
              </div>
              <q-slide-transition>
                <q-card-actions v-if="planningCount == 7" align="center">
                  <q-btn color="positive" rounded label="Next" @click="nextStage(2)" />
                </q-card-actions>
              </q-slide-transition>
            </q-tab-panel>
            <q-tab-panel name="formup">
              <div class="column q-gutter-none text-webway">
                <OperationinfocheckListTickBox
                  title="FC's ready and informed"
                  storeName="form_op_fc_ready"
                  tagName="FCReady"
                />
                <OperationinfocheckListTickBox
                  title="Capital FC's ready and informed"
                  storeName="form_op_capital_fc_ready"
                  tagName="CapitalFCReady"
                />
                <OperationinfocheckListTickBox
                  title="Recon Online"
                  storeName="form_op_recon_ready"
                  tagName="ReconReady"
                />
                <OperationinfocheckListTickBox
                  title="Scouts Onlined"
                  storeName="form_op_scouts_ready"
                  tagName="ScoutsReady"
                />
                <OperationinfocheckListTickBox
                  title="GSOL Online"
                  storeName="form_op_gsol_ready"
                  tagName="GSOLReady"
                />
                <OperationinfocheckListTickBox
                  title="Black Hand Online"
                  storeName="form_op_blackhand_ready"
                  tagName="BlackHandReady"
                />
                <OperationinfocheckListTickBox
                  title="GSFOE Online"
                  storeName="form_op_gsfoe_ready"
                  tagName="GSFOEReady"
                />
                <OperationinfocheckListTickBox
                  title="Allies Confirmed"
                  storeName="form_op_allies_ready"
                  tagName="AlliesReady"
                />
              </div>
              <q-slide-transition>
                <q-card-actions v-if="formupCount == 8" align="center">
                  <q-btn color="positive" rounded label="Next" @click="nextStage(3)" />
                </q-card-actions>
              </q-slide-transition>
            </q-tab-panel>

            <q-tab-panel name="postop">
              <div class="column q-gutter-none text-webway">
                <OperationinfocheckListTickBox
                  title="Post-Op Debrief done"
                  storeName="post_op_defrief_done"
                  tagName="PostOpDebriefDone"
                />
                <OperationinfocheckListTickBox
                  title="Scouts Stood Down"
                  storeName="post_op_scouts_done"
                  tagName="ScoutsDone"
                />
                <OperationinfocheckListTickBox
                  title="Recon Stood Down"
                  storeName="post_op_recon_done"
                  tagName="ReconDone"
                />
                <OperationinfocheckListTickBox
                  title="FC's debriefed and stood down"
                  storeName="post_op_fc_done"
                  tagName="FCDone"
                />
                <OperationinfocheckListTickBox
                  title="Coord debriefed"
                  storeName="post_op_coord_done"
                  tagName="CoordDone"
                />
              </div>
              <q-slide-transition>
                <q-card-actions v-if="postopCount == 5" align="center">
                  <q-btn color="negative" rounded label="Close Op" @click="done()" />
                </q-card-actions>
              </q-slide-transition>
            </q-tab-panel>
          </q-tab-panels>
        </q-card> </q-menu
    ></q-btn>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
import axios from "axios";

const OperationinfocheckListTickBox = defineAsyncComponent(() =>
  import("./OperationInfoCheckListTickBox.vue")
);
const store = useMainStore();
let tab = $ref("postop");

let planningCount = $computed(() => {
  return (
    store.operationInfoPage.planing_op_posted +
    store.operationInfoPage.planing_op_pre_ping +
    store.operationInfoPage.planing_op_recon_alerted +
    store.operationInfoPage.planing_op_capital_fc_found +
    store.operationInfoPage.planing_op_fc_found +
    store.operationInfoPage.planing_op_doctromes_decoded +
    store.operationInfoPage.planing_op_allies_infored
  );
});

let formupCount = $computed(() => {
  return (
    store.operationInfoPage.form_op_fc_ready +
    store.operationInfoPage.form_op_capital_fc_ready +
    store.operationInfoPage.form_op_recon_ready +
    store.operationInfoPage.form_op_scouts_ready +
    store.operationInfoPage.form_op_gsol_ready +
    store.operationInfoPage.form_op_blackhand_ready +
    store.operationInfoPage.form_op_gsfoe_ready +
    store.operationInfoPage.form_op_allies_ready
  );
});

let postopCount = $computed(() => {
  return (
    store.operationInfoPage.post_op_defrief_done +
    store.operationInfoPage.post_op_scouts_done +
    store.operationInfoPage.post_op_recon_done +
    store.operationInfoPage.post_op_fc_done +
    store.operationInfoPage.post_op_coord_done
  );
});

let stageValue = $computed(() => {
  return store.operationInfoPage.status_id;
});

let stage = $computed(() => {
  if (stageValue == 1) {
    return "planning";
  } else if (stageValue == 2) {
    return "formup";
  } else if (stageValue == 3) {
    return "postop";
  } else if (stageValue == 4) {
    return "";
  }
});

let cardTitle = $computed(() => {
  if (stageValue == 1) {
    return "Planning";
  } else if (stageValue == 2) {
    return "Pre-OP Form Up";
  } else if (stageValue == 3) {
    return "Post-OP Cool Down";
  } else if (stageValue == 4) {
    return "";
  }
});

let stageTitle = $computed(() =>
  stageValue == 1
    ? "Planning"
    : stageValue == 2
    ? "Form Up"
    : stageValue == 3
    ? "Post Op"
    : stageValue == 4
    ? "No Operation"
    : ""
);

let stageColor = $computed(() => {
  if (stageValue == 1) {
    return "positive";
  } else if (stageValue == 2) {
    return "warning";
  } else if (stageValue == 3) {
    return "negative";
  } else if (stageValue == 4) {
    return "grey";
  }
});

let stageBgColor = $computed(() =>
  stageValue == 1
    ? "bg-positive"
    : stageValue == 2
    ? "bg-warning"
    : stageValue == 3
    ? "bg-negative"
    : stageValue == 4
    ? "bg-grey-10"
    : ""
);

let progressMax = $computed(() => {
  if (stageValue == 1) {
    return 7;
  } else if (stageValue == 2) {
    return 8;
  } else if (stageValue == 3) {
    return 5;
  } else if (stageValue == 4) {
    return 0;
  }
});

let progressCount = $computed(() => {
  if (stageValue == 1) {
    return planningCount;
  } else if (stageValue == 2) {
    return formupCount;
  } else if (stageValue == 3) {
    return postopCount;
  } else if (stageValue == 4) {
    return 0;
  }
});

let nextStage = (newVal) => {
  store.operationInfoPage.status_id = newVal;
  var data = store.operationInfoPage;
  axios({
    method: "put", //you can set what request you want to be
    url: "/api/operationinfopage/" + store.operationInfoPage.id,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let done = () => {
  axios({
    method: "DELETE",
    url: "/api/operationinfosheet/" + store.operationInfoPage.link,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss"></style>
