<template>
  <div>
    <q-btn
      color="negative"
      icon="fa-solid fa-trash-can"
      flat
      size="sm"
      round
      @click="confirm = true"
    />
    <q-dialog v-model="confirm" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <q-icon name="fa-solid fa-triangle-exclamation" color="warning" size="xl" />
          <span class="q-ml-sm text-h4"
            >Are you sure
            <span class="text-pink-13 text-bold">EMILY!!!!!!!!!!</span></span
          >
          <q-icon name="fa-solid fa-triangle-exclamation" color="warning" size="xl" />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn rounded label="Cancel" color="negative" v-close-popup />
          <q-btn
            rounded
            label="Yes"
            color="primary"
            v-close-popup
            @click="deleteCampaign"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import axios from "axios";

const props = defineProps({
  item: Object,
});
let confirm = $ref(false);

let deleteCampaign = async () => {
  await axios({
    method: "delete",
    url: "/api/newoperation/" + props.item.id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss"></style>
