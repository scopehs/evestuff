<template>
  <div>
    <q-expansion-item
      class="shadow-1 overflow-hidden"
      style="border-radius: 30px"
      label="dance"
      expand-icon-toggle
      hide-expand-icon
      :model-value="showPannel"
      header-class=" q-py-none bg-webBack text-webway text-center"
      @show="10"
      @hide="10"
      dark
    >
      <template v-slot:header>
        <div class="row q-gutter-none full-width items-center justify-between">
          <div class="col-auto">
            <div class="row full-width">
              <div class="col-auto">
                <q-btn
                  class="myOutLineButtonMid"
                  color="primary"
                  :outline="charTableOutlined"
                  @click="btnShowCharTable"
                  label="Char Table"
                  rounded
                />
              </div>
              <div class="col-auto">
                <AddOperationUser :operationID="props.operationID" />
              </div>
              <div class="col-auto">
                <OperationCal :operationID="operationID" />
              </div>
            </div>
          </div>
          <div v-if="can('access_multi_campaigns')" class="col-auto q-gutter-md">
            <q-btn
              v-if="can('access_campaigns')"
              flat
              rounded
              padding="none"
              color="primary"
              icon="fa-solid fa-bullhorn"
              @click="sendAddCharMessage"
            />
            <q-btn
              v-if="can('access_campaigns')"
              flat
              rounded
              padding="none"
              color="negative"
              icon="fa-solid fa-bullhorn"
              @click="sendAddCharMessagePlus"
            />
          </div>
          <div class="col-auto" v-if="can('view_campaign_members')">
            <q-btn
              class="myOutLineButtonMid"
              color="primary"
              :outline="userTableOutlined"
              @click="btnShowUserTable"
              label="User List"
              rounded
            />
          </div>
          <div class="col-auto" v-if="can('view_campaign_members')">
            <q-btn
              class="myOutLineButtonMid"
              color="primary"
              :outline="logTableOutlined"
              @click="btnShowLogTable"
              label="Logs"
              rounded
            />
          </div>
          <div class="col-auto" v-if="can('edit_read_only')">
            <q-toggle
              v-model="store.newOperationInfo.read_only"
              :true-value="1"
              :false-value="0"
              color="green"
            >
              <template v-slot:default>
                <transition
                  mode="out-in"
                  enter-active-class="animate__animated animate__flash animate__slower"
                  leave-active-class="animate__animated animate__flash animate__slower"
                >
                  <span :class="toggleTextColor" :key="store.newOperationInfo.read_only">
                    Read Only - {{ readOnlyText }}</span
                  >
                </transition>
              </template></q-toggle
            >
          </div>
          <div class="col-auto q-gutter-sm">
            <q-btn
              color="primary"
              class="myRoundButtonLeft myOutLineButtonMid"
              label="Open"
              @click="toggleOpen"
            />

            <q-btn
              color="primary"
              class="myRoundButtonRight myOutLineButtonMid"
              label="CLose"
              @click="toggleClose"
            />
          </div>
        </div>
      </template>

      <q-card>
        <q-card-section>
          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="charTable">
              <OperationUserTable :operationID="operationID" />
            </q-tab-panel>
            <q-tab-panel v-if="can('view_campaign_members')" name="userTable">
              <OperationUserListTable :operationID="operationID" />
            </q-tab-panel>
            <q-tab-panel name="logTable">
              <OperationLogTable
                v-if="can('view_campaign_members')"
                :operationID="operationID"
              />
            </q-tab-panel>
          </q-tab-panels>
        </q-card-section>
      </q-card>
    </q-expansion-item>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

const store = useMainStore();

const AddOperationUser = defineAsyncComponent(() => import("./AddOperationUser.vue"));
const OperationCal = defineAsyncComponent(() => import("./OperationCal.vue"));
const OperationUserTable = defineAsyncComponent(() => import("./OperationUserTable.vue"));
const OperationUserListTable = defineAsyncComponent(() =>
  import("./OperationUserListTable.vue")
);
const OperationLogTable = defineAsyncComponent(() => import("./OperationLogTable.vue"));

let can = inject("can");
const props = defineProps({
  operationID: Number,
});
let showPannel = $ref(false);
let tab = $ref(null);

let btnShowCharTable = () => {
  if (showPannel && tab == "charTable") {
    showPannel = false;
    tab = null;
    return;
  }

  if (showPannel && tab != "charTable") {
    tab = "charTable";
    return;
  }
  if (!showPannel) {
    showPannel = true;
    tab = "charTable";
    return;
  }
};

let charTableOutlined = $computed(() => {
  if (showPannel && tab == "charTable") {
    return false;
  } else {
    return true;
  }
});

let btnShowUserTable = () => {
  if (showPannel && tab == "userTable") {
    showPannel = false;
    tab = null;
    return;
  }

  if (showPannel && tab != "userTable") {
    tab = "userTable";
    return;
  }
  if (!showPannel) {
    showPannel = true;
    tab = "userTable";
    return;
  }
};

let userTableOutlined = $computed(() => {
  if (showPannel && tab == "userTable") {
    return false;
  } else {
    return true;
  }
});

let btnShowLogTable = async () => {
  if (showPannel && tab == "logTable") {
    showPannel = false;
    tab = null;
    return;
  }

  if (showPannel && tab != "logTable") {
    tab = "logTable";
    await store.getCampaignsLogs(props.operationID);
    return;
  }
  if (!showPannel) {
    showPannel = true;
    tab = "logTable";
    await store.getCampaignsLogs(props.operationID);
    return;
  }
};

let logTableOutlined = $computed(() => {
  if (showPannel && tab == "logTable") {
    return false;
  } else {
    return true;
  }
});

let sendAddCharMessage = async () => {
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/sendadduseroverlay/" + props.operationID + "/" + 1,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let sendAddCharMessagePlus = async () => {
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/sendadduseroverlay/" + props.operationID + "/" + 2,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let readOnlyText = $computed(() => {
  if (store.newOperationInfo.read_only == 1) {
    return "Off";
  } else {
    return "On";
  }
});

let toggleTextColor = $computed(() => {
  if (store.newOperationInfo.read_only == 1) {
    return "text-green";
  } else {
    return "text-red";
  }
});

let toggleOpen = async () => {
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/operation/toggleWindow/" + 1 + "/" + props.operationID,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let toggleClose = async () => {
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/operation/toggleWindow/" + 0 + "/" + props.operationID,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss"></style>
