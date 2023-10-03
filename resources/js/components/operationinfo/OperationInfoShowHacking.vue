<template>
  <v-dialog
    transition="dialog-top-transition"
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
        ><font-awesome-icon icon="fa-solid fa-person-rifle" size="2xl"
      /></v-btn>
    </template>
    <v-card tile class="rounded-xl">
      <v-card-text class="pb-1">
        <OperationInfoShowHackingContent
          v-for="(item, index) in opInfo.campaigns"
          :key="index"
          :item="item"
        />
      </v-card-text>
    </v-card>
  </v-dialog>
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
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },
  },

  beforeDestroy() {},
};
</script>
