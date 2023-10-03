<template>
  <div>
    <q-expansion-item
      class="shadow-1 overflow-hidden"
      style="border-radius: 30px"
      :label="title"
      header-class=" bg-webBack text-webway text-h3 text-center"
      @show="10"
      @hide="10"
      dark
    >
      <q-card>
        <q-card-section>
          <CampaignTitleBarContent
            v-for="(item, index) in store.newCampaigns"
            :key="index"
            :item="item"
            :title="title"
            :operationID="operationID"
            :activeCampaigns="activeCampaigns"
            :warmUpCampaigns="warmUpCampaigns"
          />
        </q-card-section>
      </q-card>
    </q-expansion-item>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";

const store = useMainStore();
const props = defineProps({
  title: String,
  operationID: Number,
  activeCampaigns: Array,
  warmUpCampaigns: Array,
});
const CampaignTitleBarContent = defineAsyncComponent(() =>
  import("./CampaignTitleBarContent.vue")
);
</script>

<style lang="scss"></style>
