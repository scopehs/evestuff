<template>
  <v-col cols="12">
    <v-card rounded="xl"
      ><v-card-title class="green"
        ><v-row no-gutters
          ><v-col cols="auto">Recon</v-col
          ><v-col cols="auto"><AddOperationReconButton /></v-col></v-row></v-card-title
      ><v-card-text>
        <transition-group
          enter-active-class="animate__animated animate__flash animate__faster"
        >
          <OperationInfoReconCardNames
            :recon="recon"
            no-gutters
            v-for="recon in opInfo.recons"
            :key="`${recon.id}-${recon.operation_info_recon_status_id}-${recon.dead}-${recon.online}-card`"
          />
        </transition-group>
      </v-card-text>
    </v-card>
  </v-col>
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
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

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
