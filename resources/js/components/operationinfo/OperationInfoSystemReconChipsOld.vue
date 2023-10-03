<template>
  <v-sheet class="transparent" :max-width="MaxW">
    <v-chip-group active-class="primary--text" max="0">
      <v-chip
        v-for="(recon, index) in systemInfo.recons"
        :key="index"
        small
        close
        draggable
        @click:close="remove(recon.id)"
      >
        <v-avatar left>
          <v-img :src="recon.url"></v-img>
        </v-avatar>
        {{ recon.name }}
      </v-chip>
    </v-chip-group>
  </v-sheet>
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
  props: {
    loaded: Boolean,
    item: [Array, Object],
    windowSize: Object,
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async remove(id) {
      var request = {
        reconID: id,
        opID: this.opInfo.id,
      };
      await axios({
        method: "delete", //you can set what request you want to be
        url: "/api/operationinfosystemreconremove/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then((reponse) => {
        this.addShown = false;
      });
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["operationInfoPage", "operationInfoRecon"]),

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },

    dropDown() {
      var data = this.operationInfoRecon.filter(
        (r) => r.operation_info_id == this.$store.state.operationInfoPage.id
      );
      return data;
    },

    systemInfo: {
      get() {
        return this.$store.getters.getOperationSystemInfo(this.item.id);
      },
      set(newValue) {},
    },

    showEnter() {
      if (this.loaded == true) {
        return "animate__animated animate__flash animate__faster";
      }
    },

    MaxW() {
      let num = this.windowSize.x - 1400;
      return num;
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__flash animate__faster";
      }
    },
  },
  beforeDestroy() {},
};
</script>
