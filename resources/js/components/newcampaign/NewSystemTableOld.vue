<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="12">
          <v-data-table
            :headers="_headers"
            :single-expand="singleExpand"
            :items="nodes"
            disable-sort
            dense
            :item-class="itemRowBackground"
            :expanded="expanded"
            hide-default-footer
            disable-pagination
            class="elevation-24 rounded-xl full-width"
          >
            <template slot="no-data"> No Nodes Reported In System </template>
            <template v-slot:[`item.actions`]="{ item }">
              <v-btn icon class="pl-5" @click="removenode(item)">
                <font-awesome-icon icon="fa-solid fa-trash-can" color="orange darken-3"
              /></v-btn>
            </template>
            <!-- <template v-slot:[`item.op_users`]="{ item }">
              <v-row no-gutters align="baseline">
                <v-col cols="auto">
                  <AddPilot :node="item" :operationID="operationID" />
                </v-col>
                <v-col cols="auto">
                  <NewNodeExtraChar :node="item" :operationID="operationID" />
                </v-col>
                <v-col cols="auto">
                  <AddPilotAdmin
                    v-if="$can('campaigns_admin_access')"
                    :node="item"
                    :operationID="operationID"
                  />
                </v-col>
              </v-row>
            </template> -->
            <!-- <template v-slot:[`item.TODOMain`]="{ item }">
              <NewSystemTableSimpleText :node="item" :type="1" />
            </template>
            <template v-slot:[`item.TODOShip`]="{ item }">
              <NewSystemTableSimpleText :node="item" :type="2" />
            </template> -->
            <!-- <template v-slot:[`item.node_status.name`]="{ item }">
              <NewSystemTableStatusButton :node="item" :operationID="operationID" />
            </template> -->

            <!-- <template v-slot:[`item.created_at`]="{ item }">
              <NewSystemTableTimer
                :node="item"
                :operationID="operationID"
                :extra="1"
                :tidiProp="item.system.tidi"
                :systemIDProp="item.system_id"
              />
            </template> -->

            <template v-slot:expanded-item="{ headers, item }">
              <td :colspan="headers.length" align="center">
                <NewJoinNodeTable :node="item" :operationID="operationID" />
              </td>
            </template>

            <template v-slot:[`header.actions`]="{ headers }">
              <!-- <AddNode
                :item="item"
                :operationID="operationID"
                :activeCampaigns="activeCampaigns"
              /> -->
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    item: Object,
    operationID: Number,
    activeCampaigns: Array,
  },
  data() {
    return {
      singleExpand: false,
      headers: [
        {
          text: "NodeID",
          value: "name",
          sortable: false,
        },
        {
          text: "Pilot",
          value: "op_users",
          sortable: true,
        },

        {
          text: "Main",
          value: "TODOMain",
          sortable: true,
        },

        {
          text: "Ship",
          value: "TODOShip",
          sortable: true,
        },
        {
          text: "Status",
          value: "node_status.name",
          sortable: true,
        },

        {
          text: "Age/Hack",
          value: "created_at",
          sortable: true,
          align: "center",
        },
        {
          text: "",
          value: "actions",
          align: "end",
          sortable: false,
        },
      ],
    };
  },

  methods: {
    async removenode(item) {
      var id = item.id;

      await axios({
        method: "DELETE",
        url: "/api/deletenode/" + id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    itemRowBackground: function (item) {
      if (item.node_status.id == 7) {
        return "style-1-table";
      } else if (item.node_status.id == 8) {
        return "style-2-table";
      }
      //   else if (item.under_attack == 1) {
      //     return "style-4";
      //   }
    },
  },

  computed: {
    ...mapState([]),

    ...mapGetters([]),

    nodes() {
      return this.item.new_nodes;
    },

    // _headers() {
    //   if (this.activeCampaigns.length == 1) {
    //     var headers = [
    //       {
    //         text: "NodeID",
    //         value: "name",
    //         sortable: false,
    //       },
    //       {
    //         text: "Pilot",
    //         value: "op_users",
    //         sortable: true,
    //       },

    //       {
    //         text: "Main",
    //         value: "TODOMain",
    //         sortable: true,
    //       },

    //       {
    //         text: "Ship",
    //         value: "TODOShip",
    //         sortable: true,
    //       },
    //       {
    //         text: "Status",
    //         value: "node_status.name",
    //         sortable: true,
    //       },

    //       {
    //         text: "Age/Hack",
    //         value: "created_at",
    //         sortable: true,
    //         align: "center",
    //       },
    //       {
    //         text: "",
    //         value: "actions",
    //         align: "end",
    //         sortable: false,
    //       },
    //     ];
    //   } else {
    //     var headers = [
    //       {
    //         text: "Campaign",
    //         value: "campaign.name",
    //         sortable: false,
    //       },

    //       {
    //         text: "NodeID",
    //         value: "name",
    //         sortable: false,
    //       },
    //       {
    //         text: "Pilot",
    //         value: "op_users",
    //         sortable: true,
    //       },

    //       {
    //         text: "Main",
    //         value: "TODOMain",
    //         sortable: true,
    //       },

    //       {
    //         text: "Ship",
    //         value: "TODOShip",
    //         sortable: true,
    //       },
    //       {
    //         text: "Status",
    //         value: "node_status.name",
    //         sortable: true,
    //       },

    //       {
    //         text: "Age/Hack",
    //         value: "created_at",
    //         sortable: true,
    //         align: "center",
    //       },
    //       {
    //         text: "",
    //         value: "actions",
    //         align: "end",
    //         sortable: false,
    //       },
    //     ];
    //   }
    //   return headers;
    // },
    count() {
      var count = this.activeCampaigns.length;
      return count;
    },

    expanded() {
      if (this.nodes) {
        var data = this.nodes.filter((f) => f.none_prime_node_user.length > 0);
        return data;
      }
    },
  },
};
</script>

<style>
.style-4-table {
  background-color: rgba(255, 153, 0, 0.199);
}
.style-3-table {
  background-color: rgb(255, 172, 77);
}
.style-2-table {
  background: linear-gradient(-45deg, #00768b, #00d9ff, #5fccff, #00a2ff);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

.style-1-table {
  background: linear-gradient(-45deg, #8b0000, #ff0000, #ff5f5f, #ff0000);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}
</style>
