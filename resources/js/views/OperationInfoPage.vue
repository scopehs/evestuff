<template>
  <div class="q-ma-md">
    <q-card class="myRoundTop myOperationInfoMainCard">
      <q-card-section class="bg-primary text-center q-py-xs">
        <div class="row full-width justify-between">
          <div class="col-auto flex align-baseline">
            <q-btn text-color="warning" flat icon="fa-solid fa-eye" rounded padding="none"
              ><q-menu class="myRoundTop">
                <q-card class="myRoundTop text-webway">
                  <q-card-section class="bg-primary text-h5 text-center">
                    <h5 class="no-margin">Page Setting</h5> </q-card-section
                  ><q-card-section>
                    Your Own Setting for this page
                    <q-option-group
                      class="q-pt-md"
                      v-model="ownSetting"
                      :options="store.operationInfoSettingOpetions"
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
                            <span>{{ opt.label }}</span></transition
                          >
                        </div>
                      </template>
                    </q-option-group>
                  </q-card-section>
                </q-card>
              </q-menu></q-btn
            >
            <transition
              mode="out-in"
              enter-active-class="animate__animated animate__fadeIn animate__slower"
              leave-active-class="animate__animated animate__fadeOut animate__slower"
            >
              <OperationInfoCheckList
                :key="`${showCheckList}-ownSetting`"
                v-if="showCheckList"
                class="q-pl-sm"
              />
            </transition>
            <div class="col flex flex-center q-pl-lg">
              <OperationInfoMessageCard v-if="store.operationInfoPage.messages" />
            </div>
          </div>
          <div class="col-auto">
            <h5 class="no-margin">Operation - {{ opInfo.name }}</h5>
          </div>
          <div class="">
            <OperationInfoSettingPannel />
          </div>
        </div>
      </q-card-section>
      <q-card-section>
        <div class="row justify-between">
          <transition
            mode="out-in"
            enter-active-class="animate__animated animate__backInLeft animate__slower"
            leave-active-class="animate__animated animate__backOutLeft animate__slower"
          >
            <div class="col-3 q-pr-lg" v-if="showLeftCol">
              <div class="column q-gutter-lg">
                <OperationInfoReconCard v-if="showReconTable" />
                <OperationInfoSystemWatchTable v-if="showWatchedSystems" />
              </div>
            </div>
          </transition>

          <div class="col-9 col-grow">
            <div class="column q-gutter-lg">
              <transition
                mode="out-in"
                enter-active-class="animate__animated animate__backInDown animate__slower"
                leave-active-class="animate__animated animate__backOutUp animate__slower"
              >
                <OperationInfoSystemTable v-if="showSystemTable" />
              </transition>
              <transition
                mode="out-in"
                enter-active-class="animate__animated animate__backInUp animate__slower"
                leave-active-class="animate__animated animate__backOutDown animate__slower"
              >
                <OperationInfoFleetCard v-if="showFleets" />
              </transition>
            </div>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useRoute } from "vue-router";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";
let router = useRouter();
let route = useRoute();
let store = useMainStore();
let can = inject("can");

const OperationInfoReconCard = defineAsyncComponent(() =>
  import("../components/operationinfo/OperationInfoReconCard.vue")
);

const OperationInfoSettingPannel = defineAsyncComponent(() =>
  import("../components/operationinfo/OperationInfoSettingPannel.vue")
);

const OperationInfoSystemTable = defineAsyncComponent(() =>
  import("../components/operationinfo/OpertationInfoSystemTable.vue")
);

const OperationInfoFleetCard = defineAsyncComponent(() =>
  import("../components/operationinfo/OperationInfoFleetCard.vue")
);

const OperationInfoCheckList = defineAsyncComponent(() =>
  import("../components/operationinfo/OperationInfoCheckList.vue")
);

const OperationInfoMessageCard = defineAsyncComponent(() =>
  import("../components/operationinfo/OperationInfoMessageCard.vue")
);

const OperationInfoSystemWatchTable = defineAsyncComponent(() =>
  import("../components/operationinfo/OperationInfoSystemWatchTable.vue")
);

onMounted(async () => {
  await store.getOperationSheetInfoPage(route.params.link);
  await store.getOperationUsers();
  await store.getOperationInfoMumble();
  await store.getOperationInfoDoctrines();
  await store.getOperationRecon();
  await store.getAllianceTickList();
  await store.getSystemList();
  await store.getOperationInfoReconRoles();
  await store.getOperationSheetInfoOperationList();
  await store.getOperationInfoJamList();
  await store.getUserList();

  Echo.private("operationinfooppageown." + store.user_id + "-" + opInfo.id);

  Echo.private("operationinfooppage." + opInfo.id).listen(
    "OperationInfoPageSoloUpdate",
    (e) => {
      if (e.flag.flag == 1) {
        store.updateOperationSheetInfoPage(e.flag.message);
      }

      if (e.flag.flag == 2) {
        store.updateOperationSheetInfoPageFleet(e.flag.message);
      }

      if (e.flag.flag == 3) {
        store.operationInfoUsers = e.flag.message;
      }

      if (e.flag.flag == 4) {
        store.operationInfoRecon = e.flag.message;
      }

      if (e.flag.flag == 5) {
        store.updateOperationReconSolo(e.flag.message);
      }

      if (e.flag.flag == 6) {
        store.deleteOperationSheetInfoPageFleet(e.flag.message);
      }

      if (e.flag.flag == 7) {
        store.updateOperationMessage(e.flag.message);
      }

      if (e.flag.flag == 8) {
        store.operationInfoPage.status = e.flag.message;
      }

      if (e.flag.flag == 9) {
        store.operationInfoPage.operation = e.flag.message;
      }

      if (e.flag.flag == 10) {
        store.operationInfoPage.operation = e.flag.message;
      }

      if (e.flag.flag == 11) {
        store.operationInfoPage.systems = e.flag.message;
      }

      if (e.flag.flag == 12) {
        store.getOperationInfoDoctrines();
      }

      if (e.flag.flag == 13) {
        store.removeOperationReconSolo(e.flag.message);
      }

      if (e.flag.flag == 14) {
        store.updateOperationSoloSystems(e.flag.message);
      }

      if (e.flag.flag == 15) {
        store.clearOperationInfoSolo();
        router.push({ path: `/operationinfoover` });
      }
      if (e.flag.flag == 16) {
        store.updateNewCampaignSystemInfo(e.flag.message);
      }

      if (e.flag.flag == 17) {
        store.updateOperationCampaignsSolo(e.flag.message);
      }

      if (e.flag.flag == 18) {
        store.operationInfoUserList = e.flag.message;
      }

      if (e.flag.flag == 19) {
        store.operationInfoPage.fleets = e.flag.message;
      }

      if (e.flag.flag == 20) {
        store.operationInfoPage.dankop = e.flag.message;
      }

      if (e.flag.flag == 21) {
        store.operationInfoPage.watch_systems = e.flag.message;
      }

      if (e.flag.flag == 22) {
        store.operationInfoPage.dankop = null;
      }
    }
  );

  document.title = "Evestuff - Operation Page - " + opInfo.name;
});

onBeforeUnmount(async () => {
  Echo.leave("operationinfooppage." + opInfo.id);
  Echo.leave("operationinfooppageown." + store.user_id + "-" + opInfo.id);
});

let opInfo = $computed(() => {
  return store.operationInfoPage;
});

let showFleets = $computed(() => {
  if (opSetting.showFleets && opInfo.fleet_table) {
    return true;
  }
  return false;
});

let showReconTable = $computed(() => {
  if (opSetting.showReconTable && opInfo.recon_table) {
    return true;
  }
  return false;
});

let showSystemTable = $computed(() => {
  if (opSetting.showSystemTable && opInfo.system_table) {
    return true;
  }
  return false;
});

let showCheckList = $computed(() => {
  if (opSetting.showCheckList && opInfo.check_list) {
    return true;
  }
  return false;
});

let showWatchedSystems = $computed(() => {
  if (opSetting.showWatchedSystems && opInfo.watched_system_table) {
    return true;
  }
  return false;
});

let opSetting = $computed(() => {
  return store.operationInfoSetting;
});

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});

let ownSetting = $computed({
  get: () => {
    return store.getOperationInfoTableSettings;
  },
  set: (value) => {
    store.setOwnOptions(value);
  },
});

let showLeftCol = $computed(() => {
  return showReconTable || showWatchedSystems ? true : false;
});

let reconHeight = $computed(() => {
  if (!showWatchedSystems) {
    let mins = 100;
    let window = store.size.height;

    return window - mins + "px";
  } else {
    let mins = 70;
    let window = store.size.height / 2;

    return window - mins + "px";
  }
});

let reconNameHeight = $computed(() => {
  if (!showWatchedSystems) {
    let mins = 170;
    let window = store.size.height;

    return window - mins + "px";
  } else {
    let mins = 150;
    let window = store.size.height / 2;

    return window - mins + "px";
  }
});

let watchedHeight = $computed(() => {
  if (!showReconTable) {
    let mins = 100;
    let window = store.size.height;

    return window - mins + "px";
  } else {
    let mins = 70;
    let window = store.size.height / 2;

    return window - mins + "px";
  }
});

let fleetHeight = $computed(() => {
  if (!showSystemTable) {
    let mins = 120;
    let window = store.size.height;

    return window - mins + "px";
  } else {
    let mins = 60;
    let window = store.size.height / 2;

    return window - mins + "px";
  }
});

let systemHeight = $computed(() => {
  if (!showFleets) {
    let mins = 120;
    let window = store.size.height;

    return window - mins + "px";
  } else {
    let mins = 80;
    let window = store.size.height / 2;

    return window - mins + "px";
  }
});
</script>

<style lang="sass">
.myOperationInfoMainCard
  /* height or max-height is important */
  height: v-bind(h)

.myOperationInfoReconCard
  /* height or max-height is important */
  height: v-bind(reconHeight)

.myReconNames
  /* height or max-height is important */
  height: v-bind(reconNameHeight)
</style>

<style lang="sass">
.myOperationSystemTable
  /* height or max-height is important */
  height: v-bind(systemHeight)

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

<style lang="sass">
.myOperationFleetTable
  /* height or max-height is important */
  height: v-bind(fleetHeight)

  .q-table__top
    padding-top: 0 !important
    padding-left: 0 !important
    padding-right: 0 !important
  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>

<style lang="sass">
.myTableWatchedSystem
  /* height or max-height is important */
  height: v-bind(watchedHeight)

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
