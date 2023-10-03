<template>
  <div class="row flex-center">
    <q-btn
      v-if="showButton == 1"
      :color="filterCharsOnTheWay"
      class="myOutLineButtonMid"
      no-caps
      label="On The Way"
      rounded
      ><q-menu>
        <q-list style="min-width: 100px">
          <q-item
            clickable
            v-close-popup
            v-for="(list, index) in freeChars"
            :key="index"
            @click="clickOnTheWay(list.id)"
          >
            <q-item-section>{{ list.name }}</q-item-section>
          </q-item>
        </q-list>
      </q-menu></q-btn
    >

    <q-btn
      v-else-if="showButton == 2"
      :color="filterCharsOnTheWay"
      class="myOutLineButtonMid"
      no-caps
      label="On The Way"
      rounded
    />
    <span v-else> On The Way - </span>
    <q-btn
      color="green-4"
      :label="onTheWayCount"
      :disabled="fabOnTheWayDisbale"
      size="sm"
      round
    >
      <q-menu>
        <q-list style="min-width: 100px">
          <q-item v-close-popup v-for="(list, index) in charsOnTheWayAll" :key="index">
            <q-item-section>
              <div class="row">
                {{ list.name }} - {{ list.ship }} - T{{ list.entosis }}
                <q-btn
                  v-if="seeReadyToGoOnTheWay(list)"
                  flat
                  class="q-ml-sm"
                  padding="none"
                  round
                  color="warning"
                  icon="fa-solid fa-trash-can"
                  size="xs"
                  @click="removeReadyToGoOnTheWay(list.id)"
                />
              </div>
            </q-item-section>
          </q-item>
        </q-list> </q-menu
    ></q-btn>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { inject } from "vue";

let can = inject("can");
const store = useMainStore();
const props = defineProps({
  item: Object,
  operationID: Number,
});

let clickOnTheWay = async (opUserID) => {
  var data = {
    user_status_id: 2,
    system_id: props.item.id,
  };

  await axios({
    method: "put",
    url: "/api/onthewayreadytogo/" + props.operationID + "/" + opUserID,
    data: data,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let seeReadyToGoOnTheWay = (item) => {
  if (can("campaigns_admin_access") || store.user_id == item.user_id) {
    return true;
  } else {
    return false;
  }
};

let removeReadyToGoOnTheWay = (opUserID) => {
  var data = {
    user_status_id: 1,
    system_id: null,
  };

  axios({
    method: "put",
    url: "/api/onthewayreadytogo/" + props.operationID + "/" + opUserID,
    data: data,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let showButton = $computed(() => {
  if (allOwnHackingCharsCount > 0) {
    if (freeCharsCount > 0) {
      return 1;
    } else {
      return 2;
    }
  } else {
    return 0;
  }
});

let allOwnHackingChars = $computed(() => {
  var data = store.getOwnHackingCharOnOpAllHackers(props.operationID);
  if (data) {
    return data;
  } else {
    return [];
  }
});

let allOwnHackingCharsCount = $computed(() => {
  if (allOwnHackingChars) {
    return allOwnHackingChars.length;
  } else {
    return 0;
  }
});

let freeChars = $computed(() => {
  var data = store.getOwnHackingCharOnOpAllHackers(props.operationID);
  if (data) {
    data = data.filter((c) => c.user_status_id != 4);
    data = data.filter((c) => {
      if (c.system_id == props.item.id) {
        if (c.user_status_id != 2) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    });
  }
  if (data) {
    return data;
  } else {
    return [];
  }
});

let freeCharsCount = $computed(() => {
  if (freeChars) {
    return freeChars.length;
  } else {
    return 0;
  }
});

let charsOnTheWayAll = $computed(() => {
  return store.getOpUsersOnTheWayAll.filter((q) => q.system_id == props.item.id);
});

let onTheWayCount = $computed(() => {
  if (charsOnTheWayAll) {
    return charsOnTheWayAll.length;
  } else {
    return 0;
  }
});

let fabOnTheWayDisbale = $computed(() => {
  if (onTheWayCount == 0) {
    return true;
  } else {
    return false;
  }
});

let filterCharsOnTheWay = $computed(() => {
  var data = store.getOwnHackingCharOnOp(props.operationID);
  if (data) {
    var count = data.filter((c) => c.system_id == props.item.id && c.user_status_id == 2)
      .length;
  } else {
    var count = 0;
  }

  if (count > 0) {
    return "green";
  } else {
    return "red";
  }
});
</script>

<style lang="scss"></style>
