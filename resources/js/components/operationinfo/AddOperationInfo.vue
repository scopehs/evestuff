<template>
  <div>
    <q-btn
      color="none"
      flat
      text-color="green"
      icon="fa-solid fa-plus"
      label="Add Operation"
      @click="showAddOp = true"
    />
    <q-dialog
      class="myRoundTop"
      max-width="700px"
      min-width="700px"
      v-model="showAddOp"
      persistent
      @before-hide="close()"
    >
      <q-card class="myRoundTop" flat style="width: 700px; max-width: 80vw">
        <q-card-section class="bg-primary text-center">
          <h5 class="no-margin">Add Tower</h5>
        </q-card-section>
        <q-card-section>
          <q-input
            v-model="infoTitle"
            clearable
            outlined
            rounded
            dense
            type="text"
            label="Operation Title"
          />
        </q-card-section>
        <q-card-section>
          <q-input
            input-style="height: 500px"
            v-model="infoText"
            clearable
            outlined
            rounded
            dense
            type="textarea"
            label="Describe the operation here"
          />
        </q-card-section>
        <q-card-actions align="center">
          <q-btn
            color="positive"
            label="Submit"
            @click="submit()"
            rounded
            :disable="isDisable"
            v-close-popup
          />
          <q-btn label="Close" rounded color="negative" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
let showAddOp = $ref(false);
let infoText = $ref();
let infoTitle = $ref();

let submit = async () => {
  var request = {
    name: infoTitle,
    info: infoText,
  };
  await axios({
    method: "POST",
    url: "/api/operationinfosheet",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let close = () => {
  infoText = null;
  infoTitle = null;
  showAddOp = false;
};

let isDisable = $computed(() => {
  if (infoTitle) {
    return false;
  }
  return true;
});
</script>

<style lang="scss"></style>
