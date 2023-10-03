<template>
  <div>
    <v-dialog
      v-model="overlay"
      max-width="500px"
      max-hight="1200px"
      z-index="0"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn class="mr-4" color="green lighten-1" v-bind="attrs" v-on="on"
          >Edit Fleets</v-btn
        >
      </template>

      <v-card tile max-width="500px" min-height="200px">
        <v-card-title class="d-flex justify-space-between align-center">
          <div>Table of Fleets</div>
          <v-card width="500" tile flat color="#121212" class="align-start">
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              filled
              hide-details
            ></v-text-field>
          </v-card>
          <div>
            <v-menu
              :close-on-content-click="false"
              :value="addShown"
              transition="fab-transition"
              origin="100% -30%"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  text
                  v-bind="attrs"
                  v-on="on"
                  @click="addShown = true"
                  color="success"
                  ><font-awesome-icon icon="fa-solid fa-plus" pull="left" />
                  Fleet</v-btn
                >
              </template>
              <v-row no-gutters>
                <div>
                  <v-card class="pa-2" tile width="100%">
                    <v-text-field
                      v-model="newFleetName"
                      label="Fleet Name"
                      required
                      autofocus
                    ></v-text-field>

                    <v-btn
                      color="success"
                      class="mr-4"
                      @click="NewFleetSubmit()"
                      >submit</v-btn
                    >
                    <v-btn color="warning" class="mr-4" @click="closeAdd()"
                      >Close</v-btn
                    >
                    <!-- <v-btn @click="clear">clear</v-btn> -->
                  </v-card>
                </div>
              </v-row>
            </v-menu>
          </div>
        </v-card-title>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredItems"
            :search="search"
            height="500px"
            item-key="id"
            :sort-by="['name']"
            disable-pagination
            fixed-header
            hide-default-footer
            class="elevation-24"
          >
            <template slot="no-data"> No Fleets </template>
            <!-- :color="pillColor(item)" -->
            <template v-slot:[`item.addRemove`]="{ item }">
              <span>
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
import axios from "axios";
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {},
  data() {
    return {
      headers: [
        { text: "Name", value: "name" },
        { text: "", value: "addRemove", align: "end" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      newFleetName: null,

      addShown: false,
      overlay: false,
      search: "",
    };
  },

  methods: {
    close() {
      this.overlay = false;
    },

    newFleetClose() {
      this.addShown = false;
      this.newFleetName = null;
    },

    async pillDelete(item) {
      await axios({
        method: "DELETE",
        url: "/api/fleetdelete/" + item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async NewFleetSubmit() {
      var request = {
        name: this.newFleetName,
      };

      await axios({
        method: "PUT",
        url: "/api/fleetnew",
        data: request,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Contect-Type": "application/json",
        },
      });

      this.addShown = false;
      this.newFleetName = null;
    },

    async closeAdd() {
      this.addShown = false;
      this.newFleetName = null;
    },
  },

  computed: {
    ...mapState(["fleets"]),
    filteredItems() {
      return this.fleets;
    },
  },
};
</script>

<style></style>
