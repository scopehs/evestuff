<template>
  <div>
    <v-btn
      class="mx-2"
      fab
      :href="url"
      target="_blank"
      color="green darken-4"
      dark
      x-small
    >
      {{ item.webway[0]["jumps"] }}
    </v-btn>
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
    return {};
  },

  methods: {},

  computed: {
    url() {
      var base = "https://webway.apps.gnf.lt/routing?share=";
      var url = base + this.item.webway[0]["webway"];
      return url;
    },
  },
};
</script>
