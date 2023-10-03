<template>
  <div>
    <v-card min-width="1200" max-width="1200" rounded="xl">
      <v-card-title class="primary">
        Make your Inital-Campaign Here
      </v-card-title>
      <v-card-text>
        <v-text-field
          label="Inital-Campaign Name"
          v-model="name"
          rounded
          hint="Enter The name of your Campaign here"
          filled
        >
        </v-text-field>

        <v-autocomplete
          v-model="picked"
          :items="list"
          label="Select"
          chips
          clearable
          deletable-chips
          dense
          hint="Which Campaigns do you want"
          hide-selected
          multiple
          persistent-hint
          rounded
          small-chips
          solo-inverted
        ></v-autocomplete>
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

  created() {},

  methods: {
    async addCampaignDone() {
      let id = moment().format("x");

      await axios({
        method: "POST",
        url: "/api/startcampaigns/" + id + "/" + this.name,
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
    ...mapState(["constellationlist"]),

    list() {
      return this.constellationlist;
    },
  },
};
</script>
