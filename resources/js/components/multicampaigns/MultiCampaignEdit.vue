<template>
  <div>
    <v-card min-width="1200" max-width="1200">
      <v-card-title> Edit your Mulit-Campaign Here </v-card-title>
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
        <v-btn color="success" class="mr-4" @click="editCampaignDone()"
          >Done</v-btn
        >
        <v-btn color="warning" class="mr-4" @click="editCampaignClose()"
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
  props: {
    campaignID: Number,
    nameProp: String,
  },
  data() {
    return {
      name: "",
      picked: [],
    };
  },

  created() {
    this.$store.dispatch("getCampaignsList");
    this.eidtCampaignLoad();
    this.name = this.nameProp;
  },

  methods: {
    async eidtCampaignLoad() {
      let res = await axios({
        method: "get",
        url: "/api/campaignjoinlist/" + this.campaignID,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      sleep(500);

      this.picked = res.data.value;
    },

    async editCampaignDone() {
      await axios({
        method: "POST",
        url: "/api/multicampaignsedit/" + this.campaignID + "/" + this.name,
        withCredentials: true,
        data: this.picked,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      sleep(500);
      this.$emit("closeEditNew");
    },

    editCampaignClose() {
      this.$emit("closeEdit");
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
