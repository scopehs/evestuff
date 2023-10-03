<template>
  <div>
    <v-dialog
      v-model="overlay"
      max-width="1200px"
      max-hight="1200px"
      z-index="0"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn class="mr-4" color="green lighten-1" v-bind="attrs" v-on="on"
          >LOGS</v-btn
        >
      </template>

      <v-card tile min-height="200px">
        <v-card-title class="d-flex justify-space-between align-center">
          <div>Logs for Nats Health</div>
          <v-card tile flat color="#121212" class="align-start">
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              filled
              hide-details
            ></v-text-field>
          </v-card>
        </v-card-title>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredItems"
            :search="search"
            item-key="id"
            height="500px"
            :sort-by="['created_at']"
            :sort-desc="[true, false]"
            fixed-header
            :items-per-page="50"
            :footer-props="{
              'items-per-page-options': [10, 20, 50, 100, -1],
            }"
            class="elevation-24"
          >
            <template slot="no-data"> No FCs </template>
            <!-- :color="pillColor(item)" -->
            <template v-slot:[`item.addRemove`]="{ item }">
              <span>
                <v-btn
                  rounded
                  :outlined="true"
                  x-small
                  @click="pillClick(item)"
                >
                  ADD
                </v-btn>
                <v-btn icon @click="pillDelete(item)">
                  <font-awesome-icon icon="fa-solid fa-trash-can"
                /></v-btn>
              </span>
            </template>
          </v-data-table>
        </v-card-text>
        <v-card-actions>
          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    station: Object,
  },
  data() {
    return {
      headers: [
        {
          text: "Event",
          value: "logging_type_name",
          align: "start",
          width: "15%",
        },
        {
          text: "User",
          value: "user_name",
          align: "start",
          width: "15%",
        },
        { text: "Text", value: "text", align: "center", width: "50%" },
        {
          text: "Time",
          value: "created_at",
          align: "end",
          width: "20%",
        },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      overlay: false,
      search: "",
    };
  },

  methods: {
    close() {
      this.overlay = false;
    },
  },

  computed: {
    ...mapState(["loggingRcSheet"]),
    filteredItems() {
      return this.loggingRcSheet;
    },
  },
};
</script>

<style></style>
