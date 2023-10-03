<template>
  <div>
    <v-dialog
      v-model="showWatching"
      max-width="700px"
      z-index="0"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on: menu, attrs }">
        <v-tooltip
          color="#121212"
          bottom
          :open-delay="2000"
          :disabled="$store.state.tooltipToggle"
        >
          <template v-slot:activator="{ on: tooltip }">
            <v-btn
              class="mr-4"
              color="warning"
              v-bind="attrs"
              @click="userViewTable()"
              v-on="{ ...tooltip, ...menu }"
              >people watching</v-btn
            ></template
          ><span
            >Shows all Users on the campaign page, and if they have set a
            character.</span
          ></v-tooltip
        >
      </template>
      <v-card tile max-height="500">
        <v-card-title class="d-flex justify-space-between align-center">
          <div>Table of all Users on this page</div>
        </v-card-title>
        <v-card-text>
          <!-- :height="tableHight" -->
          <v-data-table
            :headers="headers"
            :items="campaignMembers"
            item-key="id"
            disable-pagination
            fixed-header
            hide-default-footer
            class="elevation-24"
            dense
            :sort-by="['user_name']"
          >
            <template v-slot:[`item.check`]="{ item }">
              <span v-if="userCheck(item)">Yes</span>
              <span v-else>No</span>
            </template>
            <template v-slot:[`item.action`]="{ item }">
              <span class="d-inline-flex">
                <v-btn class="ma-2" tile @click="kickUser(item)" icon>
                  <font-awesome-icon
                    icon="fa-solid fa-user-minus"
                    color="red"
                  />
                </v-btn>
              </span>
            </template>
            <template slot="no-data"> No one is here </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    campaign_id: Number,
  },
  data() {
    return {
      headers: [
        { text: "Name", value: "user_name", width: "70%" },
        { text: "Char", value: "check", width: "20%" },
        { text: "", value: "action" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      statusflag: 0,
      toggle_exclusive: 0,
      channel: "",
      showWatching: false,
    };
  },

  async created() {
    await this.userViewTable();
    Echo.private("campaignsystemmembers." + this.campaign_id).listen(
      "CampaignUsersChanged",
      (e) => {
        if (this.$can("view_campaign_members")) {
          this.updateUserViewTable();
        }
      }
    );

    this.channel = "campaignsystemmembers." + this.campaign_id;
  },

  mounted() {},

  methods: {
    close() {
      this.showWatching = false;
    },
    userViewTable() {
      if (this.$can("view_campaign_members")) {
        this.$store.dispatch("getCampaignMembers", this.campaign_id);
      }
    },

    updateUserViewTable() {
      this.$store.dispatch("getCampaignMembers", this.campaign_id);
    },

    async kickUser(item) {
      var request = {
        user_id: item.user_id,
      };
      await axios({
        method: "POST", //you can set what request you want to be
        url: "/api/campaignsystemuserskick/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    userCheck(item) {
      let count = this.$store.state.campaignusers.filter(
        (u) => u.site_id == item.user_id
      ).length;
      if (count == 0) {
        return false;
      } else {
        return true;
      }
    },
  },

  computed: {
    ...mapState(["campaignmembers"]),

    campaignMembers() {
      return this.campaignmembers.filter(
        (m) => m.campaign_id == this.campaign_id
      );
    },
  },

  beforeDestroy() {
    Echo.leave(this.channel);
  },
};
</script>

<style></style>
