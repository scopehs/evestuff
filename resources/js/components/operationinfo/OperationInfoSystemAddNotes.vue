<template>
  <q-btn color="primary" flat icon="fa-solid fa-clipboard" padding="none">
    <q-menu @before-show="open()" class="myRoundTop">
      <q-card class="myRoundTop">
        <q-card-section class="bg-primary text-center">
          Notes for {{ props.item.system_name }}
        </q-card-section>
        <q-card-section>
          <q-input
            input-style="height: 150px"
            v-model="text"
            clearable
            outlined
            autofocus
            rounded
            dense
            type="textarea"
            label="Enter Notes here"
          />
        </q-card-section>
        <q-card-actions align="between">
          <q-btn rounded color="positive" label="Submint" v-close-popup @click="done()" />
          <q-btn rounded color="negative" label="Close" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-menu>
  </q-btn>
</template>

<script setup>
const props = defineProps({
  item: [Array, Object],
});
let text = $ref();

let open = () => {
  text = props.item.pivot.notes;
};

let done = $computed(async () => {
  var request = {
    notes: text,
  };
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/operationinfosystemnoteupdate/" + props.item.id,
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
});
</script>

<style lang="scss"></style>
