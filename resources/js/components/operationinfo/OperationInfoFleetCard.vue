<template>
  <div>
    <q-table
      title="Connections"
      class="myOperationFleetTable myRound"
      :rows="store.operationInfoPage.fleets"
      table-class=" text-webway"
      row-key="id"
      no-data-label="Not Fleets"
      ref="tableRef"
      rounded
      grid
      card-container-class="justify-evenly overflow-auto "
      hide-header
      hide-bottom
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width">
          <div class="col-auto">
            <q-btn
              color="primary"
              icon-right="fa-solid fa-plus"
              label="Add Fleet"
              flat
              @click="addFleet()"
            />
          </div>
          <div class="col-auto">
            <span v-if="showAddDank">
              <q-btn
                color="primary"
                icon-right="fa-solid fa-plus"
                label="Add Dank Operation Link"
                flat
                ><q-menu @before-hide="dankLink = null">
                  <q-list style="min-width: 100px">
                    <q-card class="my-card">
                      <q-card-section>
                        <q-input
                          v-model="dankLink"
                          rounded
                          outlined
                          type="text"
                          label="Dank Link"
                        />
                      </q-card-section>
                      <q-card-actions align="center">
                        <q-btn
                          v-close-popup
                          rounded
                          @click="addDankLink()"
                          color="positive"
                          label="Add"
                        />
                        <q-btn v-close-popup rounded color="negative" label="close" />
                      </q-card-actions>
                    </q-card>
                  </q-list> </q-menu
              ></q-btn>
            </span>
            <span v-else>
              <q-btn
                color="primary"
                flat
                :label="dankOpName"
                :href="store.operationInfoPage.dankop.link"
                target="_blank"
              />
              <q-btn
                round
                padding="none"
                flat
                size="sm"
                color="warning"
                icon="fa-solid fa-trash-alt"
                @click="removeDankLink()"
              />
            </span>
          </div>
        </div>
      </template>
      <template v-slot:item="props">
        <transition
          appear
          name="fade"
          enter-active-class="animate__animated animate__zoomIn animate__slow"
          leave-active-class="animate__animated animate__zoomOut animate__slow"
        >
          <OperationInfoFleetSoloCard
            class="q-pa-xs"
            :key="`${props.row.id}-card`"
            :fleet="props.row"
          />
        </transition>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { defineAsyncComponent, onMounted } from "vue";

const store = useMainStore();
const OperationInfoFleetSoloCard = defineAsyncComponent(() =>
  import("./OperationInfoFleetSoloCard.vue")
);
let pagination = $ref({
  sortBy: "id",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let dankLink = $ref(null);

let addDankLink = async () => {
  var data = {
    link: dankLink,
    opID: store.operationInfoPage.id,
  };
  console.log(store.operationInfoPage.id);
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/operationdanklink",
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let removeDankLink = async () => {
  console.log(store.operationInfoPage.id);
  await axios({
    method: "delete", //you can set what request you want to be
    url: "/api/operationdanklink/" + store.operationInfoPage.id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let dankOpName = $computed(() => {
  if (store.operationInfoPage.dankop) {
    return store.operationInfoPage.dankop.name;
  }
  return "No Dank Link";
});

let showAddDank = $computed(() => {
  if (store.operationInfoPage.dankop && store.operationInfoPage.dankop.link) {
    return false;
  }
  return true;
});

let addFleet = async () => {
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/operationinfofleet/" + store.operationInfoPage.id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>
