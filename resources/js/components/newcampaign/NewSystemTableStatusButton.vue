<template>
  <div>
    <q-btn
      :color="pillColor"
      rounded
      class="myOutLineButton"
      :label="nodeStatusName"
      :outline="pillOutlined"
      ><q-menu>
        <q-list style="min-width: 100px">
          <q-item
            clickable
            v-close-popup
            v-for="(list, index) in filterDropDown"
            :key="index"
            @click="statusClick(list)"
          >
            <q-item-section>{{ list.title }}</q-item-section>
          </q-item>
        </q-list>
      </q-menu></q-btn
    >
  </div>
</template>

<script setup>
const props = defineProps({
  node: Object,
  operationID: Number,
  extra: {
    type: Number,
    default: 1,
  },
});

let dropdown_edit = $ref([
  { title: "Warm up", value: 2 },
  { title: "Hacking", value: 3 },
  { title: "Friendly Hacking", value: 8 },
  { title: "Passive", value: 9 },
  { title: "Success", value: 4 },
  { title: "Pushed off", value: 6 },
  { title: "Hostile Hacking", value: 7 },
  { title: "Failed", value: 5 },
]);

let dropdown_edit_extra = $ref([
  { title: "Warm up", value: 2 },
  { title: "Hacking", value: 3 },
  { title: "Pushed off", value: 6 },
]);

let nodeStatusID = $computed(() => {
  if (props.extra == 2) {
    return props.node.node_status.id;
  } else if (props.node.prime_node_user.length > 0) {
    return props.node.prime_node_user[0].node_status.id;
  } else {
    return props.node.node_status.id;
  }
});

let pillOutlined = $computed(() => {
  if (nodeStatusID == 7 || nodeStatusID == 9 || nodeStatusID == 8) {
    return false;
  } else {
    return true;
  }
});

let nodeStatusName = $computed(() => {
  if (props.extra == 2) {
    return props.node.node_status.name;
  } else if (props.node.prime_node_user.length > 0) {
    return props.node.prime_node_user[0].node_status.name;
  } else {
    return props.node.node_status.name;
  }
});

let pillColor = $computed(() => {
  if (nodeStatusID == 1) {
    return "deep-orange-6";
  }
  if (nodeStatusID == 2) {
    return "lime-10";
  }
  if (nodeStatusID == 3) {
    return "green-9";
  }

  if (nodeStatusID == 8) {
    return "blue-10";
  }
  if (nodeStatusID == 4) {
    return "green-14";
  }
  if (nodeStatusID == 5) {
    return "red-10";
  }
  if (nodeStatusID == 6) {
    return "pink-12";
  }
  if (nodeStatusID == 7) {
    return "red-6";
  }
  if (nodeStatusID == 9) {
    return "grey-6";
  }
});

let filterDropDownStart = $computed(() => {
  if (props.node.prime_node_user.length > 0) {
    var filter = dropdown_edit.filter(
      (d) => d.value == 1 || d.value == 2 || d.value == 3 || d.value == 4 || d.value == 6
    );
  } else {
    var filter = dropdown_edit.filter(
      (d) =>
        d.value == 1 ||
        d.value == 8 ||
        d.value == 9 ||
        d.value == 4 ||
        d.value == 6 ||
        d.value == 7 ||
        d.value == 5
    );
  }
  return filter;
});

let filterDropDown = $computed(() => {
  if (props.extra == 1) {
    var list = filterDropDownStart.filter((f) => f.value != nodeStatusID);
  } else {
    var list = dropdown_edit_extra.filter((f) => f.value != nodeStatusID);
  }

  return list;
});

let statusClick = async (list) => {
  if (props.extra == 1) {
    if (props.node.prime_node_user.length > 0) {
      var data = {
        status_id: list.value,
        system_id: props.node.system_id,
        opID: props.operationID,
        extra: false,
        prime: true,
      };
    } else {
      var data = {
        status_id: list.value,
        system_id: props.node.system_id,
        opID: props.operationID,
        extra: false,
        prime: false,
      };
    }
  } else {
    var data = {
      status_id: list.value,
      system_id: props.node.op_user.system_id,
      opID: props.operationID,
      extra: true,
      prime: false,
    };
  }
  await axios({
    method: "post",
    url: "/api/updatenodestats/" + nodeID,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let nodeID = $computed(() => {
  if (props.extra == 2) {
    return props.node.id;
  } else if (props.node.prime_node_user.length > 0) {
    return props.node.prime_node_user[0].id;
  } else {
    return props.node.id;
  }
});
</script>

<style lang="scss"></style>
