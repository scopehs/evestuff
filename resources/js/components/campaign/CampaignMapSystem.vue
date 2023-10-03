<template>
  <div>
    <v-dialog
      v-model="dialog1"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <template v-slot:activator="{ on, attrs }">
        <template>
          <v-btn
            color="primary"
            icon
            class="mr-2"
            v-bind="attrs"
            v-on="on"
            @click="dialog1 = true"
          >
            ><font-awesome-icon icon="fa-solid fa-map" size="2xl" />
          </v-btn>
        </template>
      </template>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="dialog1 = false">
            <font-awesome-icon icon="fa-solid fa-circle-xmark" size="2xl" />
          </v-btn>
          <v-toolbar-title
            >{{ region_name }} {{ constellation }}</v-toolbar-title
          >
          <v-spacer></v-spacer>
        </v-toolbar>
        <div
          style="
            position: absolute;
            top: 64px;
            right: 0px;
            bottom: 0px;
            left: 0px;
          "
        >
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
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    region_name: String,
    constellation: String,
  },
  data() {
    return {
      dialog1: "",
    };
  },

  methods: {
    link() {
      if (this.region_name == "Black Rise") {
        return (
          "https://evemaps.dotlan.net/map/Black_Rise/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "The Bleak Lands") {
        return (
          "https://evemaps.dotlan.net/map/The_Bleak_Lands/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "The Citadel") {
        return (
          "https://evemaps.dotlan.net/map/The_Citadel/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Cloud Ring") {
        return (
          "https://evemaps.dotlan.net/map/Cloud_Ring/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Cobalt Edge") {
        return (
          "https://evemaps.dotlan.net/map/Cobalt_Edge/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Etherium Reach") {
        return (
          "https://evemaps.dotlan.net/map/Etherium_Reach/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "The Forge") {
        return (
          "https://evemaps.dotlan.net/map/The_Forge/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "The Kalevala Expanse") {
        return (
          "https://evemaps.dotlan.net/map/The_Kalevala_Expanse/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Molden Heath") {
        return (
          "https://evemaps.dotlan.net/map/Molden_Heath/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Outer Passage") {
        return (
          "https://evemaps.dotlan.net/map/Outer_Passage/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Outer Ring") {
        return (
          "https://evemaps.dotlan.net/map/Outer_Ring/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Paragon Soul") {
        return (
          "https://evemaps.dotlan.net/map/Paragon_Soul/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Period Basis") {
        return (
          "https://evemaps.dotlan.net/map/Period_Basis/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Perrigen Falls") {
        return (
          "https://evemaps.dotlan.net/map/Perrigen_Falls/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Pure Blind") {
        return (
          "https://evemaps.dotlan.net/map/Pure_Blind/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Scalding Pass") {
        return (
          "https://evemaps.dotlan.net/map/Scalding_Pass/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Sinq Laison") {
        return (
          "https://evemaps.dotlan.net/map/Sinq_Laison/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "The Spire") {
        return (
          "https://evemaps.dotlan.net/map/The_Spire/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Vale of the Silent") {
        return (
          "https://evemaps.dotlan.net/map/Vale_of_the_Silent/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Verge Vendor") {
        return (
          "https://evemaps.dotlan.net/map/Verge_Vendor/" +
          this.constellation +
          "#const"
        );
      }
      if (this.region_name == "Wicked Creek") {
        return (
          "https://evemaps.dotlan.net/map/Wicked_Creek/" +
          this.constellation +
          "#const"
        );
      }
      return (
        "https://evemaps.dotlan.net/map/" +
        this.region_name +
        "/" +
        this.constellation +
        "#const"
      );
    },
  },

  computed: {},
};
</script>
