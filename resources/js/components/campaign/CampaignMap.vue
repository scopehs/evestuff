<template>
  <div class="q-gutter-sm col-auto">
    <q-btn icon="fa-solid fa-map" color="primary" round flat @click="dialog = true" />

    <q-dialog
      v-model="dialog"
      persistent
      :maximized="maximizedToggle"
      transition-show="slide-up"
      transition-hide="slide-down"
    >
      <q-card class="bg-primary text-white overflow-hidden">
        <q-bar>
          {{ region_name }} - {{ system_name }}
          <q-space />
          <q-btn dense flat icon="close" v-close-popup>
            <q-tooltip class="bg-white text-primary">Close</q-tooltip>
          </q-btn>
        </q-bar>

        <q-card-section class="q-pt-none full-height">
          <iframe
            :src="link()"
            style="
              left: 0;
              bottom: 0;
              right: 0;
              width: 100%;
              height: 100%;
              border: none;
              margin: 0;
              padding: 0;
              overflow: hidden;
              z-index: 999999;
            "
          >
          </iframe>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
const props = defineProps({
  region_name: String,
  system_name: String,
});

let dialog = $ref(false);
let maximizedToggle = $ref(true);
let link = () => {
  if (props.region_name == "Black Rise") {
    return "https://evemaps.dotlan.net/map/Black_Rise/" + props.system_name + "#const";
  }
  if (props.region_name == "The Bleak Lands") {
    return (
      "https://evemaps.dotlan.net/map/The_Bleak_Lands/" + props.system_name + "#const"
    );
  }
  if (props.region_name == "The Citadel") {
    return "https://evemaps.dotlan.net/map/The_Citadel/" + props.system_name + "#const";
  }
  if (props.region_name == "Cloud Ring") {
    return "https://evemaps.dotlan.net/map/Cloud_Ring/" + props.system_name + "#const";
  }
  if (props.region_name == "Cobalt Edge") {
    return "https://evemaps.dotlan.net/map/Cobalt_Edge/" + props.system_name + "#const";
  }
  if (props.region_name == "Etherium Reach") {
    return (
      "https://evemaps.dotlan.net/map/Etherium_Reach/" + props.system_name + "#const"
    );
  }
  if (props.region_name == "The Forge") {
    return "https://evemaps.dotlan.net/map/The_Forge/" + props.system_name + "#const";
  }
  if (props.region_name == "The Kalevala Expanse") {
    return (
      "https://evemaps.dotlan.net/map/The_Kalevala_Expanse/" +
      props.system_name +
      "#const"
    );
  }
  if (props.region_name == "Molden Heath") {
    return "https://evemaps.dotlan.net/map/Molden_Heath/" + props.system_name + "#const";
  }
  if (props.region_name == "Outer Passage") {
    return "https://evemaps.dotlan.net/map/Outer_Passage/" + props.system_name + "#const";
  }
  if (props.region_name == "Outer Ring") {
    return "https://evemaps.dotlan.net/map/Outer_Ring/" + props.system_name + "#const";
  }
  if (props.region_name == "Paragon Soul") {
    return "https://evemaps.dotlan.net/map/Paragon_Soul/" + props.system_name + "#const";
  }
  if (props.region_name == "Period Basis") {
    return "https://evemaps.dotlan.net/map/Period_Basis/" + props.system_name + "#const";
  }
  if (props.region_name == "Perrigen Falls") {
    return (
      "https://evemaps.dotlan.net/map/Perrigen_Falls/" + props.system_name + "#const"
    );
  }
  if (props.region_name == "Pure Blind") {
    return "https://evemaps.dotlan.net/map/Pure_Blind/" + props.system_name + "#const";
  }
  if (props.region_name == "Scalding Pass") {
    return "https://evemaps.dotlan.net/map/Scalding_Pass/" + props.system_name + "#const";
  }
  if (props.region_name == "Sinq Laison") {
    return "https://evemaps.dotlan.net/map/Sinq_Laison/" + props.system_name + "#const";
  }
  if (props.region_name == "The Spire") {
    return "https://evemaps.dotlan.net/map/The_Spire/" + props.system_name + "#const";
  }
  if (props.region_name == "Vale of the Silent") {
    return (
      "https://evemaps.dotlan.net/map/Vale_of_the_Silent/" + props.system_name + "#const"
    );
  }
  if (props.region_name == "Verge Vendor") {
    return "https://evemaps.dotlan.net/map/Verge_Vendor/" + props.system_name + "#const";
  }
  if (props.region_name == "Wicked Creek") {
    return "https://evemaps.dotlan.net/map/Wicked_Creek/" + props.system_name + "#const";
  }
  return (
    "https://evemaps.dotlan.net/map/" +
    props.region_name +
    "/" +
    props.system_name +
    "#const"
  );
};
</script>
