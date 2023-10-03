<template>
  <div>
    <q-btn
      v-if="!edit"
      flat
      color="warning"
      icon="fa-solid fa-plus"
      label="Add Staging System"
      @click="showPannel = !showPannel"
    />

    <q-btn
      flat
      round
      padding="none"
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
        <q-card-section class="myCardHeader bg-primary text-h3 text-no-wrap">
          Add new Staging
        </q-card-section>
        <q-card-section>
          <q-input v-model="name" label="Name" />
          <q-select
            rounded
            class="q-pt-sm"
            dense
            standout
            input-debounce="0"
            label-color="webway"
            option-value="value"
            option-label="text"
            v-model="systemsToUpdate"
            :options="systemFindList"
            @filter="filterFnSystemFinish"
            @filter-abort="abortFilterFn"
            label="System"
            use-input
          />
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

let submit = async () => {
  let data = {
    name: name,
    system_id: systemsToUpdate.value,
  };

  if (props.edit) {
    await axios({
      method: "put",
      withCredentials: true,
      data: data,
      url: "/api/staging/" + props.item.id,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    }).then((response) => {});
  } else {
    await axios({
      method: "post",
      withCredentials: true,
      data: data,
      url: "/api/staging",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    }).then((response) => {});
  }
};

let abortFilterFn = () => {};
let systemsToUpdate = $ref([]);
let systemFindText = $ref();

let systemFindList = $computed(() => {
  if (systemFindText) {
    return store.systemlist.filter(
      (d) => d.text.toLowerCase().indexOf(systemFindText) > -1
    );
  }
  return store.systemlist;
});

let filterFnSystemFinish = (val, update, abort) => {
  update(() => {
    systemFindText = val.toLowerCase();
    if (systemFindList.length > 0 && val) {
      systemsToUpdate = systemFindList[0];
    }
  });
};

let open = () => {
  if (props.edit) {
    name = props.item.name;
    systemsToUpdate = store.systemlist.find((d) => d.value == props.item.system_id);
  }
};

let isDisabled = $computed(() => {
  if (name == "" || systemsToUpdate.length == 0) {
    return true;
  } else {
    return false;
  }
});

let close = () => {
  name = "";
  systemsToUpdate = [];
  systemFindText = "";
};
</script>

<style lang="scss"></style>
