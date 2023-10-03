<template>
  <v-dialog v-model="showAdd" width="500" @click:outside="close()">
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        icon
        v-bind="attrs"
        v-on="on"
        color="light-blue darken-4"
        @click="showAdd"
      >
        <font-awesome-icon icon="fa-solid fa-plus" size="2xl"
      /></v-btn>
    </template>
    <v-card
      tile
      max-width="1200px"
      max-height="500px"
      class="d-flex flex-column"
    >
      <v-card-title
        class="d-lg-inline-block justify-space-between align-center"
      >
        <div>
          Add a Character to node {{ nodeItem.node }} in
          {{ nodeItem.system_name }}
        </div>
        <div>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
        </div>
      </v-card-title>
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="filteredItems"
          item-key="id"
          disable-pagination
          hide-default-footer
          :search="search"
          class="elevation-24"
          dense
        >
          <template v-slot:[`item.actions`]="{ item }">
            <v-btn
              rounded
              :outlined="true"
              x-small
              color="green"
              @click="addChar(item)"
            >
              <font-awesome-icon icon="fa-solid fa-plus" pull="left" />

              Add
            </v-btn></template
          >
          <template slot="no-data"> No Free Characters </template>
        </v-data-table>
      </v-card-text>
      <v-spacer></v-spacer
      ><v-card-actions
        ><v-btn class="white--text" color="teal" @click="close()">
          Close
        </v-btn></v-card-actions
      >
    </v-card>
  </v-dialog>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    nodeItem: Object,
  },
  data() {
    return {
      headers: [
        { text: "Name", value: "char_name" },
        { text: "Main", value: "main_name" },
        { text: "", value: "actions" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      search: "",
      showAdd: false,
    };
  },

  methods: {
    close() {
      this.showAdd = false;
    },

    addChar(item) {
      if (this.nodeItem.custom_campaign_id == null) {
        var request = {
          campaignUserID: item.id,
          campaignSystemID: this.nodeItem.id,
        };

        axios({
          method: "put", //you can set what request you want to be
          url:
            "/api/addchartonodeadmin/" +
            this.nodeItem.id +
            "/" +
            this.nodeItem.campaign_id,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      } else {
        var request = {
          campaignUserID: item.id,
          campaignSystemID: this.nodeItem.id,
        };

        axios({
          method: "put", //you can set what request you want to be
          url:
            "/api/addchartonodeadmin/" +
            this.nodeItem.id +
            "/" +
            this.nodeItem.custom_campaign_id,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }
    },
  },

  computed: {
    ...mapState(["campaignusers"]),
    filteredItems() {
      if (this.nodeItem.custom_campaign_id == null) {
        return this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id == 1 &&
            campaignusers.campaign_id == this.nodeItem.campaign_id &&
            campaignusers.node_id == null
        );
      } else {
        return this.campaignusers.filter(
          (campaignusers) =>
            campaignusers.role_id == 1 &&
            campaignusers.campaign_id == this.nodeItem.custom_campaign_id &&
            campaignusers.node_id == null
        );
      }
    },
  },
};
</script>

<style></style>
