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
        color="success"
        ><font-awesome-icon icon="fa-solid fa-plus" size="2xl"
      /></v-btn>
    </template>
    <v-card tile class="rounded-xl">
      <v-card-text>
        <v-combobox
          outlined
          :items="dropDown"
          v-model="name"
          label="Recon"
          item-text="name"
          item-value="id"
          hide-details
          rounded
          dense
        ></v-combobox>
        <v-autocomplete
          class="pt-2"
          outlined
          :items="roleDrop"
          v-model="role"
          label="Role"
          item-text="name"
          item-value="id"
          rounded
          dense
          hide-details
        ></v-autocomplete>
      </v-card-text>
      <v-card-actions
        ><v-row no-gutters>
          <v-col cols="auto"
            ><v-btn
              rounded
              color=" primary"
              @click="addRecon()"
              :disabled="disableAddButton"
              >Add</v-btn
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
  },
  data() {
    return {
      addShown: false,
      role: null,
      name: null,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async addRecon() {
      var request = {
        reconID: this.name.id,
        role: this.role,
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
        this.close();
      });
    },

    close() {
      this.name = null;
      this.role = null;
      this.addShown = false;
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["operationInfoRecon"]),

    disableAddButton() {
      if (this.name && this.role) {
        return false;
      } else {
        return true;
      }
    },

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

    type() {
      return typeof this.name;
    },
  },
  beforeDestroy() {},
};
</script>
