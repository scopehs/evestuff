<template>
  <div class="d-inline-flex align-items-md-center pl-4">
    <div>
      <span class="d-inline-flex align-items-md-center pr-2">
        <span class="pl-2" v-show="!showStockerButton">
          {{ station.user_name }}
        </span>
      </span>
    </div>
    <div>
      <v-tooltip
        color="#121212"
        bottom
        :key="'stockertooltip' + station.id"
        :open-delay="2000"
        :disabled="$store.state.tooltipToggle"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            v-show="showStockerButton"
            :key="'stockerbutton' + station.id"
            class=""
            color="blue"
            x-small
            outlined
            @click="stockerAdd()"
            v-bind="attrs"
            v-on="on"
          >
            <font-awesome-icon icon="fa-solid fa-plus" />
            Take Task</v-btn
          >
        </template>
        <span> Stocker can assign themselfs here </span>
      </v-tooltip>
      <v-btn
        icon
        v-show="!showStockerButton"
        color="orange darken-3"
        @click="stockerRemove()"
      >
        <font-awesome-icon icon="fa-solid fa-trash-can"
      /></v-btn>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    station: Object,
  },
  data() {
    return {
      stockerName: null,
    };
  },

  watch: {
    station: {
      handler() {
        this.showStockerButton;
      },
      deep: true,
    },
  },

  mounted() {},

  methods: {
    async stockerAdd() {
      var request = {
        user_id: this.$store.state.user_id,
      };

      await axios({
        method: "post",
        url: "/api/ammorequestupdate/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async stockerRemove() {
      var request = {
        user_id: null,
      };

      await axios({
        method: "post",
        url: "/api/ammorequestupdate/" + this.station.id,
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
    showStockerButton() {
      if (this.station.user_id == null) {
        return true;
      } else {
        return false;
      }
    },
  },
};
</script>

<style></style>
