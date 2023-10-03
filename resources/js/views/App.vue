<template>
  <q-layout view="hHh lpR fFf" class="bg-test">
    <q-header elevated class="bg-webDark text-white" height-hint="98">
      <div class="row justify-between q-px-md">
        <div class="col-2">
          <div class="row">
            <div class="col text-h6">{{ store.user_name }}</div>
          </div>
          <div class="row">
            <div class="col text-subtitle2">
              Eve Player Count:
              <span class="text-positive">
                {{ store.eveUserCount }}
              </span>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <q-tabs align="center">
            <q-tab label="Sovereignty">
              <template v-slot:default>
                <q-menu>
                  <q-list style="min-width: 100px">
                    <q-item to="/operations" clickable v-close-popup>
                      <q-item-section>Operations</q-item-section>
                    </q-item>
                    <q-item to="/windows" clickable v-close-popup>
                      <q-item-section>Windows</q-item-section>
                    </q-item>
                    <q-item
                      to="/mcampaigns"
                      clickable
                      v-close-popup
                      v-if="can('access_multi_campaigns')"
                    >
                      <q-item-section>Custom Campaign</q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </template></q-tab
            >
            <q-tab label="Stations"
              ><template v-slot:default>
                <q-menu>
                  <q-list style="min-width: 100px">
                    <q-item to="/stations" clickable v-close-popup>
                      <q-item-section>Stations List</q-item-section>
                    </q-item>

                    <q-item
                      to="/addtimer"
                      v-if="can('finish_move_timer')"
                      clickable
                      v-close-popup
                    >
                      <q-item-section>To Check</q-item-section>
                    </q-item>
                    <q-item
                      to="/addtimer"
                      v-else-if="can('view_move_timers')"
                      clickable
                      v-close-popup
                    >
                      <q-item-section>Add Timer</q-item-section>
                    </q-item>
                    <q-item
                      v-if="can('view_station_watch_list_setup')"
                      to="/stationwatchlist/settings"
                      clickable
                      v-close-popup
                    >
                      <q-item-section>Hit Lists</q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </template></q-tab
            >
            <q-route-tab
              v-if="can('view_opertaion_info')"
              to="/operationinfo"
              :label="operationText()"
            />
            <q-route-tab
              v-if="can('edit_notifications')"
              to="/notifications"
              label="Notifications"
            />
            <q-route-tab v-if="can('view_towers')" to="/towers" label="Towers" />

            <q-route-tab
              v-if="can('view_affiliation_page')"
              to="/affilations"
              label="Affiliations"
            />
            <q-route-tab v-if="can('view_staging_page')" to="/staging" label="staging" />
            <q-route-tab v-if="can('edit_users')" to="/pannel" label="Users" />
            <q-route-tab v-if="can('super')" to="/feedback" label="Feedback" />
          </q-tabs>
        </div>
        <div class="col-2">
          <div class="row full-height flex-center">
            <div class="col flex justify-content-end">
              <q-btn
                color="bg-webDark"
                flat
                icon="fa-solid fa-comment"
                label="Feedback"
                @click="showFeedback = true"
              />
            </div>
            <div class="col-1 align-bottom q-ml-xs">
              <q-btn
                dense
                flat
                round
                icon="fa-solid fa-right-from-bracket"
                @click="logout()"
              />
            </div>
          </div>
        </div>
      </div>
    </q-header>
    <q-drawer
      show-if-above
      v-model="leftDrawerOpen"
      :width="200"
      :breakpoint="500"
      side="left"
      mini-to-overlay
      elevated
    >
      <q-resize-observer @resize="onResize" />
    </q-drawer>
    <q-page-container class="bg-test">
      <router-view v-slot="{ Component }">
        <transition
          mode="out-in"
          enter-active-class="animate__animated animate__fadeIn "
          leave-active-class="animate__animated animate__fadeOut "
        >
          <component :key="route.path" :is="Component" />
        </transition>
      </router-view>
    </q-page-container>

    <q-dialog v-model="showFeedback" persistent>
      <q-card class="myRoundTop" style="width: 500px">
        <q-card-section class="bg-primary text-h5 text-center">
          <h4 class="no-margin">Give your feedback here</h4>
        </q-card-section>
        <q-card-section>
          <div>
            <q-input
              input-style="height: 500px"
              v-model="feedBackText"
              clearable
              outlined
              rounded
              dense
              type="textarea"
              label="Describe your bug/feedback here"
            />
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn color="primary" label="Submit" @click="submitFeedBack()" />
          <q-btn color="negative" label="Close" @click="closeFeedBack()" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-layout>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useQuasar } from "quasar";
import { useMainStore } from "@/store/useMain.js";
import { useRoute } from "vue-router";

let store = useMainStore();
let can = inject("can");
let route = useRoute();
let $q = useQuasar();

$q.dark.set(true); // or false or "auto"

onMounted(async () => {
  await store.getLoginInfo();
  await store.geteveusercount();
  leftDrawerOpen = false;
  Echo.private("evestuff").listen("EveUserUpdate", (e) => {
    if (e.flag.message != null) {
      store.eveUserCount = e.flag.message;
    }
  });
});

onBeforeUnmount(async () => {
  await Echo.leave("evestuff");
});
let leftDrawerOpen = $ref(false);
let showFeedback = $ref(false);
let feedBackText = $ref("");

let logout = () => {
  window.location.href = "/logout";
};

let submitFeedBack = () => {
  let request = {
    user_id: store.user_id,
    text: feedBackText,
  };

  axios({
    method: "post",
    url: "/api/feedback",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  showFeedback = false;
  feedBackText = null;
};

let closeFeedBack = () => {
  showFeedback = false;
  feedBackText = null;
};

let onResize = (size) => {
  store.size = size;
};

let operationText = () => {
  if (can("recon_role")) {
    return "Cyno Sheet";
  } else {
    return "Operations";
  }
};
</script>
