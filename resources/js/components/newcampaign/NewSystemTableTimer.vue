<template>
  <div class="row">
    <div class="col">
      <VueCountUp
        v-if="showAgeCountUp"
        :time="countUpTimeMil(timeMoment)"
        :emit-events="false"
        v-slot="{ hours, minutes, seconds }"
        :interval="1000"
      >
        <div
          :key="`${props.node.id}-1-timer-age`"
          v-if="clockRedText(hours, hours, seconds)"
          class="text-positive"
        >
          {{ hours }}:{{ minutes }}:{{ seconds }}
        </div>
        <div :key="`${props.node.id}-2-timer-age`" v-else class="text-negative">
          {{ hours }}:{{ minutes }}:{{ seconds }}
        </div>
      </VueCountUp>

      <q-btn
        v-else-if="checkHackUser"
        color="warning"
        :outline="pillOutlined"
        label="Add time"
        rounded
        class="myOutLineButton"
        ><q-menu>
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
                label="Add"
                v-close-popup
                @click="addHackTime()"
              />
              <q-btn color="negative" rounded label="Close" v-close-popup />
            </q-card-actions>
          </q-card> </q-menu
      ></q-btn>
      <VueCountDown
        v-else
        :time="countDownTimeMil(startTime)"
        :interval="1000"
        :emit-events="false"
        v-slot="{ hours, minutes, seconds }"
        :transform="transformSlotProps"
      >
        <span :class="hackCountDownTextColor" v-if="props.node.node_status.id != 8"
          ><span v-if="hours > 0">{{ hours }}:</span>{{ minutes }}:{{ seconds }}</span
        >

        <q-chip color="blue-14" v-else
          ><span v-if="hours > 0">{{ hours }}:</span>{{ minutes }}:{{ seconds }}</q-chip
        >
      </VueCountDown>
      <q-btn
        color="warning"
        icon="fa-solid fa-edit"
        round
        flat
        size="xs"
        v-if="checkHackUserEdit"
        ><q-menu @before-hide="hackTime = null">
          <q-card class="my-card">
            <q-card-section>
              <q-input
                v-model="hackTime"
                type="text"
                label="Hack Time mm:ss"
                mask="##:##"
                outlined
                rounded
              />
            </q-card-section>
            <q-card-actions align="center">
              <q-btn
                rounded
                color="positive"
                label="update"
                v-close-popup
                @click="addHackTime()"
              />
              <q-btn rounded color="negative" label="close" v-close-popup />
            </q-card-actions>
          </q-card> </q-menu
      ></q-btn>
    </div>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";

const VueCountUp = defineAsyncComponent(() => import("../countup/index"));
const VueCountDown = defineAsyncComponent(() => import("../countdown/index"));

const props = defineProps({
  node: Object,
  operationID: Number,
  extra: {
    type: Number,
    default: 1,
  },
  tidiProp: Number,
  systemIDProp: Number,
});

let hackTime = $ref(null);

let pillOutlined = $computed(() => {
  if (props.node.node_status.id == 7 || props.node.node_status.id == 8) {
    return false;
  } else {
    return true;
  }
});

let addHackTime = async () => {
  var min = parseInt(hackTime.substr(0, 2));
  var sec = parseInt(hackTime.substr(3, 2));
  var base = min * 60 + sec;
  sec = min * 60 + sec;
  if (props.extra == 2) {
    var sec = sec / (props.tidiProp / 100);
  } else {
    var sec = sec / (props.node.system.tidi / 100);
  }

  var finishTime = new Date(Date.now() + sec * 1000)
    .toISOString()
    .replace("T", " ")
    .substr(0, 19);
  if (
    props.node.node_status.id == 7 ||
    props.node.node_status.id == 8 ||
    props.node.node_status.id == 9
  ) {
    var request = {
      end_time: finishTime,
      input_time: new Date().toISOString().replace("T", " ").substr(0, 19),
      base_time: base,
      system_id: props.node.system_id,
    };

    await axios({
      method: "put",
      url: "/api/addtimertonode/" + props.node.id,
      withCredentials: true,
      data: request,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
  } else {
    if (props.extra == 1) {
      var url = opUserInfo.id;
      var request = {
        end_time: finishTime,
        input_time: new Date().toISOString().replace("T", " ").substr(0, 19),
        base_time: base,
        system_id: props.node.system_id,
      };
    } else {
      var url = props.node.id;
      var request = {
        end_time: finishTime,
        input_time: new Date().toISOString().replace("T", " ").substr(0, 19),
        base_time: base,
        system_id: props.systemIDProp,
      };
    }

    await axios({
      method: "put",
      url: "/api/addprimetimer/" + url,
      withCredentials: true,
      data: request,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
  }
};

let opUserInfo = $computed(() => {
  if (props.extra == 1) {
    if (props.node.prime_node_user.length > 0) {
      return props.node.prime_node_user[0];
    } else {
      return null;
    }
  } else {
    return null;
  }
});

let countUpTimeMil = (time) => {
  const timestamp = Date.parse(time);
  return timestamp;
};

let timeMoment = $computed(() => {
  if (props.extra == 1) {
    if (props.node.node_status.id == 2) {
      var updatedAtDate = new Date(opUserInfo.updated_at);
      return (
        updatedAtDate.getUTCFullYear() +
        "-" +
        ("0" + (updatedAtDate.getUTCMonth() + 1)).slice(-2) +
        "-" +
        ("0" + updatedAtDate.getUTCDate()).slice(-2) +
        " " +
        ("0" + updatedAtDate.getUTCHours()).slice(-2) +
        ":" +
        ("0" + updatedAtDate.getUTCMinutes()).slice(-2) +
        ":" +
        ("0" + updatedAtDate.getUTCSeconds()).slice(-2)
      );
    } else {
      var createdAtDate = new Date(props.node.created_at);
      return (
        createdAtDate.getUTCFullYear() +
        "-" +
        ("0" + (createdAtDate.getUTCMonth() + 1)).slice(-2) +
        "-" +
        ("0" + createdAtDate.getUTCDate()).slice(-2) +
        " " +
        ("0" + createdAtDate.getUTCHours()).slice(-2) +
        ":" +
        ("0" + createdAtDate.getUTCMinutes()).slice(-2) +
        ":" +
        ("0" + createdAtDate.getUTCSeconds()).slice(-2)
      );
    }
  } else {
    var createdDate = new Date(props.node.created_at);
    return createdDate.toISOString();
  }
});

let showAgeCountUp = $computed(() => {
  if (props.extra == 1) {
    if (props.node.prime_node_user.length > 0) {
      var use = props.node.prime_node_user[0].node_status.id;
    } else {
      var use = props.node.node_status.id;
    }
  } else {
    var use = props.node.node_status.id;
  }

  switch (use) {
    case 1: // * New
      return true;

    case 2: // * Warm up
      return true;

    case 3: // * Hacking
      return false;

    case 4: // * Success
      return false;

    case 5: // * Failed
      return false;

    case 6: // * Pushed off
      return true;

    case 7: // * Hostile Hacking
      return false;

    case 8: // * Friendly Hacking
      return false;

    case 9: // * Passive
      return false;

    case 10: // * Other
      return true;
  }
});

let clockRedText = (h, m, s) => {
  if ((m < 5 || h > 0) && props.node.node_status.id == 1) {
    return true;
  } else if ((m < 10 || h > 0) && props.node.node_status.id == 2) {
    return true;
  }

  return false;
};

let checkHackUser = $computed(() => {
  if (opUserInfo) {
    if (opUserInfo.end_time == null && opUserInfo.node_status.id == 3) {
      return true;
    } else if (
      opUserInfo.end_time == null &&
      (props.node.node_status.id == 7 ||
        props.node.node_status.id == 8 ||
        props.node.node_status.id == 9)
    ) {
      return true;
    } else {
      return false;
    }
  } else {
    if (props.node.node_status.id == 3 && props.node.end_time == null) {
      return true;
    } else if (
      (props.node.node_status.id == 7 ||
        props.node.node_status.id == 8 ||
        props.node.node_status.id == 9) &&
      props.node.end_time == null
    ) {
      return true;
    } else {
      return false;
    }
  }
});

let startTime = $computed(() => {
  if (props.extra == 1) {
    if (opUserInfo) {
      return opUserInfo.end_time;
    } else if (
      props.node.node_status.id == 7 ||
      props.node.node_status.id == 8 ||
      props.node.node_status.id == 9
    ) {
      return props.node.end_time;
    } else {
      return null;
    }
  } else {
    if (props.node.end_time) {
      return props.node.end_time;
    }
  }
});

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

let hackCountDownTextColor = $computed(() => {
  if (props.node.node_status.id == 7) {
    return "text-white";
  } else {
    return "text-primary";
  }
});

let checkHackUserEdit = $computed(() => {
  if (props.extra == 1) {
    if (props.node.prime_node_user.length > 0) {
      var use = opUserInfo.node_status.id;
    } else {
      var use = props.node.node_status.id;
    }
  } else {
    var use = props.node.node_status.id;
  }

  if (
    use == 7 || // * Hostile Hacking
    use == 8 || // * Friendly Hacking
    use == 3 || // * Hacking
    use == 9 // * Passive
  ) {
    return true;
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>
