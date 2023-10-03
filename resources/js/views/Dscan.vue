<template>
  <div class="q-ma-md">
    <div class="row q-gutter-lg">
      <div class="col-9" v-if="show">
        <q-tabs dense="" v-model="tab" class="text-teal">
          <q-tab v-if="showLocalTab" name="local" label="Local" />
          <q-tab name="total" label="Total" />
          <q-tab name="onGrid" label="On Grid" />
          <q-tab name="offGrid" label="Off Grid" />
        </q-tabs>
        <q-tab-panels :style="h" v-model="tab" animated>
          <q-tab-panel name="local"> <DscanLocal /> </q-tab-panel>
          <q-tab-panel name="total">
            <DscanTotal :type="1" />
          </q-tab-panel>
          <q-tab-panel name="onGrid">
            <DscanTotal :type="2" />
          </q-tab-panel>
          <q-tab-panel name="offGrid">
            <DscanTotal :type="3" />
          </q-tab-panel>
        </q-tab-panels>
      </div>
      <div class="col-2">
        <div :class="colClass" class="column">
          <div class="col-auto" v-if="scanLink">
            <q-card class="my-card">
              <div class="col">
                <div class="row justify-between items-center">
                  <div class="col-auto text-h6">System: {{ systemName }}</div>
                  <div class="col-2"><DscanMessage /></div>
                </div>
              </div>
              <div class="col text-h6">Created By: {{ createdBy }}</div>
              <div class="col text-h6">Edited By: {{ updatedBy }}</div>
              <div class="col text-h6">
                Last Updated:
                <VueCountUp
                  class="q-pl-sm"
                  :emit-events="false"
                  :time="countUpTimeMil(age)"
                  :interval="1000"
                  v-slot="{ hours, minutes, seconds }"
                >
                  <span class="text-red" v-if="hours >= 1"
                    >{{ hours }}:{{ minutes }}:{{ seconds }}</span
                  >
                  <span class="text-red" v-else-if="minutes >= 20"
                    >{{ minutes }}:{{ seconds }}</span
                  >
                  <span v-else class="text-primary">{{ minutes }}:{{ seconds }}</span>
                </VueCountUp>
              </div>
            </q-card>
          </div>

          <div class="col-auto text-h6" v-if="!store.dScanIsHistory">
            <q-card class="my-card myRoundTop">
              <q-card-section>
                <q-input
                  v-model="dScanText"
                  type="textarea"
                  label="Paste your Dscan or local here"
                />
              </q-card-section>
              <q-card-actions align="center">
                <q-btn
                  rounded
                  color="primary"
                  label="New"
                  :loading="loading"
                  @click="subScan()"
                />
                <q-btn
                  v-if="scanLink"
                  rounded
                  color="secondary"
                  label="Update"
                  :loading="loading"
                  @click="updateScan()"
                />
                <q-btn
                  v-if="store.dScanHistory.length || store.dScanIsHistory"
                  color="accent"
                  rounded
                  label="Share"
                  @click="clickShare"
                />
              </q-card-actions>
            </q-card>
          </div>
          <div class="col-auto" v-if="store.dScanHistory.length || store.dScanIsHistory">
            <q-card class="my-card myRoundTop overflow-auto" :style="hh">
              <q-card-section class="q-px-none">
                <q-list dense>
                  <q-item clickable v-if="store.dScanIsHistory" @click="clickLive()">
                    Live
                  </q-item>
                  <q-item
                    class="myListNoPadding"
                    clickable
                    @click="clickHistory(list)"
                    :active="isHistoryActive(list.link)"
                    v-for="(list, index) in store.dScanHistory"
                    :key="index"
                  >
                    <DscanHistoryList :list="list" />
                  </q-item>
                </q-list>
              </q-card-section>
            </q-card>
          </div>
        </div>
      </div>
    </div>
    <q-dialog v-model="showESIError" persistent>
      <q-card class="myRoundTop">
        <q-card-section class="myCardHeader bg-warning text-center text-h5">
          Somthing went wrong
        </q-card-section>
        <q-card-section class="row items-center">
          <span class="q-ml-sm"
            >There was a error with the EVE ESI while trying add the local scan. You may
            need to enter the scan again</span
          >
        </q-card-section>
        <q-card-actions align="right">
          <q-btn rounded label="close" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import {
  onMounted,
  onBeforeUnmount,
  defineAsyncComponent,
  inject,
  onBeforeMount,
} from "vue";
import { useQuasar, copyToClipboard } from "quasar";
import { useRoute, useRouter } from "vue-router";
import { useMainStore } from "@/store/useMain.js";

const DscanTotal = defineAsyncComponent(() =>
  import("@/components/dscan/DscanTotal.vue")
);

const DscanLocal = defineAsyncComponent(() =>
  import("@/components/dscan/DscanLocal.vue")
);

const DscanHistoryList = defineAsyncComponent(() =>
  import("@/components/dscan/DscanHistoryList.vue")
);

const DscanMessage = defineAsyncComponent(() =>
  import("@/components/dscan/DscanMessage.vue")
);

const VueCountUp = defineAsyncComponent(() => import("@/components/countup/index"));

let store = useMainStore();
let can = inject("can");

const $q = useQuasar();
let route = useRoute();
let router = useRouter();
let scanLink = $ref(null);
let dScanText = $ref(null);
let loading = $ref(false);
let tab = $ref("total");
let showESIError = $ref(false);

onBeforeMount(() => {}),
  onMounted(async () => {
    document.title = "Evestuff - Dscan";
    scanLink = route.params.link ? route.params.link : null;
    Echo.private("dscanall").listen("DscanAllUpdate", (e) => {
      if (e.flag.flag == 1) {
      }
    });

    await checkDscan();
  });

onBeforeUnmount(() => {
  Echo.leave("dscansolo." + scanLink);
  Echo.leave("dscanall");
});

let checkDscan = async () => {
  if (scanLink) {
    await store.getDscan(scanLink);
    if (!store.dScanIsHistory) {
      Echo.private("dscansolo." + scanLink).listen("dScanSoloUpdate", (e) => {
        if (e.flag.flag == 1) {
          showESIError = true;
        }

        if (e.flag.flag == 2) {
          store.dScanLocalCorp = e.flag.message.corpsTotal;
          store.dScanLocalAlliance = e.flag.message.allianceTotal;
          store.dScanLocalAffiliation = e.flag.message.affiliationTotal;
          store.updateLocalDscan(e.flag.message.soloLocal);
        }

        if (e.flag.flag == 3) {
          store.dScanLocalCorp = e.flag.message;
        }

        if (e.flag.flag == 4) {
          store.dScanLocalAlliance = e.flag.message;
        }

        if (e.flag.flag == 5) {
          store.dScan = e.flag.message;
          store.dScanHistory = e.flag.message.history;
        }

        if (e.flag.flag == 6) {
          store.dScanIsHistory = e.flag.message;
        }
        if (e.flag.flag == 7) {
          store.dScanItemCategory = e.flag.message;
        }
        if (e.flag.flag == 8) {
          store.dScanItemGroup = e.flag.message;
        }
        if (e.flag.flag == 9) {
          store.dScanItemItem = e.flag.message;
        }

        if (e.flag.flag == 10) {
          store.dScanLocalAffiliation = e.flag.message;
        }

        if (e.flag.flag == 11) {
          router.push({ path: `/dscanisnomore` });
        }

        if (e.flag.flag == 12) {
          store.dScanMessages = e.flag.message;
        }
      });
    }
  }
};

let show = $computed(() => {
  return store.dScan ? true : false;
  //   return true;
});

let systemName = $computed(() => {
  return store.dScan.system ? store.dScan.system.system_name : "unknown";
});

let createdBy = $computed(() => {
  return store.dScan.madeby ? store.dScan.madeby.name : "unknown";
});

let updatedBy = $computed(() => {
  return store.dScan.updated_by ? store.dScan.updated_by.name : "unknown";
});

let age = $computed(() => {
  return store.dScan.updated_at ? store.dScan.updated_at : "unknown";
});

let subScan = async () => {
  loading = true;
  var data = {
    text: dScanText,
  };
  await axios({
    method: "post",
    withCredentials: true,
    data: data,
    url: "/api/dscan",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((res) => {
    loading = false;
    router.push({ path: `/dscan/${res.data.link}/` });
  });
};

let updateScan = async () => {
  loading = true;
  var data = {
    text: dScanText,
  };
  await axios({
    method: "post",
    withCredentials: true,
    data: data,
    url: "/api/dscan/" + scanLink,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((res) => {
    loading = false;

    store.dScanLocalCorp = res.data.data.corpsTotal;
    store.dScanLocalAlliance = res.data.data.allianceTotal;
    store.dScan = res.data.data.dscan;
    store.dScanHistory = res.data.data.dscan.history;
    res.data.data.categoryTotals
      ? (store.dScanItemCategory = res.data.data.categoryTotals)
      : null;
    res.data.data.groupTotals ? (store.dScanItemGroup = res.data.data.groupTotals) : null;
    res.data.data.itemTotals ? (store.dScanItemItem = res.data.data.itemTotals) : null;
    res.data.data.affiliationTotal
      ? (store.dScanLocalAffiliation = res.data.data.affiliationTotal)
      : null;
    dScanText = null;
  });
};

let countUpTimeMil = (time) => {
  if (!time) {
    return 0;
  }
  const timestamp = Date.parse(time);
  return timestamp;
};

let colClass = $computed(() => {
  return scanLink ? "full-height justify-between" : "q-gutter-lg full-height justify-end";
});

let isHistoryActive = (link) => {
  return link == scanLink ? true : false;
};

let clickHistory = (list) => {
  router.push({ path: `/dscan/${list.link}/${checkTab(list)}` });
};

let clickLive = () => {
  router.push({ path: `/dscan/${store.dScanLiveLink}/${tab}` });
};

let h = $computed(() => {
  let mins = 70;
  let window = store.size.height;

  let size = window - mins + "px";
  return "height:" + size;
});

let showLocalTab = $computed(() => {
  return store.dScan && store.dScan.locals && store.dScan.locals.length > 0;
  //   return store.dScan.locals && store.dScan.locals.length > 0 ? true : false;
});

let clickShare = () => {
  let text = "https://evestuff.apps.gnf.lt" + route.path;
  copyToClipboard(text).then(() => {
    $q.notify({
      type: "info",
      message: text + " copied to your clipboard",
    });
  });
};

let checkTab = (list) => {
  if (tab == "local") {
    let total = 0;
    if (!list.corpsTotal || Object.values(list.corpsTotal).length == 0) {
      total = 0;
    } else {
      Object.values(list.corpsTotal).forEach((corp) => {
        total += corp.totalInSystem;
      });
    }

    if (total > 0) {
      return "local";
    } else {
      return "total";
    }
    return "total";
  }

  return tab;
};

let hh = $computed(() => {
  let mins = 500;
  let window = store.size.height;

  let size = window - mins + "px";
  return "max-height:" + size;
});
</script>

<style lang="scss"></style>
