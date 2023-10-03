<template>
  <div>
    <v-data-table
      v-if="showTable"
      :headers="headers"
      :items="filteredItems"
      item-key="node"
      disable-sort
      dense
      hide-default-footer
      disable-pagination
      hide-default-header
      class=""
    >
      <template v-slot:[`item.charname`]="{ item }">
        <div class="d-inline-flex align-items-center">
          <div class="d-inline-flex align-items-center">
            {{ item.charname }}
          </div>
        </div>
      </template>
      <template v-slot:[`item.statusName`]="{ item }">
        <div class="d-inline-flex align-items-center">
          <v-menu offset-y>
            <template v-slot:activator="{ on, attrs }">
              <div>
                <v-chip
                  v-bind="attrs"
                  v-on="on"
                  pill
                  outlined
                  small
                  :color="pillColor(item)"
                >
                  {{ item.statusName }}
                </v-chip>
              </div>
            </template>

            <v-list>
              <v-list-item
                v-for="(list, index) in dropdown_edit"
                :key="index"
                @click="
                  (item.campaign_system_status_id = list.value),
                    (item.statusName = list.title),
                    statusClick(item)
                "
              >
                <v-list-item-title>{{ list.title }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
          <v-btn class="pl-4" color="orange darken-3" @click="deleteNode(item)">
            <font-awesome-icon icon="fa-solid fa-trash-can"
          /></v-btn>
        </div>
      </template>
      <template v-slot:[`item.ship`]="{ item }" class="pl-0">
        <span v-if="item.charname != null">
          {{ item.ship }} - T{{ item.link }}
        </span>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    sysid: Number,
  },
  data() {
    return {
      headers: [
        {
          text: "",
          value: "padding",
          width: "5%",
          align: "start",
        },
        {
          text: "Pilot",
          value: "charname",
          width: "25%",
          align: "start",
        },

        {
          text: "Main",
          value: "mainname",

          align: "center",
        },
        {
          text: "Ship",
          value: "ship",

          align: "start",
        },
        {
          text: "",
          value: "statusName",

          align: "start",
        },
        // { text: "", value: "actions", width: "5%", align: "center" },
        // {
        //     text: "",
        //     value: "b",
        //     width: "5%",
        //     align: "start"
        // }
      ],

      dropdown_edit: [
        { title: "New", value: 1 },
        { title: "Warm up", value: 2 },
        { title: "Hacking", value: 3 },
        { title: "Pushed off", value: 6 },
      ],
      expanded: [],
      singleExpand: false,
    };
  },

  methods: {
    pillColor(item) {
      if (item.campaign_system_status_id == 1) {
        return "deep-orange lighten-1";
      }
      if (item.campaign_system_status_id == 2) {
        return "lime darken-4";
      }
      if (item.campaign_system_status_id == 3) {
        return "green darken-3";
      }
      if (item.campaign_system_status_id == 6) {
        return "FF5EEA";
      }
    },

    checkShowAddRemove(item) {
      if (
        item.charname != null &&
        this.charCount != 0 &&
        item.campaign_system_status_id != 4 &&
        item.campaign_system_status_id != 5 &&
        item.campaign_system_status_id != 7 &&
        item.campaign_system_status_id != 8
      ) {
        return true;
      } else if (this.$can("campaigns_admin_access")) {
        return true;
      } else {
        return false;
      }
    },

    async deleteNode(item) {
      await axios({
        method: "PUT", //you can set what request you want to be
        url: "/api/deleteextranode/" + item.id + "/" + item.campaign_id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async statusClick(item) {
      var request = [];
      if (
        item.campaign_system_status_id == 1 ||
        item.campaign_system_status_id == 2 ||
        item.campaign_system_status_id == 3
      ) {
        request = {
          campaign_system_status_id: item.campaign_system_status_id,
        };
      }
      if (item.campaign_system_status_id == 6) {
        await this.deleteNode(item);
        return;
      }
      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/nodejoinupdate/" + item.id + "/" + item.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    ...mapGetters([
      "getNodeJoinByNode",
      "getNodeJoinByNodeCount",
      "getCampaignUsersByUserIdEntosisCount",
    ]),

    filteredItems() {
      return this.getNodeJoinByNode(this.sysid);
    },

    showTable() {
      if (this.getNodeJoinByNodeCount(this.sysid) > 0) {
        return true;
      } else {
        return false;
      }
    },

    charCount() {
      return this.getCampaignUsersByUserIdEntosisCount(
        this.$store.state.user_id
      );
    },
  },
};
</script>

<style></style>
