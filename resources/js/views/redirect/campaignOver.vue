<template>
  <div class="pr-16 pl-16 d-flex justify-center">
    <div cols="8">
      <v-card>
        <v-card-title class="justify-center"
          ><h1>Why am I on this page?</h1></v-card-title
        >
        <v-card-text>
          <span class="body-1">
            <p class="text-md-center">The Fleet is over!!! Hope you had fun.</p>
            <p class="text-md-center">
              If you have any feedback after using the tool, feel free to press
              the button on the top right.
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
    return `EveStuff - Your Job Is done.  Thanks`;
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
