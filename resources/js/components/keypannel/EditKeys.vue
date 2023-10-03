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
          >Edit Keys</v-btn
        >
      </template>

      <v-card tile max-width="1200px" min-height="200px">
        <v-card-title class="d-flex justify-space-between align-center">
          <div>Table of Keys fefefe</div>
          <v-card width="1200px" tile flat color="#121212" class="align-start">
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
                  Keys</v-btn
                >
              </template>
              <v-row no-gutters>
                <div>
                  <v-card class="pa-2" tile width="100%">
                    <v-text-field
                      v-model="newKeyName"
                      label="Key name"
                      required
                      autofocus
                    ></v-text-field>

                    <v-btn color="success" class="mr-4" @click="NewKeySubmit()"
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
            item-key="id"
            :loading="loading"
            :sort-by="['name']"
            :items-per-page="20"
            :search="search"
            :footer-props="{
              'items-per-page-options': [10, 20, 50, 100, -1],
            }"
            class="elevation-5"
          >
            <template v-slot:[`item.fleets`]="{ item }">
              <div class="d-inline-flex">
                <v-menu>
                  <template v-slot:activator="{ on, attrs }">
                    <div>
                      <v-btn icon v-bind="attrs" v-on="on" color="success"
                        ><font-awesome-icon icon="fa-solid fa-plus"
                      /></v-btn>
                    </div>
                  </template>

                  <v-list>
                    <v-list-item
                      v-for="(list, index) in filterDropdownList(item.fleets)"
                      :key="index"
                      @click="(keyAddFleetText = list.id), keyAddFleet(item)"
                    >
                      <v-list-item-title>{{ list.name }}</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </div>

              <div class="d-inline-flex">
                <div
                  v-for="(fleet, index) in fliterFleets(item.fleets)"
                  :key="index"
                  class="pr-2"
                >
                  <v-chip
                    pill
                    :close="pillClose(fleet.name)"
                    dark
                    @click:close="
                      (userRemoveFleetText = fleet.id), keyRemoveFleet(item)
                    "
                  >
                    <span> {{ fleet.name }}</span>
                  </v-chip>
                </div>
              </div>
            </template>
            <template v-slot:[`item.addRemove`]="{ item }">
              <span>
                <v-btn icon @click="pillDelete(item)">
                  <font-awesome-icon icon="fa-solid fa-trash-can"
                /></v-btn>
              </span>
            </template>
            <template slot="no-data"> Nothing matches your filters </template>
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
        { text: "Fleets", value: "fleets", width: "80%" },
        { text: "", value: "addRemove", align: "end" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      newKeyName: null,
      loadingr: false,
      loadingf: false,
      loading: false,
      toggle_exclusive: 0,
      addShown: false,
      overlay: false,
      search: "",
      keyAddFleetText: "",
      keyRemoveFleetText: "",
    };
  },

  methods: {
    filterDropdownList(item) {
      let fleetID = item.map((i) => i.id);

      var filter = this.fleets.filter((r) => !fleetID.includes(r.id));
      filter = filter.filter((r) => r.name != "All");
      if (this.$can("edit_fleet_keys")) {
        return filter;
      }
    },

    pillClose(name) {
      if (this.$can("edit_fleet_keys")) {
        return true;
      } else {
        return false;
      }
    },

    fliterFleets(fleets) {
      return fleets;
    },
    close() {
      this.overlay = false;
    },

    async keyAddFleet(item) {
      var request = {
        fleet_type_id: this.keyAddFleetText,
        key_type_id: item.id,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/fleetssadd",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    newKeyClose() {
      this.addShown = false;
      this.newFleetName = null;
    },

    async keyRemoveFleet(item) {
      var request = {
        fleet_type_id: this.keyRemoveFleetText,
        key_type_id: item.id,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/fleetsremove",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async pillDelete(item) {
      await axios({
        method: "DELETE",
        url: "/api/keydelete/" + item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async NewKeySubmit() {
      var request = {
        name: this.newKeyName,
      };

      await axios({
        method: "PUT",
        url: "/api/keynew",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Contect-Type": "application/json",
        },
      });

      this.addShown = false;
      this.newKeyName = null;
    },

    async closeAdd() {
      this.addShown = false;
      this.newFleetName = null;
    },
  },

  computed: {
    ...mapState(["keyfleets", "fleets"]),
    filteredItems() {
      return this.keyfleets;
    },
  },
};
</script>

<style></style>
