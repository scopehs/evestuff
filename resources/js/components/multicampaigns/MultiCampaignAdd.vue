<template>
  <div>
    <v-card min-width="1200" max-width="1200">
      <v-card-title> Make your Mulit-Campaign Here </v-card-title>
      <v-card-text>
        <v-text-field
          label="Multi-Campaign Name"
          v-model="name"
          hint="Enter The name of your Campaign here"
          filled
        >
        </v-text-field>
        <v-select
          v-model="picked"
          :items="list"
          label="Select"
          multiple
          chips
          deletable-chips
          hint="Which Campaigns do you want"
          persistent-hint
        ></v-select>
      </v-card-text>
      <v-card-actions>
        <v-btn color="success" class="mr-4" @click="addCampaignDone()"
          >Done</v-btn
        >
        <v-btn color="warning" class="mr-4" @click="addCampaignClose()"
          >Close</v-btn
        >
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  data() {
    return {
      name: "",
      picked: [],
    };
  },

  created() {
    this.$store.dispatch("getCampaignsList");
  },

  methods: {
    async addCampaignDone() {
      let id = moment().format("x");

      await axios({
        method: "POST",
        url: "/api/multicampaigns/" + id + "/" + this.name,
        withCredentials: true,
        data: this.picked,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.$emit("closeAddNew");
    },

    addCampaignClose() {
      this.picked = [];
      this.name = "";
      this.$emit("closeAdd");
    },
  },

  computed: {
    ...mapState(["campaignslist"]),

    list() {
      return this.campaignslist;
    },
  },
};
</script>
