<template>
  <div>
    <div class="row justify-around">
      <!-- /////////////////////////// 1st Card /////////////////////////// -->
      <transition-group
        mode="out-in"
        enter-active-class="animate__animated animate__bounceIn animate__slower"
        leave-active-class="animate__animated animate__bounceOut animate__slower"
      >
        <div
          :key="`${showShipsTable}-shipList-totalTable`"
          class="col-4"
          v-if="showShipsTable"
        >
          <q-card class="my-card">
            <q-card-section class="q-py-none text-center">
              {{ titleShipText }} - {{ shipSystemTotals }}

              <span v-if="shipDff != 0" :class="textColor(shipDff)">
                ({{ shipDff }})</span
              >
            </q-card-section>
            <q-card-section class="overflow-auto" :style="h">
              <q-list bordered dense key="shiplist">
                <transition-group
                  mode="out-in"
                  enter-active-class="animate__animated animate__bounceIn animate__slower"
                  leave-active-class="animate__animated animate__bounceOut animate__slower"
                >
                  <q-item
                    :disable="shipIsDisable(list)"
                    v-for="(list, index) in tableShips"
                    :key="index"
                  >
                    <div
                      class="row full-width justify-between"
                      :key="`${list.id}-div-shipList`"
                    >
                      <div class="col-auto" :key="`${list.id}-avatar-name`">
                        <q-avatar size="32px">
                          <img :src="listUrl(list.details.id)"
                        /></q-avatar>
                      </div>
                      <div class="col-auto" :key="`${list.id}-shipList-name`">
                        {{ list.details.item_name }}
                      </div>
                      <transition-group
                        mode="out-in"
                        enter-active-class="animate__animated animate__flash animate__slower"
                      >
                        <div
                          class="col-auto"
                          :key="`${list.id}${itemCount(list)}-shipList-total`"
                        >
                          {{ itemCount(list) }}
                          <span
                            :key="`${list.id}${oldAllShipNumber(list)}-shipList-oldTotal`"
                            v-if="oldAllShipNumber(list) != 0"
                            :class="textColor(oldAllShipNumber(list))"
                          >
                            ({{ oldAllShipNumber(list) }})</span
                          >
                        </div>
                      </transition-group>
                    </div>
                  </q-item>
                </transition-group>
              </q-list>
            </q-card-section>
          </q-card>
        </div>
      </transition-group>
      <!-- /////////////////////////// 2nd Card /////////////////////////// -->
      <transition-group
        mode="out-in"
        enter-active-class="animate__animated animate__bounceIn animate__slower"
        leave-active-class="animate__animated animate__bounceOut animate__slower"
      >
        <div
          :key="`${showShipsTable}-combatList-totalTable`"
          class="col-4"
          v-if="showShipsTable"
        >
          <q-card class="my-card overflow-auto" :style="h">
            <q-card-section class="q-py-none text-center">
              {{ titleCombatText }} - {{ shipSystemTotals }}
              <span v-if="catDff != 0" :class="textColor(catDff)"> ({{ catDff }})</span>
            </q-card-section>
            <q-card-section>
              <q-list bordered dense>
                <transition-group
                  mode="out-in"
                  enter-active-class="animate__animated animate__bounceIn animate__slower"
                  leave-active-class="animate__animated animate__bounceOut animate__slower"
                >
                  <q-item
                    :disable="shipIsDisable(list)"
                    v-for="(list, index) in tableGroups"
                    :key="index"
                  >
                    <div
                      class="row full-width justify-between"
                      :key="`${list.id}-cambat-row`"
                    >
                      <div class="col-auto">
                        <q-avatar size="32px" :key="`${list.id}--combatavatar-name`">
                          <!-- <img :src="listurlGroup(list.details.id)" /> -->
                          <img
                            v-if="list.details.id == 29"
                            src="@/img/dscan/capsule_64.png"
                            alt=""
                          />

                          <img
                            v-if="
                              list.details.id == 27 ||
                              list.details.id == 419 ||
                              list.details.id == 540 ||
                              list.details.id == 1201
                            "
                            src="@/img/dscan/battlecruiser_64.png"
                            alt=""
                          />

                          <img
                            v-if="list.details.id == 898 || list.details.id == 900"
                            src="@/img/dscan/battleship_64.png"
                            alt=""
                          />

                          <img
                            v-if="
                              list.details.id == 485 ||
                              list.details.id == 547 ||
                              list.details.id == 883
                            "
                            src="@/img/dscan/capital_64.png"
                            alt=""
                          />

                          <img
                            v-if="
                              list.details.id == 26 ||
                              list.details.id == 358 ||
                              list.details.id == 832 ||
                              list.details.id == 833 ||
                              list.details.id == 894 ||
                              list.details.id == 906 ||
                              list.details.id == 963 ||
                              list.details.id == 1972
                            "
                            src="@/img/dscan/cruiser_64.png"
                            alt=""
                          />

                          <img
                            v-if="
                              list.details.id == 420 ||
                              list.details.id == 541 ||
                              list.details.id == 1305 ||
                              list.details.id == 1534
                            "
                            src="@/img/dscan/destroyer_64.png"
                            alt=""
                          />

                          <img
                            v-if="list.details.id == 1538"
                            src="@/img/dscan/forceAuxiliary_32.png"
                            alt=""
                          />

                          <img
                            v-if="
                              list.details.id == 25 ||
                              list.details.id == 324 ||
                              list.details.id == 830 ||
                              list.details.id == 831 ||
                              list.details.id == 834 ||
                              list.details.id == 893 ||
                              list.details.id == 1022 ||
                              list.details.id == 1283 ||
                              list.details.id == 1527 ||
                              list.details.id == 2001
                            "
                            src="@/img/dscan/frigate_64.png"
                            alt=""
                          />
                          <img
                            v-if="list.details.id == 513 || list.details.id == 902"
                            src="@/img/dscan/freighter_64.png"
                            alt=""
                          />

                          <img
                            v-if="
                              list.details.id == 28 ||
                              list.details.id == 380 ||
                              list.details.id == 1202
                            "
                            src="@/img/dscan/industrial_64.png"
                            alt=""
                          />

                          <img
                            v-if="list.details.id == 941"
                            src="@/img/dscan/industrialCommand_64.png"
                            alt=""
                          />

                          <img
                            v-if="list.details.id == 463 || list.details.id == 543"
                            src="@/img/dscan/miningBarge_64.png"
                            alt=""
                          />

                          <img
                            v-if="list.details.id == 237"
                            src="@/img/dscan/rookie_64.png"
                            alt=""
                          />

                          <img
                            v-if="list.details.id == 31"
                            src="@/img/dscan/shuttle_64.png"
                            alt=""
                          />

                          <img
                            v-if="list.details.id == 659"
                            src="@/img/dscan/superCarrier_64.png"
                            alt=""
                          />

                          <img
                            v-if="list.details.id == 30"
                            src="@/img/dscan/titan_64.png"
                            alt=""
                          />
                        </q-avatar>
                      </div>
                      <div class="col-auto" :key="`${list.id}-namecol-name`">
                        {{ list.details.name }}
                      </div>
                      <transition-group
                        mode="out-in"
                        enter-active-class="animate__animated animate__flash animate__slower"
                      >
                        <div
                          class="col-auto"
                          :key="`${list.id}${itemCount(list)}-combat-total`"
                        >
                          {{ itemCount(list) }}
                          <span
                            :key="`${list.id}${oldAllShipNumber(list)}-combat-oldTotal`"
                            v-if="oldAllShipNumber(list) != 0"
                            :class="textColor(oldAllShipNumber(list))"
                          >
                            ({{ oldAllShipNumber(list) }})</span
                          >
                        </div>
                      </transition-group>
                    </div>
                  </q-item>
                </transition-group>
              </q-list>
            </q-card-section>
          </q-card>
        </div>
      </transition-group>
      <!-- /////////////////////////// 3rd Card /////////////////////////// -->

      <div class="col-3">
        <div class="column q-gutter-lg">
          <transition-group
            mode="out-in"
            enter-active-class="animate__animated animate__bounceIn animate__slower"
            leave-active-class="animate__animated animate__bounceOut animate__slower"
          >
            <div
              class="col-auto"
              v-if="showStructureTable"
              :key="`${showStructureTable}-structures-totalTable`"
            >
              <q-card class="my-card overflow-auto" :style="h">
                <q-card-section class="q-py-none text-center">
                  {{ titleStructureText }} - {{ structureSystemTotals }}
                  <span v-if="strDff != 0" :class="textColor(strDff)">
                    ({{ strDff }})</span
                  >
                </q-card-section>
                <q-card-section>
                  <q-list bordered dense>
                    <transition-group
                      mode="out-in"
                      enter-active-class="animate__animated animate__bounceIn animate__slower"
                      leave-active-class="animate__animated animate__bounceOut animate__slower"
                    >
                      <q-item
                        :disable="shipIsDisable(list)"
                        clickable
                        v-for="(list, index) in tableStructure"
                        :key="index"
                      >
                        <div
                          class="row full-width justify-between"
                          :key="`${list.id}-row`"
                        >
                          <div class="col-auto" :key="`${list.id}-col-strname`">
                            {{ list.details.name }}
                          </div>
                          <transition-group
                            mode="out-in"
                            enter-active-class="animate__animated animate__flash animate__slower"
                          >
                            <div
                              class="col-auto"
                              :key="`${list.id}${itemCount(list)}-strList-total`"
                            >
                              {{ itemCount(list) }}
                              <span
                                :key="`${list.id}${oldAllShipNumber(
                                  list
                                )}-strList-oldTotal`"
                                v-if="oldAllShipNumber(list) != 0"
                                :class="textColor(oldAllShipNumber(list))"
                              >
                                ({{ oldAllShipNumber(list) }})</span
                              >
                            </div>
                          </transition-group>
                        </div>
                      </q-item>
                    </transition-group>
                  </q-list>
                </q-card-section>
              </q-card>
            </div>
          </transition-group>
          <transition-group
            mode="out-in"
            enter-active-class="animate__animated animate__bounceIn animate__slower"
            leave-active-class="animate__animated animate__bounceOut animate__slower"
          >
            <div
              class="col-auto"
              v-if="showOtherTable"
              :key="`${showOtherTable}-other-totalTable`"
            >
              <q-card class="my-card overflow-auto" :style="h">
                <q-card-section class="q-py-none text-center">
                  {{ titleOtherText }} - {{ otherSystemTotals }}
                  <span v-if="otherDff != 0" :class="textColor(otherDff)">
                    ({{ otherDff }})</span
                  >
                </q-card-section>
                <q-card-section>
                  <transition-group
                    mode="out-in"
                    enter-active-class="animate__animated animate__bounceIn animate__slower"
                    leave-active-class="animate__animated animate__bounceOut animate__slower"
                  >
                    <q-list bordered dense>
                      <q-item
                        :disable="shipIsDisable(list)"
                        clickable
                        v-for="(list, index) in tableOthers"
                        :key="index"
                      >
                        <div
                          class="row full-width justify-between"
                          :key="`${list.id}-row`"
                        >
                          <div class="col-auto" :key="`${list.id}-col-othername`">
                            {{ list.details.item_name }}
                          </div>
                          <transition-group
                            mode="out-in"
                            enter-active-class="animate__animated animate__flash animate__slower"
                          >
                            <div
                              class="col-auto"
                              :key="`${list.id}${itemCount(list)}-otherList-total`"
                            >
                              {{ itemCount(list) }}
                              <span
                                :key="`${list.id}${oldAllShipNumber(
                                  list
                                )}-otherList-oldTotal`"
                                v-if="oldAllShipNumber(list) != 0"
                                :class="textColor(oldAllShipNumber(list))"
                              >
                                ({{ oldAllShipNumber(list) }})</span
                              >
                            </div>
                          </transition-group>
                        </div>
                      </q-item>
                    </q-list>
                  </transition-group>
                </q-card-section>
              </q-card>
            </div>
          </transition-group>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();

const props = defineProps({
  type: Number,
});

let listUrl = (id) => {
  return "https://imageserver.eveonline.com/Type/" + id + "_64.png";
};

let tableShips = $computed(() => {
  if (props.type == 1) {
    if (store.dScanItemItem) {
      return store.dScanItemItem.filter((i) => i.details.group.category_id == 6);
    } else {
      return [];
    }
  }

  if (props.type == 2) {
    if (store.dScanItemItem) {
      return store.dScanItemItem.filter(
        (i) =>
          i.details.group.category_id == 6 && (i.totalOnGrid > 0 || i.oldTotalOnGrid > 0)
      );
    } else {
      return [];
    }
  }

  if (props.type == 3) {
    if (store.dScanItemItem) {
      return store.dScanItemItem.filter(
        (i) =>
          i.details.group.category_id == 6 &&
          (i.totalOffGrid > 0 || i.oldTotalOffGrid > 0)
      );
    } else {
      return [];
    }
  }
});

let shipSystemTotals = $computed(() => {
  if (props.type == 1) {
    if (tableShips) {
      return tableShips.reduce((acc, item) => acc + item.totalInSystem, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 2) {
    if (tableShips) {
      return tableShips.reduce((acc, item) => acc + item.totalOnGrid, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 3) {
    if (tableShips) {
      return tableShips.reduce((acc, item) => acc + item.totalOffGrid, 0);
    } else {
      return 0;
    }
  }
});

let shipTotals = $computed(() => {
  if (props.type == 1) {
    if (tableShips) {
      return tableShips.reduce((acc, item) => acc + item.total, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 2) {
    if (tableShips) {
      return tableShips.reduce((acc, item) => acc + item.totalOnGrid, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 3) {
    if (tableShips) {
      return tableShips.reduce((acc, item) => acc + item.totalOffGrid, 0);
    } else {
      return 0;
    }
  }
});

let tableGroups = $computed(() => {
  if (props.type == 1) {
    if (store.dScanItemGroup) {
      return store.dScanItemGroup.filter((i) => i.details.category_id == 6);
    } else {
      return [];
    }
  }

  if (props.type == 2) {
    if (store.dScanItemGroup) {
      return store.dScanItemGroup.filter(
        (i) => i.details.category_id == 6 && (i.totalOnGrid > 0 || i.oldTotalOnGrid > 0)
      );
    } else {
      return [];
    }
  }

  if (props.type == 3) {
    if (store.dScanItemGroup) {
      return store.dScanItemGroup.filter(
        (i) => i.details.category_id == 6 && (i.totalOffGrid > 0 || i.oldTotalOffGrid > 0)
      );
    } else {
      return [];
    }
  }
});

let groupSystemTotals = $computed(() => {
  if (props.type == 1) {
    if (tableGroups) {
      return tableGroups.reduce((acc, item) => acc + item.totalInSystem, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 2) {
    if (tableGroups) {
      return tableGroups.reduce((acc, item) => acc + item.totalOnGrid, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 3) {
    if (tableGroups) {
      return tableGroups.reduce((acc, item) => acc + item.totalOffGrid, 0);
    } else {
      return 0;
    }
  }
});

let groupTotals = $computed(() => {
  if (props.type == 1) {
    if (tableGroups) {
      return tableGroups.reduce((acc, item) => acc + item.total, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 2) {
    if (tableGroups) {
      return tableGroups.reduce((acc, item) => acc + item.totalOnGrid, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 3) {
    if (tableGroups) {
      return tableGroups.reduce((acc, item) => acc + item.totalOffGrid, 0);
    } else {
      return 0;
    }
  }
});

let tableStructure = $computed(() => {
  if (props.type == 1) {
    if (store.dScanItemGroup) {
      return store.dScanItemGroup.filter(
        (i) =>
          i.details.category_id == 65 ||
          i.details.category_id == 40 ||
          i.details.category_id == 22
      );
    } else {
      return [];
    }
  }

  if (props.type == 2) {
    if (store.dScanItemGroup) {
      return store.dScanItemGroup.filter(
        (i) =>
          (i.details.category_id == 65 ||
            i.details.category_id == 40 ||
            i.details.category_id == 22) &&
          (i.totalOnGrid > 0 || i.oldTotalOnGrid > 0)
      );
    } else {
      return [];
    }
  }

  if (props.type == 3) {
    if (store.dScanItemGroup) {
      return store.dScanItemGroup.filter(
        (i) =>
          (i.details.category_id == 65 ||
            i.details.category_id == 40 ||
            i.details.category_id == 22) &&
          (i.totalOffGrid > 0 || i.oldTotalOffGrid > 0)
      );
    } else {
      return [];
    }
  }
});

let structureSystemTotals = $computed(() => {
  if (props.type == 1) {
    if (tableStructure) {
      return tableStructure.reduce((acc, item) => acc + item.totalInSystem, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 2) {
    if (tableStructure) {
      return tableStructure.reduce((acc, item) => acc + item.totalOnGrid, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 3) {
    if (tableStructure) {
      return tableStructure.reduce((acc, item) => acc + item.totalOffGrid, 0);
    } else {
      return 0;
    }
  }
});

let structureTotals = $computed(() => {
  if (props.type == 1) {
    if (tableStructure) {
      return tableStructure.reduce((acc, item) => acc + item.total, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 2) {
    if (tableStructure) {
      return tableStructure.reduce((acc, item) => acc + item.totalOnGrid, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 3) {
    if (tableStructure) {
      return tableStructure.reduce((acc, item) => acc + item.totalOffGrid, 0);
    } else {
      return 0;
    }
  }
});

let tableOthers = $computed(() => {
  if (props.type == 1) {
    if (store.dScanItemItem) {
      return store.dScanItemItem.filter(
        (i) =>
          i.details.group.category_id != 6 &&
          i.details.group.category_id != 65 &&
          i.details.group.category_id != 2 &&
          i.details.group.category_id != 40 &&
          i.details.group.category_id != 22
      );
    } else {
      return [];
    }
  }

  if (props.type == 2) {
    if (store.dScanItemItem) {
      return store.dScanItemItem.filter(
        (i) =>
          i.details.group.category_id != 6 &&
          i.details.group.category_id != 65 &&
          i.details.group.category_id != 2 &&
          i.details.group.category_id != 40 &&
          i.details.group.category_id != 22 &&
          (i.totalOnGrid > 0 || i.oldTotalOnGrid > 0)
      );
    } else {
      return [];
    }
  }

  if (props.type == 3) {
    if (store.dScanItemItem) {
      return store.dScanItemItem.filter(
        (i) =>
          i.details.group.category_id != 6 &&
          i.details.group.category_id != 2 &&
          i.details.group.category_id != 65 &&
          i.details.group.category_id != 40 &&
          i.details.group.category_id != 22 &&
          (i.totalOffGrid > 0 || i.oldTotalOffGrid > 0)
      );
    } else {
      return [];
    }
  }
});

let otherSystemTotals = $computed(() => {
  if (props.type == 1) {
    if (tableShips) {
      return tableOthers.reduce((acc, item) => acc + item.totalInSystem, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 2) {
    if (tableOthers) {
      return tableOthers.reduce((acc, item) => acc + item.totalOnGrid, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 3) {
    if (tableOthers) {
      return tableOthers.reduce((acc, item) => acc + item.totalOffGrid, 0);
    } else {
      return 0;
    }
  }
});

let otherTotals = $computed(() => {
  if (props.type == 1) {
    if (tableOthers) {
      return tableOthers.reduce((acc, item) => acc + item.total, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 2) {
    if (tableOthers) {
      return tableOthers.reduce((acc, item) => acc + item.totalOnGrid, 0);
    } else {
      return 0;
    }
  }

  if (props.type == 3) {
    if (tableOthers) {
      return tableOthers.reduce((acc, item) => acc + item.totalOffGrid, 0);
    } else {
      return 0;
    }
  }
});

let shipIsDisable = (list) => {
  if (props.type == 1) {
    return list.totalInSystem ? false : true;
  }
  if (props.type == 2) {
    return list.totalOnGrid ? false : true;
  }
  if (props.type == 3) {
    return list.totalOffGrid ? false : true;
  }
};

let shipDff = $computed(() => {
  var totalInSystem = shipSystemTotals;
  var totalShips = shipTotals;
  var diff = totalInSystem - totalShips;
  return diff;
});

let catDff = $computed(() => {
  var totalGroupInSystem = groupSystemTotals;
  var totalGroup = groupTotals;
  var diff = totalGroupInSystem - totalGroup;
  return diff;
});

let strDff = $computed(() => {
  var totalStructureInSystem = structureSystemTotals;
  var totalStructure = structureTotals;
  var diff = totalStructureInSystem - totalStructure;
  return diff;
});

let otherDff = $computed(() => {
  var totalStructureInSystem = otherSystemTotals;
  var totalStructure = otherTotals;
  var diff = totalStructureInSystem - totalStructure;
  return diff;
});

let oldAllShipNumber = (list) => {
  if (!list.left && !list.same) return 0;

  return list.new + list.left * -1;
};

let textColor = (count) => {
  if (count > 0) {
    return "text-green";
  } else if (count < 0) {
    return "text-red";
  } else {
    return "text-white";
  }
};

let titleShipText = $computed(() => {
  if (props.type == 1) {
    return "All Ships";
  }

  if (props.type == 2) {
    return "Ships On Grid";
  }

  if (props.type == 3) {
    return "Ships Off Grid";
  }
});

let titleCombatText = $computed(() => {
  if (props.type == 1) {
    return "All Combat";
  }

  if (props.type == 2) {
    return "Combat On Grid";
  }

  if (props.type == 3) {
    return "Combat Off Grid";
  }
});

let titleStructureText = $computed(() => {
  if (props.type == 1) {
    return "All Structures";
  }

  if (props.type == 2) {
    return "Structures On Grid";
  }

  if (props.type == 3) {
    return "Structures Off Grid";
  }
});

let titleOtherText = $computed(() => {
  if (props.type == 1) {
    return "All Others";
  }

  if (props.type == 2) {
    return "Others On Grid";
  }

  if (props.type == 3) {
    return "Others Off Grid";
  }
});

let itemCount = (list) => {
  if (props.type == 1) {
    return list.totalInSystem;
  }

  if (props.type == 2) {
    return list.totalOnGrid;
  }

  if (props.type == 3) {
    return list.totalOffGrid;
  }
};

let showShipsTable = $computed(() => {
  return shipSystemTotals || shipDff ? true : false;
});

let showStructureTable = $computed(() => {
  return structureSystemTotals || strDff ? true : false;
});

let showOtherTable = $computed(() => {
  return otherSystemTotals || otherDff ? true : false;
});

let h = $computed(() => {
  let mins = 110;
  let window = store.size.height;

  let size = window - mins + "px";
  return "max-height:" + size;
});
</script>

<style lang="scss"></style>
