<template>
  <div class="row align-baseline">
    <div class="col text-h5">
      System TiDi
      <span class="cursor-pointer" :class="colorTidi" @click="dance"
        >{{ props.item.tidi }}%

        <q-menu
          @before-show="editTidi = props.item.tidi"
          @before-hide="editTidi = null"
          persistent
        >
          <q-card class="myRoundTop">
            <q-card-section>
              <q-input
                v-model="editTidi"
                autofocus
                outlined
                rounded
                type="text"
                label="Tidi %"
              />
            </q-card-section>
            <q-card-actions align="center">
              <q-btn
                rounded
                :disable="hideUpdateButton"
                color="positive"
                label="Update"
                @click="updateTidi"
                v-close-popup
              />
              <q-btn rounded color="negative" label="Close" v-close-popup />
            </q-card-actions>
          </q-card>
        </q-menu>
      </span>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
  operationID: Number,
});

let editTidi = $ref(null);

let updateTidi = async () => {
  var data = {
    tidi: editTidi,
  };

  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/edittidi/" + props.item.id,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let hideUpdateButton = $computed(() => {
  if (editTidi == null) {
    return true;
  }
  if (editTidi > 100 || editTidi < 0) {
    return true;
  }
  return false;
});

const colorTidi = $computed(() => {
  if (props.item.tidi > 59) {
    return "text-green text-bold";
  } else if (props.item.tidi > 34) {
    return "text-orange text-bold";
  } else {
    return "text-red text-bold";
  }
});
</script>

<style lang="scss"></style>
