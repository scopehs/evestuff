<template>
  <div>
    <v-dialog
      max-width="500px"
      min-width="500px"
      z-index="0"
      v-model="showReconTaskDelete"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="red" dark v-bind="attrs" v-on="on">
          <font-awesome-icon icon="fa-solid fa-trash-can" pull="left" />
          Delete
        </v-btn>
      </template>

      <v-card
        tile
        max-width="500px"
        min-width="500px"
        max-height="1000px"
        min-height="100px"
        class="d-flex flex-column"
      >
        <v-card-title class="justify-center">
          <p>Delete Task</p>
        </v-card-title>
        <v-card-text class="justify-center" align="center">
          <p>Are you sure you want delete this task?</p>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions class="justify-center">
          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn>
          <v-btn class="white--text" color="red" @click="submit()">
            Delete
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
    item: Object,
  },
  data() {
    return {
      showReconTaskDelete: false,
    };
  },

  methods: {
    close() {
      this.showReconTaskDelete = false;
    },

    async submit() {
      await axios({
        method: "delete", //you can set what request you want to be
        url: "api/recontask/" + this.item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then((this.showReconTaskDelete = false));
    },
  },

  computed: {},

  beforeDestroy() {},
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
