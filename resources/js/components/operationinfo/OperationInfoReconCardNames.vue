<template>
  <div class="row justify-between" :key="`${recon.id}-card`">
    <div class="col-9 ellipsis" :class="textClass">
      <span class="cursor-pointer" @click="copyName(recon.name)">
        {{ recon.name }} - {{ recon.main.name }}</span
      >
      <q-tooltip v-if="recon.operation_info_recon_status_id == 2" :offset="[0, 10]"
        >{{ recon.name }} - {{ recon.main.name }} <br />
        Fleet - {{ recon.fleet.name }} <br />
        Role - {{ recon.fleet_role.name }}</q-tooltip
      >
      <q-tooltip v-if="recon.operation_info_recon_status_id == 3" :offset="[0, 10]"
        >{{ recon.name }} - {{ recon.main.name }} <br />
        System - {{ recon.system.system_name }}</q-tooltip
      >
      <q-tooltip v-if="recon.operation_info_recon_status_id == 4" :offset="[0, 10]">
        {{ recon.name }} - {{ recon.main.name }} <br />
        Fleet - {{ recon.fleet.name }} <br />
        Role - {{ recon.fleet_role.name }} <br />System -
        {{ recon.system.system_name }}</q-tooltip
      >

      <q-tooltip v-if="recon.operation_info_recon_status_id == 1" :offset="[0, 10]"
        >{{ recon.name }} - {{ recon.main.name }}</q-tooltip
      >
    </div>
    <div class="col-3 flex justify-end q-gutter-xs">
      <q-btn
        color="red"
        icon="fa-solid fa-trash"
        flat
        padding="none"
        size="sm"
        rounded
        @click="removeRecon(recon)"
      />
      <q-btn
        :color="deadColor"
        icon="fa-solid fa-skull-crossbones"
        flat
        padding="none"
        size="sm"
        rounded
        @click="dead(recon)"
      />
      <q-btn
        :color="onlineColor"
        icon="fa-solid fa-power-off"
        flat
        padding="none"
        size="sm"
        rounded
        @click="online(recon)"
      />
    </div>
  </div>
</template>

<script setup>
import { useQuasar, copyToClipboard } from "quasar";
const $q = useQuasar();
const props = defineProps({
  recon: Object,
});

let copyName = (name) => {
  copyToClipboard(name).then(() => {
    $q.notify({
      type: "info",
      message: name + " copied to your clipboard",
    });
  });
};

let textClass = $computed(() => {
  var a = textColor;
  var b = textCross;
  var c = textOnline;
  if (c || b) {
    return c + " " + b;
  } else {
    return a;
  }
});

let textColor = $computed(() => {
  if (props.recon.operation_info_recon_status_id == 1) {
    return "text-light-blue-2";
  }

  if (props.recon.operation_info_recon_status_id == 2) {
    return "text-green";
  }

  if (props.recon.operation_info_recon_status_id == 3) {
    return "text-blue";
  }

  if (props.recon.operation_info_recon_status_id == 4) {
    return "gradient-text";
  }
});

let textCross = $computed(() => {
  if (props.recon.dead == 1) {
    return "text-strike text-webway";
  } else {
    return "";
  }
});

let textOnline = $computed(() => {
  if (props.recon.online == 1) {
    return "";
  } else {
    return "text-webway text-italic";
  }
});

let deadColor = $computed(() => {
  if (props.recon.dead == 0) {
    return "blue";
  } else {
    return "red";
  }
});

let onlineColor = $computed(() => {
  if (props.recon.online == 0) {
    return "negative";
  } else {
    return "primary";
  }
});

let online = async (item) => {
  if (item.online == 0) {
    item.online = 1;
  } else {
    item.online = 0;
  }

  var request = item;
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/operationinforecononline/" + item.id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let removeRecon = async (item) => {
  var request = item;
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/operationinforeconremove/" + item.operation_info_id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let dead = async (item) => {
  if (item.dead == 0) {
    item.dead = 1;
  } else {
    item.dead = 0;
  }

  var request = item;
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/operationinforecondead/" + item.id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss">
.gradient-text {
  /* Fallback: Set a background color. */

  /* Create the gradient. */
  background: linear-gradient(to right, #2196f3, #4caf50);

  /* Set the background size and repeat properties. */
  background-size: 100%;
  background-repeat: repeat;

  /* Use the text as a mask for the background. */
  /* This will show the gradient as a text color rather than element bg. */
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;

  /* Animate the text when loading the element. */
  /* This animates it on page load and when hovering out. */
  animation: rainbow-text-simple-animation-rev 0.75s ease forwards;
}
</style>
