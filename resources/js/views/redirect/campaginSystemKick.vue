<template>
  <div class="pr-16 pl-16 d-flex justify-center">
    <div cols="8">
      <v-card>
        <v-card-title class="justify-center"
          ><h1>Why am I on this page?</h1></v-card-title
        >
        <v-card-text>
          <span class="body-1">
            <p class="text-md-center">
              You have been kicked from the active campaign page. Most likey you
              were kicked for not adding a character to the page
            </p>
            <p class="text-md-center">
              Check MotD in your fleet to gain access to the page again and
              remember to add a character. If you fail to do so you may get a
              temp ban
            </p>
          </span>
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>
<script>
import Axios from "axios";
import moment, { now, unix, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `EveStuff - WHAT DID YOU DO`;
  },
  data() {
    return {
      //timersAll: [],
    };
  },

  created() {},

  async mounted() {
    this.log();
  },
  methods: {
    log() {
      var request = {
        url: this.$route.path,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/url",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },
  computed: {},
  beforeDestroy() {},
};
</script>
