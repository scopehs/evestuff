<template>
  <div>
    <v-btn icon @click="click()">
      <font-awesome-icon :icon="text" shake size="2xl" />
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

  methods: {
    click() {
      if (this.item.priority == 0) {
        var num = 1;
      } else {
        var num = 0;
      }
      this.item.priority = num;
      var request = {
        priority: num,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/campaignpriority/" + this.item.id,
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
    text() {
      if (this.item.priority == 0) {
        return "fa-regular fa-bell";
      } else {
        return "fa-solid fa-bell";
      }
    },
  },
};
</script>
