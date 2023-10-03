<template>
  <v-menu
    :close-on-content-click="false"
    :close-on-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
    persistent
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        icon
        x-small
        v-bind="attrs"
        v-on="on"
        @click="open()"
        color="warning"
      >
        <font-awesome-icon icon="fa-solid fa-pen-to-square" />
      </v-btn>
    </template>
    <v-card tile class="rounded-xl">
      <v-card-text>
        Edit the Role of {{ item.name }}
        <v-autocomplete
          class="pt-2"
          outlined
          :items="roleDrop"
          v-model="recon.role_id"
          auto-select-first
          hide-selected
          label="Role"
          item-text="name"
          :menu-props="{ top: true, offsetY: true }"
          item-value="id"
          rounded
          dense
          hide-details
        ></v-autocomplete>
      </v-card-text>
      <v-card-actions
        ><v-row no-gutters>
          <v-col cols="auto"
            ><v-btn rounded color=" primary" @click="updateRecon()"
              >Update</v-btn
            ></v-col
          ><v-spacer /><v-col cols="auto"
            ><v-btn rounded color=" warning" @click="close()"
              >Close</v-btn
            ></v-col
          >
        </v-row>
      </v-card-actions>
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
  props: {
    fleetID: Number,
    item: [Array, Object],
  },
  data() {
    return {
      addShown: false,
      role: null,
      oldRole: null,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    open() {
      this.oldRole = this.recon.role_id;
      this.addShown = true;
    },
    async updateRecon() {
      var request = {
        reconID: this.item.id,
        role: this.recon.role_id,
        opID: this.$store.state.operationInfoPage.id,
      };
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinfofleetrecon/" + this.fleetID,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then((response) => {
        this.addShown = false;
      });
    },

    close() {
      this.recon.role_id = this.oldRole;
      this.addShown = false;
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["operationInfoRecon"]),

    dropDown() {
      var data = this.operationInfoRecon.filter(
        (r) =>
          r.operation_info_id == this.$store.state.operationInfoPage.id &&
          r.operation_info_fleet_id == null
      );
      return data;
    },

    roleDrop() {
      return this.$store.state.operationInfoReconFleetRoleList;
    },

    fleetInfo: {
      get() {
        return this.$store.getters.getFleetInfo(this.fleetID);
      },
      set(newValue) {},
    },

    recon() {
      var recons = this.fleetInfo.recons;
      var recon = recons.find((r) => r.id == this.item.id);
      return recon;
    },
  },
  beforeDestroy() {},
};
</script>
