<template>
  <div>
    <v-dialog persistent max-width="700px" z-index="0" v-model="showDelete">
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          class="pt-4"
          color="purple accent-3"
          dark
          icon
          x-small
          v-bind="attrs"
          v-on="on"
          @click="open()"
        >
          <font-awesome-icon icon="fa-solid fa-trash" pull="left" />
        </v-btn>
      </template>

      <v-card tile class="d-flex flex-column">
        <v-card-title class="justify-center">
          <p>
            ARE YOU SURE<strong class="purple--text test--accent-3">
              EMILY!!!!!!!!!!!</strong
            >
          </p>
        </v-card-title>
        <v-card-actions class="justify-center">
          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn>
          <v-btn
            class="white--text"
            color="green"
            @click="deleteCampaign(item)"
          >
            YES
          </v-btn>
        </v-card-actions>
      </v-card>

      <!-- <showReconTask
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showReconTask = false"
            >
            </showReconTask> -->
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  props: {
    item: Object,
  },
  data() {
    return {
      showDelete: false,
    };
  },

  methods: {
    close() {
      this.showDelete = false;
    },

    async submit() {
      var request = {
        title: this.taskName,
        info: this.infoText,
        made_by_user_id: this.$store.state.user_id,
        systems: this.systemValue,
      };

      await axios({
        method: "post", //you can set what request you want to be
        url: "api/recontask",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then(
        (this.taskName = null),
        (this.showReconTask = false),
        (this.systemValue = []),
        (this.infoText = null)
      );
    },

    async deleteCampaign(item) {
      await axios({
        method: "delete", //you can set what request you want to be
        url: "/api/multicampaigns/" + item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      sleep(500);

      this.$store.dispatch("getMultiCampaigns");
    },

    async open() {},
  },

  computed: {
    ...mapGetters([]),
    ...mapState(["systemlist"]),

    showSubmit() {
      if (
        this.taskName != null &&
        this.infoText != null &&
        this.systemValue != []
      ) {
        return true;
      } else {
        return false;
      }
    },

    systemList() {
      return this.systemlist;
    },
  },

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
