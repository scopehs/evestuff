<template>
  <div>
    <q-btn
      v-if="!edit"
      flat
      color="warning"
      icon="fa-solid fa-plus"
      label="Add Affiliation"
      @click="showPannel = !showPannel"
    />

    <q-btn
      flat
      size="sm"
      v-if="edit"
      color="secondary"
      icon="fa-solid fa-edit"
      @click="showPannel = !showPannel"
    />

    <q-dialog
      v-model="showPannel"
      persistent
      @before-hide="close()"
      @before-show="open()"
    >
      <q-card class="myRoundTop">
        <q-card-section class="myCardHeader bg-primary text-h3">
          Make a new Affiliation
        </q-card-section>
        <q-card-section>
          <q-input v-model="name" label="Name" />
          <q-input v-model="short_name" label="Short Name" />
          <q-option-group v-model="group" color="secondary" :options="options" inline />
        </q-card-section>
        <q-card-actions align="between">
          <q-btn
            rounded
            color="positive"
            @click="submit"
            :label="props.edit ? 'Update' : 'Submit'"
            v-close-popup
            :disable="isDisabled"
          />
          <q-btn rounded color="negative" label="Close" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";

let store = useMainStore();
let can = inject("can");

const props = defineProps({
  edit: { type: Boolean, default: false },
  item: { type: [Object, Array], required: false },
});
let showPannel = $ref(false);
let name = $ref("");
let short_name = $ref("");
let group = $ref(null);
let options = $ref([
  { label: "Blue", value: 2 },
  { label: "Red", value: 1 },
  { label: "Neutral", value: 0 },
]);

let submit = async () => {
  let data = {
    name: name,
    short_name: short_name,
    color: group,
  };

  if (props.edit) {
    await axios({
      method: "put",
      withCredentials: true,
      data: data,
      url: "/api/affiliation/" + props.item.id,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    }).then((response) => {
      store.updateAffilation(response.data.affiliation);
    });
  } else {
    await axios({
      method: "post",
      withCredentials: true,
      data: data,
      url: "/api/affiliation",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    }).then((response) => {
      store.updateAffilation(response.data.affiliation);
    });
  }
};

let open = () => {
  if (props.edit) {
    name = props.item.name;
    short_name = props.item.short_name;
    group = props.item.color;
  }
};

let isDisabled = $computed(() => {
  if (name == "" || short_name == "" || group == null) {
    return true;
  } else {
    return false;
  }
});

let close = () => {
  name = "";
  short_name = "";
  group = null;
};
</script>

<style lang="scss"></style>
