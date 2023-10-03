<template>
  <div class="pr-4 h4 d-flex align-items-baseline justify-end">
    <div>
      System TiDi:
      <span :class="colorTidi()">{{ CampaignSolaSystem[0]["tidi"] }}%</span>
      <v-menu :close-on-content-click="false" :value="tidiShow">
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            class="pb-1"
            v-bind="attrs"
            v-on="on"
            @click="(tidiShow = true), (tidiEdit = null)"
            icon
            color="warning"
          >
            <font-awesome-icon icon="fa-solid fa-pen-to-square" />
          </v-btn>
        </template>

        <template>
          <v-card tile min-height="150px">
            <v-card-title class="pb-0">
              <v-text-field
                v-model="tidiEdit"
                label="Tidi %"
                autofocus
                v-mask="'###'"
                :placeholder="placeHolder()"
                @keyup.enter="(tidiShow = false), editTidi()"
                @keyup.esc="(tidiShow = false), (tidiEdit = null)"
              ></v-text-field>
            </v-card-title>
            <v-card-text>
              <v-btn
                icon
                fixed
                left
                color="success"
                @click="(tidiShow = false), editTidi()"
                ><font-awesome-icon icon="fa-solid fa-check"
              /></v-btn>

              <v-btn
                fixed
                right
                icon
                color="warning"
                @click="(tidiShow = false), (tidiEdit = null)"
                ><font-awesome-icon icon="fa-solid fa-circle-xmark"
              /></v-btn>
            </v-card-text>
          </v-card>
        </template>
      </v-menu>
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
    return {
      tidiShow: false,
      tidiEdit: null,
    };
  },

  mounted() {},
  methods: {
    colorTidi() {
      if (this.CampaignSolaSystem[0]["tidi"] > 59) {
        return "green--text font-weight-bold";
      } else if (this.CampaignSolaSystem[0]["tidi"] > 34) {
        return "orange--text font-weight-bold";
      } else {
        return "red--text font-weight-bold";
      }
    },

    placeHolder() {
      return "" + this.CampaignSolaSystem[0]["tidi"];
    },

    async editTidi() {
      var data = {
        id: this.CampaignSolaSystem[0]["id"],
        tidi: this.tidiEdit,
      };
      this.$store.dispatch("updateCampaignSolaSystem", data);

      var request = {
        newTidi: this.tidiEdit,
        oldTidi: this.CampaignSolaSystem[0]["tidi"],
        solaID: this.CampaignSolaSystem[0]["id"],
        baseTime: this.CampaignSolaSystem[0]["base_time"],
      };

      await axios({
        method: "put", //you can set what request you want to be
        url:
          "/api/campaignsystemstidimulti/" +
          this.CampaignSolaSystem[0]["system_id"] +
          "/" +
          this.CampaignSolaSystem[0]["campaign_id"],
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {},
};
</script>

<style></style>
