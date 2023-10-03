<template>
  <div>
    <v-dialog persistent v-model="overlay" max-width="500px" z-index="0">
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          class="mr-4"
          color="green lighten-1"
          v-bind="attrs"
          x-small
          v-on="on"
          >Corp</v-btn
        >
      </template>

      <v-card tile max-width="500px" min-height="200px">
        <v-card-title class="d-flex justify-space-between align-center">
          <div>Corp List</div>
          <v-card width="500" tile flat color="#121212" class="align-start">
          </v-card>
        </v-card-title>
        <v-card-text>
          <v-autocomplete
            v-model="value"
            :items="items"
            dense
            filled
            label="Corp Tickers"
          ></v-autocomplete>
        </v-card-text>
        <v-card-actions>
          <v-btn class="white--text" color="teal" @click="done()"> Done </v-btn>
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
      value: null,
      overlay: null,
    };
  },

  methods: {
    close() {
      this.value = null;
      this.overlay = false;
    },

    async done() {
      var request = {
        corpid: this.value,
      };

      await axios({
        method: "put",
        url: "/api/rcfixcorp/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then(close());
    },
  },

  computed: {
    ...mapState(["ticklist"]),
    items() {
      return this.ticklist;
    },
  },
};
</script>

<style></style>
