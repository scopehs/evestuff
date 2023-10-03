<template>
  <div class="row justify-center">
    <q-btn :color="pillColor()" :label="buttontext(item)" rounded
      ><q-menu>
        <q-list style="min-width: 100px">
          <!-- <q-item
            v-if="props.item.status.id != 16"
            clickable
            v-close-popup
            @click="statusUpdate(16)"
          >
            <q-item-section class="text-green">Online</q-item-section>
          </q-item> -->
          <q-list style="min-width: 100px">
            <q-item
              clickable
              v-close-popup
              v-for="(list, index) in dropDownFilter"
              :key="index"
              @click="statusUpdate(list.value)"
            >
              <q-item-section>{{ list.title }}</q-item-section>
            </q-item>
          </q-list>
        </q-list>
      </q-menu></q-btn
    >
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});

let statusUpdate = async (statusID) => {
  var request = null;
  request = {
    station_status_id: statusID,
  };
  await axios({
    method: "put",
    url: "/api/stationsheet/updatestationnotification/" + props.item.id,
    data: request,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let dropdownStatus = $ref([
  { title: "New", value: 1 },
  { title: "Online", value: 16 },
  { title: "Armor", value: 8 },
  { title: "Hull", value: 9 },
  { title: "Destroyed", value: 7 },
  { title: "Unknown", value: 18 },
]);

let dropDownFilter = $computed(() => {
  return dropdownStatus.filter((f) => f.value != props.item.status.id);
});

let pillColor = () => {
  if (props.item.status.id == 8) {
    return "warning";
  }

  if (props.item.status.id == 9) {
    return "deep-orange-13";
  }
  if (props.item.status.id == 18) {
    return "blue-grey-8";
  }
  if (props.item.status.id == 16) {
    return "positive";
  }
  if (props.item.status.id == 7) {
    return "negative";
  }
  return "primary";
};

let buttontext = () => {
  let ret = props.item.status.name.replace("Upcoming - ", "").replace("Reffed - ", "");
  return ret;
};
</script>

<style lang="scss"></style>
