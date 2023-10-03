<template>
  <div>
    <q-btn
      v-if="props.type == 1"
      color="positive"
      icon="fa-solid fa-plus"
      flat
      label="Char"
      @click="confirm = !confirm"
    />
    <q-btn
      v-if="props.type == 2"
      color="warning"
      icon="fa-solid fa-edit"
      flat
      round
      padding="none"
      size="xs"
      @click="confirm = !confirm"
    />
    <!-- style="width: 700px; max-width: 80vw" -->
    <q-dialog
      @before-show="open()"
      @before-hide="close()"
      class="myRoundTop"
      v-model="confirm"
      persistent
    >
      <q-card class="myRoundTop" flat>
        <q-card-section class="bg-primary text-center">
          <h5 class="no-margin">{{ headerText }}</h5>
        </q-card-section>
        <q-card-section>
          <div class="colum q-gutter-lg">
            <div class="col">
              <q-input outlined rounded v-model="pickedName" type="text" label="Name" />
            </div>
            <div class="col">
              <q-select
                option-label="text"
                option-value="value"
                outlined
                rounded
                v-model="pickedRole"
                :options="roleOperations"
                label="Role"
              />
            </div>
            <q-slide-transition>
              <div class="col" v-if="showExtra">
                <q-input outlined rounded v-model="pickedShip" type="text" label="Ship" />
              </div>
            </q-slide-transition>
            <q-slide-transition>
              <div class="col" v-if="showExtra">
                <div class="colum">
                  <div class="col flex flex-center">
                    <span class="text-caption"> Entosis Link</span>
                  </div>
                  <div class="col">
                    <q-radio v-model="newLink" :val="1" label="Tech 1" />
                    <q-radio v-model="newLink" :val="2" label="Tech 2" />
                  </div>
                </div>
              </div>
            </q-slide-transition>
          </div>
        </q-card-section>
        <q-card-actions align="center">
          <q-btn
            rounded
            color="positive"
            :disable="!showAddButton"
            :label="buttonText"
            v-close-popup
            @click="addChar()"
          />
          <q-btn rounded color="negative" label="Close" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";

let store = useMainStore();

const props = defineProps({
  operationID: Number,
  type: { type: Number, default: 1 },
  char: { type: Object, required: false },
});
let confirm = $ref(false);
let roleOperations = $ref([
  { text: "Hacker", value: 1 },
  { text: "Support", value: 5 },
  { text: "Scout", value: 2 },
  { text: "FC", value: 3 },
  { text: "Command", value: 4 },
]);

let pickedName = $ref();
let pickedRole = $ref();
let pickedShip = $ref();
let newLink = $ref();

let showExtra = $computed(() => {
  if (pickedRole) {
    if (pickedRole.value == 1) {
      return true;
    }
  }
  return false;
});

let close = () => {
  confirm = false;
  pickedName = null;
  pickedRole = null;
  pickedShip = null;
  newLink = null;
};

let showAddButton = $computed(() => {
  if (showExtra) {
    if (pickedName && pickedRole && pickedShip && newLink) {
      return true;
    }
  } else {
    if (pickedName && pickedRole) {
      return true;
    }
  }
  return false;
});

let addChar = async () => {
  if (props.type == 1) {
    await newChar();
  } else {
    await updateChar();
  }
  close();
};

let newChar = async () => {
  var data = {
    user_id: store.user_id,
    operation_id: props.operationID,
    name: pickedName,
    entosis: newLink,
    ship: pickedShip,
    role_id: pickedRole.value,
    user_status_id: 1,
  };
  await axios({
    method: "POST",
    url: "/api/newcampaignusers/" + props.operationID + "/" + store.user_id,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let updateChar = async () => {
  var data = {
    user_id: store.user_id,
    operation_id: props.operationID,
    name: pickedName,
    user_status_id: 1,
    role_id: pickedRole.value,
    entosis: pickedRole.value != 1 ? null : newLink,
    ship: pickedRole.value != 1 ? null : pickedShip,
  };
  await axios({
    method: "PUT",
    url: "/api/newcampaignusers/" + props.char.id + "/" + props.operationID,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let open = () => {
  if (props.type == 2) {
    let value = props.char.userrole.id;
    let text = props.char.userrole.role;
    pickedName = props.char.name;
    pickedRole = { value, text };
    pickedShip = props.char.ship;
    newLink = props.char.entosis;
  }
};

let headerText = $computed(() => {
  if (props.type == 1) {
    return "Add Character";
  } else {
    return "Edit Character";
  }
});

let buttonText = $computed(() => {
  if (props.type == 1) {
    return "Add";
  } else {
    return "Update";
  }
});
</script>

<style lang="scss"></style>
