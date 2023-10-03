<template>
  <v-row no-gutters>
    <v-col cols="auto">
      <v-card rounded="xl"
        ><v-card-title class="pt-1 pb-1"
          ><v-row no-gutters
            ><v-col cols="auto">Recon</v-col>
            <v-col cols="auto"
              ><AddOperationFleetReconButton :fleetID="fleetID"
            /></v-col> </v-row></v-card-title
        ><v-card-text>
          <v-row no-gutters v-for="recon in fleetInfo.recons" :key="`${recon.id}-card`">
            <v-col cols="auto">
              {{ recon.name }} - {{ recon.main.name }} -
              {{ recon.fleet_role.name }}
              <span v-if="recon.system"> - {{ recon.system.system_name }} </span>
            </v-col>
            <v-col cols="auto">
              <v-row no-gutters justify="end">
                <v-col cols="auto">
                  <AddOperationFleetReconEditButton :fleetID="fleetID" :item="recon" />
                </v-col>
                <v-col cols="auto">
                  <v-btn icon x-small color="red" @click="removeReconFromFleet(recon)">
                    <font-awesome-icon icon="fa-solid fa-trash-can" />
                  </v-btn>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
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
    fleetID: Number,
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    textColor(recon) {
      if (recon.operation_info_recon_status_id == 1) {
        return "";
      }

      if (recon.operation_info_recon_status_id == 2) {
        return "green--text";
      }
    },

    async removeReconFromFleet(item) {
      var request = item;
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinfofleetreconremove/" + item.operation_info_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    hideToolTip(recon) {
      if (recon.operation_info_fleet_id || recon.system) {
        return false;
      }
      return true;
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

    fleetInfo: {
      get() {
        return this.$store.getters.getFleetInfo(this.fleetID);
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPageFleet", newValue);
      },
    },

    showEnter() {
      return "animate__animated animate__flash animate__faster";
    },

    showLeave() {
      return "animate__animated animate__flash animate__faster";
    },
  },
  beforeDestroy() {},
};
</script>
