<template>
  <div>
    <q-btn class="myOutLineButtonLarge" size="md" :color="buttonColor" rounded>
      <template v-slot:default>
        <span v-if="!props.item.end_time">Add Timer</span>
        <VueCountDown
          v-if="props.item.end_time && !isOver"
          :time="countDownTimeMil(props.item.end_time)"
          :interval="1000"
          :emit-events="true"
          @end="endHack"
          v-slot="{ hours, minutes, seconds }"
          :transform="transformSlotProps"
        >
          <!-- <span :class="hackCountDownTextColor" v-if="props.node.node_status.id != 8"
        ><span v-if="hours > 0">{{ hours }}:</span>{{ minutes }}:{{ seconds }}</span
      > -->

          {{ minutes }}:{{ seconds }}
        </VueCountDown>
        <span v-if="isOver">{{ overText }}</span>

        <q-menu>
          <q-card class="my-card">
            <q-card-section>
              <q-input
                v-model="hackTime"
                type="text"
                label="Hack Time mm:ss"
                mask="##:##"
                autofocus
                rounded
                outlined
              />
            </q-card-section>
            <q-card-actions align="center">
              <q-btn
                color="positive"
                rounded
                :label="AddOrEdit"
                v-close-popup
                @click="addHackTime()"
              />
              <q-btn color="negative" rounded label="Close" v-close-popup />
              <q-btn
                color="info"
                rounded
                label="Clear"
                v-close-popup
                @click="clearHackTime()"
              />
            </q-card-actions>
          </q-card>
        </q-menu>
      </template>
    </q-btn>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
const VueCountDown = defineAsyncComponent(() => import("../countdown/index"));
const props = defineProps({
  item: Object,
});

onMounted(async () => {
  checkTime();
});

let isOver = $ref(false);
let hackTime = $ref(null);

let endHack = () => {
  isOver = true;
};

let checkTime = () => {
  if (props.item.end_time) {
    const utcTime = new Date(props.item.end_time + "Z");
    const currentTimestamp = Date.now();
    const timeDiff = utcTime.getTime() - currentTimestamp;
    if (timeDiff < 0) {
      isOver = true;
    }
  }
};

let addHackTime = async () => {
  var min = parseInt(hackTime.substr(0, 2));
  var sec = parseInt(hackTime.substr(3, 2));
  var base = min * 60 + sec;
  sec = min * 60 + sec;
  //   if (props.extra == 2) {
  //     var sec = sec / (props.tidiProp / 100);
  //   } else {
  //     var sec = sec / (props.node.system.tidi / 100);
  //   }

  var sec = sec / (100 / 100);

  var finishTime = new Date(Date.now() + sec * 1000)
    .toISOString()
    .replace("T", " ")
    .substr(0, 19);

  var data = {
    end_time: finishTime,
  };
  await axios({
    method: "put",
    withCredentials: true, //you can set what request you want to be
    url: "/api/notifications/" + props.item.id,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let clearHackTime = async () => {
  var data = {
    end_time: null,
  };
  await axios({
    method: "put",
    withCredentials: true, //you can set what request you want to be
    url: "/api/notifications/" + props.item.id,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let countDownTimeMil = (time) => {
  const utcTime = new Date(time + "Z");
  const currentTimestamp = Date.now();
  const timeDiff = utcTime.getTime() - currentTimestamp;
  return timeDiff;
};

let transformSlotProps = (props) => {
  const formattedProps = {};

  Object.entries(props).forEach(([key, value]) => {
    formattedProps[key] = value < 10 ? `0${value}` : String(value);
  });

  return formattedProps;
};

let buttonColor = $computed(() => {
  if (props.item.end_time) {
    return "red";
  } else {
    return "green";
  }
});

let AddOrEdit = $computed(() => {
  if (props.item.end_time) {
    return "Edit";
  } else {
    return "Add";
  }
});

let overText = $computed(() => {
  if (props.item.status_id == 5) {
    return "Was It Reffed?";
  } else {
    return "Is it Secure?";
  }
});
</script>

<style lang="scss"></style>
