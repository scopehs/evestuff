<template>
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-12">
          <NewSystemTable
            :item="item"
            :operationID="operationID"
            :activeCampaigns="activeCampaigns"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-8"><SystemCheckButton :item="item" /></div>
        <div class="col-4"><TidiButton :item="item" /></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
const props = defineProps({
  item: Object,
  operationID: Number,
});

const SystemCheckButton = defineAsyncComponent(() => import("./SystemCheckButton.vue"));
const TidiButton = defineAsyncComponent(() => import("./TidiButton.vue"));
const NewSystemTable = defineAsyncComponent(() => import("./NewSystemTable.vue"));

let campaigns = $computed(() => {
  var camp = props.item.new_campaigns;
  camp = camp.filter((c) => {
    let operations = c.operations.filter((o) => o.id == props.operationID);
    if (operations.length > 0) {
      return true;
    } else {
      return false;
    }
  });

  return camp;
});

let activeCampaigns = $computed(() => {
  var active = campaigns.filter((c) => {
    if (c.status_id == 2) {
      return true;
    } else {
      return false;
    }
  });

  return active;
});
</script>

<style lang="scss"></style>
