<template>
  <div class="text-center">
    <v-menu
      v-model="menu"
      :close-on-click="false"
      :close-on-content-click="false"
      :nudge-left="200"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn outlined rounded small v-bind="attrs" v-on="on">
          Add Missing Corp
        </v-btn>
      </template>
      <v-card>
        <v-card-title>
          ONLY USE IF CORP TICKER IS NOT FOUND<br />
          ENTER THE CORP TICKER</v-card-title
        >
        <v-card-text>
          <v-text-field
            outlined
            v-model="ticker"
            title="Corp Ticker"
          ></v-text-field></v-card-text
        ><v-card-actions>
          <v-btn @click="submit()">Submit</v-btn>
          <v-btn @click="cancel()">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-menu>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {},
  data() {
    return {
      menu: false,
      ticker: null,
    };
  },

  methods: {
    async submit() {
      await this.$store
        .dispatch("updateTickList", this.ticker)
        .then((this.menu = false), this.$emit("setTicker"));
    },

    cancel() {
      this.ticker = null;
      this.menu = false;
    },
  },
  computed: {},

  beforeDestroy() {},
};
</script>

<style></style>
