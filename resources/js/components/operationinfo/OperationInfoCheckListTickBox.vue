<template>
  <div>
    <q-checkbox
      @click="update()"
      :true-value="1"
      :false-value="0"
      v-model="store.operationInfoPage[`${props.storeName}`]"
    >
      <template v-slot:default>
        <transition
          mode="out-in"
          enter-active-class="animate__animated animate__flash animate__faster"
          leave-active-class="animate__animated animate__flash animate__faster"
        >
          <span
            :key="`${store.operationInfoPage[`${props.storeName}`]}-${props.tagName}`"
            :class="textClass(store.operationInfoPage[`${props.storeName}`])"
            >{{ title }}</span
          >
        </transition>
      </template>
    </q-checkbox>
  </div>
</template>
props.tagName
<script setup>
import { useMainStore } from "@/store/useMain.js";
const store = useMainStore();

const props = defineProps({
  title: String,
  storeName: String,
  tagName: String,
});
let update = async () => {
  var data = store.operationInfoPage;
  await axios({
    method: "put", //you can set what request you want to be
    url: "/api/operationinfopage/" + store.operationInfoPage.id,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let textClass = (value) => {
  if (value == 1) {
    return "text-green text-strike text-italic";
  } else {
    return "text-red";
  }
};
</script>

<style lang="scss"></style>
