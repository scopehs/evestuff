<template>
  <div class="q-ma-md">
    <div class="row">
      <div class="col q-pb-md">
        <CampaignTitleBar
          :operationID="operationID"
          :title="store.newOperationInfo.title"
          :activeCampaigns="activeCampaigns"
          :warmUpCampaigns="warmUpCampaigns"
        />
      </div>
    </div>
    <div class="row">
      <div class="col">
        <CampaignActiveBar :operationID="operationID"></CampaignActiveBar>
      </div>
    </div>
    <div class="row">
      <div class="col-6 q-pa-sm" v-for="(item, index) in openSystems" :key="index.id">
        <transition
          name="custom-classes"
          enter-active-class="animate__animated animate__heartBeat animate__repeat-2"
          leave-active-class="animate__animated animate__flash"
          mode="out-in"
        >
          <CampaignSystemCard
            :key="`${index.id}-card`"
            :item="item"
            :operationID="operationID"
          ></CampaignSystemCard>
        </transition>
      </div>
    </div>

    <q-dialog v-model="showOverlay1" persistent>
      <q-card class="myRoundTop">
        <q-card-section class="bg-primary text-h5 text-center">
          <h5 class="no-margin">MAKE SURE TO ADD A CHARACTER</h5>
        </q-card-section>
        <q-card-section>
          Remeber to add any chars you have in the campaign by pressing the green
          "CHARACTER" button
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            rounded
            label="Close"
            color="negative"
            @click="store.newOperationMessageOverlay = 0"
          />
          <q-btn
            rounded
            label="Add Character"
            color="secondary"
            @click="addCharOverlay()"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showOverlay2" persistent>
      <q-card class="myRoundTop">
        <q-card-section class="bg-primary text-h5 text-center">
          <h5 class="no-margin">WHAT TO DO</h5>
        </q-card-section>
        <q-card-section>
          Thanks for participating in this Entosis campaign. In order to participate, we
          need you to add the characters you are using to actively hack to this tool -
          don't worry - we don't require an ESI link.
        </q-card-section>
        <q-card-section>
          To do this, press the "CHARACTERS" button and pick **Hacker** as the role for
          each alt.
        </q-card-section>
        <q-card-section>
          Once you have added all of your characters, await instructions from your FC. You
          will be assigned a specific system, which is done in-game via Squads.
        </q-card-section>
        <q-card-section>
          Check the name of your squad for the assigned system. Once instructed, move to
          this system and prepare to hack. If in doubt, ask for help.
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            rounded
            label="Close"
            color="negative"
            @click="store.newOperationMessageOverlay = 0"
          />
          <q-btn
            rounded
            label="Add Character"
            color="secondary"
            @click="addCharOverlay()"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useRoute } from "vue-router";
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

let route = useRoute();
let store = useMainStore();
let can = inject("can");

const CampaignTitleBar = defineAsyncComponent(() =>
  import("../components/newcampaign/CampaignTitleBar.vue")
);

const CampaignSystemCard = defineAsyncComponent(() =>
  import("../components/newcampaign/CampaignSystemCard.vue")
);

const CampaignActiveBar = defineAsyncComponent(() =>
  import("../components/newcampaign/CampaignActiveBar.vue")
);

onMounted(async () => {
  await store.getOperationInfo(route.params.id);
  await store.getCampaignsList(operationID);

  document.title = "Evestuff - " + store.newOperationInfo.title;
  Echo.private("operations." + operationID).listen("OperationUpdate", (e) => {
    if (e.flag.flag == 1) {
    }

    // * Set ReadOnlyFlag
    if (e.flag.flag == 2) {
      store.newOperationInfo.read_only = e.flag.message;
    }

    // * refresh Op info
    if (e.flag.flag == 3) {
      store.newOperationInfo = e.flag.message;
    }

    // * solo system update
    if (e.flag.flag == 4) {
      store.updateNewCampaigns(e.flag.message);
    }

    // * 5 is to remove op char from  chartable
    if (e.flag.flag == 5) {
      store.removeCharfromOpList(e.flag.userid);
    }

    // * 6 update op char table
    if (e.flag.flag == 6) {
      store.updateOpChar(e.flag.message);
    }

    if (e.flag.flag == 7) {
      store.updateNewCampaignSystem(e.flag.message);
    }

    if (e.flag.flag == 8) {
      store.updateNewCampaigns(e.flag.message.campaign[0]);
    }

    if (e.flag.flag == 9) {
      store.state.newCampaignSystems = e.flag.message;
    }
  });
  Echo.private("operationsown." + store.user_id + "-" + operationID).listen(
    "OperationOwnUpdate",
    (e) => {
      if (e.flag.flag == 1) {
        store.newOperationMessageOverlay = parseInt(e.flag.type);
      }

      if (e.flag.flag == 2) {
      }
      // * 3 add/update char to char table
      if (e.flag.flag == 3) {
        store.updateNewOwnChar(e.flag.message);
      }
      if (e.flag.flag == 4) {
      }
      // * 5 is to remove op char from own list
      if (e.flag.flag == 5) {
        store.removeCharfromOwnList(e.flag.userid);
      }

      if (e.flag.flag == 6) {
      }

      if (e.flag.flag == 7) {
      }

      if (e.flag.flag == 8) {
      }
    }
  );

  if (can("view_campaign_members")) {
    Echo.private("operationsadmin." + operationID).listen("OperationAdminUpdate", (e) => {
      // update watching user list
      if (e.flag.flag == 1) {
        store.operationUserList = e.flag.message;
      }

      if (e.flag.flag == 2) {
      }
      if (e.flag.flag == 3) {
      }
      if (e.flag.flag == 4) {
      }
      if (e.flag.flag == 5) {
      }

      if (e.flag.flag == 6) {
      }

      if (e.flag.flag == 7) {
      }

      if (e.flag.flag == 8) {
      }
    });
  }
});

onBeforeUnmount(async () => {
  Echo.leave("operations." + operationID);
  Echo.leave("operationsown." + store.user_id + "-" + operationID);
  Echo.leave("operationsadmin." + operationID);
});

let addCharOverlay = () => {
  store.newOperationMessageAddChar = true;
  store.newOperationMessageOverlay = 0;
};

let showOverlay1 = $computed(() => {
  return store.newOperationMessageOverlay == 1;
});

let showOverlay2 = $computed(() => {
  return store.newOperationMessageOverlay == 2;
});

let activeCampaigns = $computed(() => {
  var check = store.newCampaigns.length;
  if (check > 0) {
    var campaigns = store.newCampaigns.filter((c) => {
      if (c.status_id == 2) {
        return true;
      } else {
        return false;
      }
    });

    return campaigns;
  } else {
    return [];
  }
});

let warmUpCampaigns = $computed(() => {
  var check = store.newCampaigns.length;
  if (check > 0) {
    var campaigns = store.newCampaigns.filter((c) => {
      if (c.status_id == 5) {
        return true;
      } else {
        return false;
      }
    });

    return campaigns;
  } else {
    return [];
  }
});

let operationID = $computed(() => {
  return store.newOperationInfo.id;
});

let openCampaings = $computed(() => {
  if (activeCampaigns.length > 0 && warmUpCampaigns.length > 0) {
    let open = activeCampaigns.concat(warmUpCampaigns);
    open = open.filter((item, index) => {
      return open.indexOf(item) == index;
    });
    return open;
  } else if (activeCampaigns.length > 0) {
    return activeCampaigns;
  } else if (warmUpCampaigns.length > 0) {
    return warmUpCampaigns;
  } else {
    return [];
  }
});

let openCampaignIDs = $computed(() => {
  if (openCampaings.length > 0) {
    var ids = openCampaings.map((c) => c.id);
    return ids;
  } else {
    return [];
  }
});

let openSystems = $computed(() => {
  var systems = store.newCampaignSystems.filter((s) => {
    let systems = s.new_campaigns.filter((c) => openCampaignIDs.includes(c.id));
    if (systems.length > 0) {
      return true;
    } else {
      return false;
    }
  });
  return systems;
});
</script>

<style lang="scss"></style>
