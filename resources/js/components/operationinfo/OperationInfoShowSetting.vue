<template>
  <v-menu
    :close-on-content-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        fab
        x-small
        v-bind="attrs"
        v-on="on"
        @click="addShown = true"
        color="warning"
        ><font-awesome-icon icon="fa-solid fa-eye" size="2xl"
      /></v-btn>
    </template>
    <v-card tile class="rounded-xl">
      <v-card-title class="justify-center primary"> Page Setting </v-card-title>
      <v-card-text>
        <v-card-subtitle>Your Own Setting for this page </v-card-subtitle>
        <v-row no-gutters>
          <v-col cols="auto">
            <v-switch
              v-model="viewSettings.showTickList"
              dense
              class="mt-0 pt-0"
              hide-details="auto"
            ></v-switch>
          </v-col>
          <v-col cols="auto" class="d-flex align-baseline">
            <transition
              mode="out-in"
              enter-active-class="animate__animated animate__flash animate__faster"
              leave-active-class="animate__animated animate__flash animate__faster"
            >
              <span
                :key="viewSettings.showTickList"
                :class="textcolor(viewSettings.showTickList)"
              >
                Check List - {{ checkText(viewSettings.showTickList) }}</span
              >
            </transition>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col cols="auto">
            <v-switch
              v-model="viewSettings.showFleets"
              dense
              class="mt-0 pt-0"
              hide-details="auto"
            ></v-switch>
          </v-col>
          <v-col cols="auto" class="d-flex align-baseline">
            <transition
              mode="out-in"
              enter-active-class="animate__animated animate__flash animate__faster"
              leave-active-class="animate__animated animate__flash animate__faster"
            >
              <span
                :key="viewSettings.showFleets"
                :class="textcolor(viewSettings.showFleets)"
              >
                Fleet Table - {{ checkText(viewSettings.showFleets) }}</span
              >
            </transition>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col cols="auto">
            <v-switch
              v-model="viewSettings.showReconTable"
              dense
              class="mt-0 pt-0"
              hide-details="auto"
            ></v-switch>
          </v-col>
          <v-col cols="auto" class="d-flex align-baseline">
            <transition
              mode="out-in"
              enter-active-class="animate__animated animate__flash animate__faster"
              leave-active-class="animate__animated animate__flash animate__faster"
            >
              <span
                :key="viewSettings.showReconTable"
                :class="textcolor(viewSettings.showReconTable)"
              >
                Recon List - {{ checkText(viewSettings.showReconTable) }}</span
              >
            </transition>
          </v-col>
        </v-row>

        <v-row no-gutters>
          <v-col cols="auto">
            <v-switch
              v-model="viewSettings.showSystemTable"
              dense
              class="mt-0 pt-0"
              hide-details="auto"
            ></v-switch>
          </v-col>
          <v-col cols="auto" class="d-flex align-baseline">
            <transition
              mode="out-in"
              enter-active-class="animate__animated animate__flash animate__faster"
              leave-active-class="animate__animated animate__flash animate__faster"
            >
              <span
                :key="viewSettings.showSystemTable"
                :class="textcolor(viewSettings.showSystemTable)"
              >
                System Table -
                {{ checkText(viewSettings.showSystemTable) }}</span
              >
            </transition>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions> </v-card-actions>
    </v-card>
  </v-menu>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {},
  data() {
    return {
      addShown: false,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    close() {
      this.addShown = false;
    },

    checkText(num) {
      if (num) {
        return "Show";
      } else {
        return "Hide";
      }
    },

    textcolor(num) {
      if (num) {
        return "green--text";
      } else {
        return "red--text";
      }
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["operationInfoSetting"]),
    viewSettings: {
      get() {
        return this.$store.state.operationInfoSetting;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationInfoSetting", newValue);
      },
    },
  },

  beforeDestroy() {},
};
</script>
