<template>
  <div>
    <!-- ?? //! hide-default-header -->
    <v-data-table
      v-if="showTable"
      :headers="headers"
      :items="filteredItems"
      item-key="node"
      disable-sort
      dense
      hide-default-footer
      disable-pagination
      class="elevation-0 rounded-xl full-width"
    >
      <template v-slot:[`item.op_user.name`]="{ item }">
        <div class="d-inline-flex align-items-center">
          <div class="d-inline-flex align-items-center">
            {{ item.op_user.name }}
          </div>
        </div>
      </template>
      <template v-slot:[`item.node_status.name`]="{ item }">
        <!-- <NewSystemTableStatusButton
          :node="item"
          :operationID="operationID"
          :extra="2"
        /> -->
      </template>
      <template v-slot:[`item.created_at`]="{ item }">
        <!-- <NewSystemTableTimer
          :node="item"
          :operationID="operationID"
          :extra="2"
          :tidiProp="node.system.tidi"
          :systemIDProp="node.system_id"
        /> -->
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-btn icon @click="deleteNode(item)" class="pl-4" color="orange darken-3">
          <font-awesome-icon icon="fa-solid fa-trash-can"
        /></v-btn>
      </template>
      <template v-slot:[`item.op_user.ship`]="{ item }" class="pl-0">
        <!-- <span v-if="item.op_user.name != null">
          {{ item.op_user.ship }} - T{{ item.op_user.entosis }}
        </span> -->
      </template>
    </v-data-table>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    node: Object,
    operationID: Number,
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
          value: "op_user.name",
          width: "25%",
          align: "start",
        },

        {
          text: "Main",
          value: "op_user.user.name",
          align: "center",
        },
        {
          text: "Ship",
          value: "op_user.ship",
          align: "start",
        },
        {
          text: "Status",
          value: "node_status.name",
          align: "start",
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
          sortable: true,
        },
        // { text: "", value: "actions", width: "5%", align: "center" },
        // {
        //     text: "",
        //     value: "b",
        //     width: "5%",
        //     align: "start"
        // }
      ],
    };
  },

  methods: {
    // async deleteNode(item) {
    //   await axios({
    //     method: "delete", //you can set what request you want to be
    //     url: "/api/newdeleteextanode/" + item.id,
    //     withCredentials: true,
    //     headers: {
    //       Accept: "application/json",
    //       "Content-Type": "application/json",
    //     },
    //   });
    // },
  },

  computed: {
    ...mapGetters([]),

    // filteredItems() {
    //   return this.node.none_prime_node_user;
    // },

    // showTable() {
    //   var count = this.filteredItems.length;
    //   if (count > 0) {
    //     return true;
    //   } else {
    //     return false;
    //   }
    // },
  },
};
</script>

<style></style>
