<template>
  <div class="d-inline-flex align-items-md-center pl-4">
    <div>
      <span class="d-inline-flex align-items-md-center pr-2">
        System Scout:
        <span
          class="pl-2"
          v-if="CampaignSolaSystem[0]['supervisor_id'] != null"
        >
          {{ CampaignSolaSystem[0]["supervier_user_name"] }}
        </span>
      </span>
    </div>
    <div>
      <v-btn
        v-if="CampaignSolaSystem[0]['supervisor_id'] == null"
        class=""
        color="blue"
        x-small
        left
        outlined
        @click="scoutAdd()"
      >
        <font-awesome-icon icon="fa-solid fa-plus" size="2xl" />
        Add</v-btn
      >
      <v-btn
        v-if="
          CampaignSolaSystem[0]['supervisor_id'] != null &&
          ($can('edit_system_scout') ||
            this.$store.state.user_id == CampaignSolaSystem[0]['supervisor_id'])
        "
        @click="scoutRemove()"
      >
        <font-awesome-icon icon="fa-solid fa-trash-can" color="orange darken-3"
      /></v-btn>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    CampaignSolaSystem: Array,
  },
  data() {
    return {};
  },

  methods: {
    async scoutAdd() {
      var data = {
        id: this.CampaignSolaSystem[0]["id"],
        supervisor_id: this.$store.state.user_id,
        supervier_user_name: this.$store.state.user_name,
      };

      this.$store.dispatch("updateCampaignSolaSystem", data);

      var request = null;
      request = {
        supervisor_id: this.$store.state.user_id,
      };

      await axios({
        method: "put",
        url:
          "/api/campaignsolasystems/" +
          this.CampaignSolaSystem[0]["id"] +
          "/" +
          this.CampaignSolaSystem[0]["campaign_id"],
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      //------logging start -----//
      // await this.$store.dispatch("getCampaignSolaSystems");

      var request = null;
      request = {
        user_id: this.$store.state.user_id,
        campaign_sola_system_id: this.CampaignSolaSystem[0]["id"],
        type: "added",
      };

      await axios({
        method: "put",
        url: "/api/checkscout/" + this.CampaignSolaSystem[0]["campaign_id"],
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      //-----loggin end-----//
    },

    async scoutRemove() {
      var data = {
        id: this.CampaignSolaSystem[0]["id"],
        supervisor_id: null,
        supervier_user_name: null,
      };

      this.$store.dispatch("updateCampaignSolaSystem", data);

      var request = null;
      request = {
        supervisor_id: null,
      };

      await axios({
        method: "put",
        url:
          "/api/campaignsolasystems/" +
          this.CampaignSolaSystem[0]["id"] +
          "/" +
          this.CampaignSolaSystem[0]["campaign_id"],
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      // await this.$store.dispatch("getCampaignSolaSystems");

      //------logging start -----//
      var request = null;
      request = {
        user_id: this.$store.state.user_id,
        campaign_sola_system_id: this.CampaignSolaSystem[0]["id"],
        type: "removed",
      };

      await axios({
        method: "put",
        url: "/api/checkscout/" + this.CampaignSolaSystem[0]["campaign_id"],
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      //------logging end -----//
    },
  },

  computed: {},
};
</script>

<style></style>
