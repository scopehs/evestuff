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
              You do not have the required roles to access this page.
            </p>
            <p class="text-md-center">
              If you should have access to this page, ask in mumble
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
  data() {
    return {
      //timersAll: [],
    };
  },

  created() {},

  async mounted() {},
  methods: {},
  computed: {},
  beforeDestroy() {},
};
</script>
