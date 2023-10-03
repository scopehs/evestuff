<template>
  <div>
    <q-btn
      text-color="positive"
      :icon="messageIcon"
      padding="none"
      round
      flat
      @click="showStationNotes = true"
      ><q-badge v-if="messageUnreadCount && !showStationNotes" color="red" floating>{{
        messageUnreadCount
      }}</q-badge></q-btn
    >
    <q-dialog v-model="showStationNotes" @before-show="open()" @before-hide="close()">
      <q-card
        class="my-card myRoundTop"
        style="width: 1000px; max-height: 900px; height: 900px"
      >
        <q-card-section class="bg-primary myCardHeader text-center">
          <div class="text-h6">Notes for Station {{ props.station.name }}</div>
        </q-card-section>
        <q-card-section id="messages" class="overflow-auto" style="height: 600px">
          <transition-group enter-active-class="animate__animated animate__zoomIn">
            <q-chat-message
              v-for="(message, index) in messages"
              :key="`${message.id}-message`"
              :text="[message.message]"
              :name="name(message.user.name, message.user_id)"
              :avatar="url(message)"
              :sent="sent(message.user_id)"
              :bg-color="messageColor(message.user_id)"
            >
              <template :key="`${message.id}-stamp`" v-slot:stamp
                >Sent:
                <VueCountUp
                  :key="`${message.id}-time`"
                  :interval="60000"
                  :time="age(message.created_at)"
                  v-slot="{ days, hours, minutes, seconds }"
                >
                  <span v-if="days != '00'">{{ days }}days, </span
                  ><span v-if="hours != '00'">{{ hours }}hours,</span
                  ><span> {{ minutes }}minutes</span>
                  ago
                </VueCountUp>
                <q-btn
                  v-if="olderThan(message.created_at) && sent(message.user_id)"
                  color="negative"
                  padding="none"
                  icon="fa-solid fa-trash-can"
                  flat
                  size="xs"
                  rounded
                  @click="remove(message.id)"
                />
              </template>
            </q-chat-message>
          </transition-group>
        </q-card-section>
        <q-card-section>
          <div>
            <q-input
              input-style="height: 150px"
              v-model="mainText"
              clearable
              outlined
              rounded
              dense
              type="textarea"
              label="Message"
            />
          </div>
        </q-card-section>
        <q-card-actions align="between">
          <q-btn
            rounded
            label="Submit"
            color="primary"
            :disable="showSubmit"
            @click="sendMessage()"
          />
          <q-btn rounded label="Close" color="negative" @click="close()" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import axios from "axios";
let can = inject("can");
let store = useMainStore();

const props = defineProps({
  station: Object,
  type: Number,
});
const VueCountUp = defineAsyncComponent(() => import("../countup/index"));
onMounted(async () => {});
let mainText = $ref();
let showStationNotes = $ref(false);

const remove = async (id) => {
  await axios({
    method: "delete",
    url: "/api/sheetmessage/" + id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

const sendMessage = async () => {
  const data = {
    message: mainText,
  };

  await axios({
    method: "put",
    url: "/api/sheetmessage/" + props.station.id,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  mainText = null;
};

let open = async () => {
  store.stationChatWindowId = props.station.id;
};

let close = async () => {
  mainText = null;
  store.stationChatWindowId = null;
};

let age = (val) => {
  let date = new Date(val);
  let timestamp = date.getTime();
  return timestamp;
};

let olderThan = (val) => {
  const timestampDate = new Date(val);
  const timeDiff = new Date() - timestampDate;
  const isLessThan30MinsOld = timeDiff < 1800000;
  return isLessThan30MinsOld;
};

let messageCount = $computed(() => {
  return messages.length;
});

let messageUnreadCount = $computed(() => {
  return store.getUnreadMessageCount(props.station.id);
});

let messageIcon = $computed(() => {
  if (messageCount) {
    return "fa-solid fa-message";
  } else {
    return "fa-regular fa-message";
  }
});

let sent = (id) => {
  if (store.user_id == id) {
    return true;
  } else {
    return false;
  }
};

let name = (textname, id) => {
  if (store.user_id == id) {
    return "me";
  } else {
    return textname;
  }
};

let messageColor = (id) => {
  if (store.user_id == id) {
    return "amber-7";
  } else {
    return "positive";
  }
};

let showSubmit = $computed(() => {
  if (mainText) {
    return false;
  }
  return true;
});

let messages = $computed(() => {
  return store.getStationMessages(props.station.id);
});

let url = (item) => {
  return "https://image.eveonline.com/Character/" + item.user.eve_user_id + "_128.jpg";
};
</script>

<style lang="scss">
#messages {
  display: flex;
  flex-direction: column-reverse;
  height: 100px;
  overflow-y: scroll;
}
</style>
