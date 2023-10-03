<template>
  <v-col cols="10">
    <v-card tile>
      <v-card-title class="d-flex justify-space-between align-center">
        <div>Table of all Characters involved in this Campaign</div>
        <div>
          <v-btn-toggle
            right-align
            v-model="toggle_exclusive"
            mandatory
            :value="0"
          >
            <v-btn @click="statusflag = 5"> All </v-btn>
            <v-btn @click="statusflag = 1"> Hackers </v-btn>
            <v-btn @click="statusflag = 2"> Scouts </v-btn>
            <v-btn @click="statusflag = 3"> FCs </v-btn>
            <v-btn @click="statusflag = 4"> Commands </v-btn>
          </v-btn-toggle>
        </div>
      </v-card-title>
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="filteredItems"
          item-key="id"
          disable-pagination
          :height="tableHight"
          hide-default-footer
          class="elevation-24"
          dense
        >
          <template slot="no-data"> No one is here </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-col>
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
        { text: "Name", value: "char_name", width: "10%" },
        { text: "Main", value: "main_name" },
        { text: "Role", value: "role_name" },
        { text: "Ship", value: "ship" },
        { text: "Entosis", value: "link" },
        { text: "System", value: "system_name" },
        { text: "Node", value: "node_id" },
        { text: "Status", value: "user_status_name" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      statusflag: 0,
      toggle_exclusive: 0,
    };
  },

  computed: {
    ...mapState(["campaignusers"]),

    filteredItems() {
      // var timers = this.$store.state.timers;
      if (this.statusflag == 1) {
        return this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id == 1 &&
            campaignusers.campaign_id == this.campaign_id
        );
      }
      if (this.statusflag == 2) {
        return this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id == 2 &&
            campaignusers.campaign_id == this.campaign_id
        );
      }
      if (this.statusflag == 3) {
        return this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id == 3 &&
            campaignusers.campaign_id == this.campaign_id
        );
      }
      if (this.statusflag == 4) {
        return this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id == 4 &&
            campaignusers.campaign_id == this.campaign_id
        );
      } else {
        return this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id != 10 &&
            campaignusers.campaign_id == this.campaign_id
        );
      }
    },
    tableHight() {
      // var timers = this.$store.state.timers;
      if (this.statusflag == 1) {
        var count = this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id == 1 &&
            campaignusers.campaign_id == this.campaign_id
        ).length;
        var sum = count * 32;
        if (sum >= 320) {
          return 352;
        } else if (count == 1) {
          return 64;
        } else if (count == 2) {
          return 96;
        } else {
          return sum + 32;
        }
      }
      if (this.statusflag == 2) {
        var count = this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id == 2 &&
            campaignusers.campaign_id == this.campaign_id
        ).length;
        var sum = count * 32;
        if (sum >= 320) {
          return 352;
        } else if (count == 1) {
          return 64;
        } else if (count == 2) {
          return 96;
        } else {
          return sum + 32;
        }
      }
      if (this.statusflag == 3) {
        var count = this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id == 3 &&
            campaignusers.campaign_id == this.campaign_id
        ).length;
        var sum = count * 32;
        if (sum >= 320) {
          return 352;
        } else if (count == 1) {
          return 64;
        } else if (count == 2) {
          return 96;
        } else {
          return sum + 32;
        }
      }
      if (this.statusflag == 4) {
        var count = this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id == 4 &&
            campaignusers.campaign_id == this.campaign_id
        ).length;
        var sum = count * 32;
        if (sum >= 320) {
          return 352;
        } else if (count == 1) {
          return 64;
        } else if (count == 2) {
          return 96;
        } else {
          return sum + 32;
        }
      } else {
        var count = this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id != 10 &&
            campaignusers.campaign_id == this.campaign_id
        ).length;
        var sum = count * 32;
        if (sum >= 320) {
          return 352;
        } else if (count == 1) {
          return 64;
        } else if (count == 2) {
          return 96;
        } else {
          return sum + 32;
        }
      }
    },
  },
};
</script>

<style></style>
