<template>
  <div>
    <q-card class="myRoundTop q-pb-xs" style="height: fit-content; max-width: 500px">
      <q-card-section>
        <div class="column">
          <span class="col text-center">
            <div class="row justify-center">
              Name - {{ list.name }}
              <StationWatchListAddList :list="list" :edit="1" />
              <q-btn
                color="warning"
                icon="fa-solid fa-trash-can"
                flat
                round
                padding="none"
                size="xs"
                @click="deleteList"
              />
            </div>
          </span>

          <span class="col text-center"> Description - {{ list.description }} </span>
        </div>
      </q-card-section>
      <q-separator color="webChip" />
      <q-card-section v-if="list.alliances.length != 0">
        <div class="row">
          <div class="col text-center">Owners</div>
        </div>
        <div class="row">
          <div class="col flex justify-center">
            <q-badge
              color="webChip"
              class="q-ml-xs"
              rounded
              v-for="(role, index) in list.alliances"
              :key="index"
            >
              {{ role.ticker }}
            </q-badge>
          </div>
        </div>
      </q-card-section>
      <q-card-section v-if="list.items.length != 0">
        <div class="row">
          <div class="col text-center">Types</div>
        </div>
        <div class="row">
          <div class="col flex justify-center">
            <q-badge
              color="webChip"
              class="q-ml-xs"
              rounded
              v-for="(user, index) in list.items"
              :key="index"
            >
              {{ user.item_name }}
            </q-badge>
          </div>
        </div>
      </q-card-section>
      <q-separator color="webChip" />
      <q-card-section v-if="list.regions.length != 0">
        <div class="row">
          <div class="col text-center text-subtitle2">Regions</div>
        </div>
        <div class="row">
          <div class="col flex justify-center">
            <q-badge
              color="webChip"
              class="q-ml-xs"
              rounded
              v-for="(region, index) in list.regions"
              :key="index"
            >
              {{ region.region_name }}
            </q-badge>
          </div>
        </div>
      </q-card-section>
      <q-card-section v-if="list.constellations.length != 0">
        <div class="row">
          <div class="col text-center">Constellations</div>
        </div>
        <div class="row">
          <div class="col flex justify-center">
            <q-badge
              color="webChip"
              class="q-ml-xs"
              rounded
              v-for="(constellation, index) in list.constellations"
              :key="index"
            >
              {{ constellation.constellation_name }}
            </q-badge>
          </div>
        </div>
      </q-card-section>
      <q-card-section v-if="list.systems.length != 0">
        <div class="row">
          <div class="col text-center">Systems</div>
        </div>
        <div class="row">
          <div class="col flex justify-center">
            <q-badge
              color="webChip"
              class="q-ml-xs"
              rounded
              v-for="(system, index) in list.systems"
              :key="index"
            >
              {{ system.system_name }}
            </q-badge>
          </div>
        </div>
      </q-card-section>
      <q-card-section v-if="list.stations.length != 0">
        <div class="row">
          <div class="col text-center">Stations</div>
        </div>
        <div class="row">
          <div class="col flex justify-center">
            <q-badge
              color="webChip"
              class="q-ml-xs"
              rounded
              v-for="(station, index) in list.stations"
              :key="index"
            >
              {{ station.name }}
            </q-badge>
          </div>
        </div>
      </q-card-section>
      <q-separator color="webChip" />
      <q-card-section v-if="list.roles.length != 0">
        <div class="row">
          <div class="col text-center">Roles</div>
        </div>
        <div class="row">
          <div class="col flex justify-center">
            <q-badge
              color="webChip"
              class="q-ml-xs"
              rounded
              v-for="(role, index) in list.roles"
              :key="index"
            >
              {{ role.name }}
            </q-badge>
          </div>
        </div>
      </q-card-section>
      <q-card-section v-if="list.users != 0">
        <div class="row">
          <div class="col text-center">Users</div>
        </div>
        <div class="row">
          <div class="col flex justify-center">
            <q-badge
              color="webChip"
              class="q-ml-xs"
              rounded
              v-for="(user, index) in list.users"
              :key="index"
            >
              {{ user.name }}
            </q-badge>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";

const StationWatchListAddList = defineAsyncComponent(() =>
  import("@/components/StationWatchList/StationWatchListAddList.vue")
);

const props = defineProps({
  list: [Array, Object],
});

let deleteList = async () => {
  await axios({
    method: "delete",
    url: "/api/watchlist/" + props.list.id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss"></style>
