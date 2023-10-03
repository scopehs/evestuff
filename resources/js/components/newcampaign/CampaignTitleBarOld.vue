<template>
  <v-row no-gutters>
    <v-col>
      <v-expansion-panels popout>
        <v-expansion-panel class="rounded-xl">
          <v-expansion-panel-header>
            <span class="text-h3 text-center">{{ title }}</span>
            <!-- <v-btn @click="clear()" color="warning"> CLER ALL DATA</v-btn> -->
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <CampaignTitleBarContent
              v-for="(item, index) in campaigns"
              :key="index"
              :item="item"
              :title="title"
              :operationID="operationID"
              :activeCampaigns="activeCampaigns"
              :warmUpCampaigns="warmUpCampaigns"
            />
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-col>
  </v-row>
</template>



<script>
import Axios from "axios";
// import { EventBus } from "../event-bus";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    title: String,
    operationID: Number,
    activeCampaigns: Array,
    warmUpCampaigns: Array,
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async clear() {
      await axios({
        method: "POST", //you can set what request you want to be
        url: "/api/testclearalldata",
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["newCampaigns"]),

    campaigns() {
      return this.newCampaigns;
    },
  },
  beforeDestroy() {},
};
</script>
