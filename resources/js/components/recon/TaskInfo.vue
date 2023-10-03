<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      v-model="showTaskInfo"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon color="blue" v-bind="attrs" v-on="on" @click="open()">
          <font-awesome-icon :icon="icon"
        /></v-btn>
      </template>
      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="700px"
        class="d-flex flex-column"
      >
        <v-card-title>Notes for Station {{ task.title }}. </v-card-title>
        <v-card-text>{{ task.info }}<v-divider></v-divider> </v-card-text>

        <v-card-actions>
          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn></v-card-actions
        >
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    task: Object,
  },
  data() {
    return {
      showNumber: false,
      showTaskInfo: false,
    };
  },

  async created() {},

  methods: {
    close() {
      this.showTaskInfo = false;
    },

    open() {
      this.showNumber = false;
    },
  },

  computed: {
    icon() {
      return "fa-solid fa-circle-info";
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
