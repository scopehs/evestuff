<template>
  <div>
    <v-menu transition="fade-transition" v-if="showButton == 1">
      <template v-slot:activator="{ on, attrs }">
        <v-chip dark :color="filterCharsOnTheWay" v-bind="attrs" v-on="on" small>
          Ready To Go
        </v-chip>
      </template>
      <v-list>
        <v-list-item
          v-for="(list, index) in freeChars"
          :key="index"
          @click="clickOnTheWay(list.id)"
        >
          <v-list-item-title>{{ list.name }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-btn
      v-else-if="showButton == 2"
      dark
      :color="filterCharsOnTheWay"
      small
      rounded
      class="no-uppercase"
    >
      Ready To Go
    </v-btn>
    <span v-else> Ready To Go - </span>
    <v-menu transition="fade-transition">
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          class="mx-2"
          v-bind="attrs"
          :disabled="fabOnTheWayDisbale"
          v-on="on"
          fab
          color="green darken-4"
          dark
          x-small
        >
          {{ OnTheWayCount }}
        </v-btn>
      </template>
      <v-list>
        <v-list-item v-for="(list, index) in charsReadyToGoAll" :key="index">
          <v-list-item-title>
            {{ list.name }} - {{ list.ship }} - T{{ list.entosis
            }}<span class="pl-3" v-if="seeReadyToGoOnTheWay(list)">
              <span @click="removeReadyToGoOnTheWay(list.id)" color="orange darken-3">
                <font-awesome-icon icon="fa-solid fa-trash-can" /></span></span
          ></v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    item: Object,
    operationID: Number,
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async clickOnTheWay(opUserID) {
      var request = {
        user_status_id: 3,
        system_id: this.item.id,
      };

      await axios({
        method: "put",
        url: "/api/onthewayreadytogo/" + this.operationID + "/" + opUserID,
        data: request,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async removeReadyToGoOnTheWay(opUserID) {
      var request = {
        user_status_id: 1,
        system_id: null,
      };

      await axios({
        method: "put",
        url: "/api/onthewayreadytogo/" + this.operationID + "/" + opUserID,
        data: request,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    seeReadyToGoOnTheWay(item) {
      if (
        this.$can("campaigns_admin_access") ||
        this.$store.state.user_id == item.user_id
      ) {
        return true;
      } else {
        false;
      }
    },
  },

  computed: {
    ...mapGetters([
      "getOwnHackingCharOnOp",
      "getOpUsersReadyToGoAll",
      "getOwnHackingCharOnOpAllHackers",
    ]),

    ...mapState([]),

    showButton() {
      if (this.allOwnHackingCharsCount > 0) {
        if (this.freeCharsCount > 0) {
          return 1;
        } else {
          return 2;
        }
      } else {
        return 0;
      }
    },

    allOwnHackingChars() {
      var data = this.getOwnHackingCharOnOpAllHackers(this.operationID);
      if (data) {
        return data;
      } else {
        return [];
      }
    },

    allOwnHackingCharsCount() {
      if (this.allOwnHackingChars) {
        return this.allOwnHackingChars.length;
      } else {
        return 0;
      }
    },

    freeChars() {
      var data = this.getOwnHackingCharOnOpAllHackers(this.operationID);
      if (data) {
        data = data.filter((c) => c.user_status_id != 4);
        data = data.filter((c) => {
          if (c.system_id == this.item.id) {
            if (c.user_status_id != 3) {
              return true;
            } else {
              return false;
            }
          } else {
            return true;
          }
        });
      }
      if (data) {
        return data;
      } else {
        return [];
      }
    },

    freeCharsCount() {
      if (this.freeChars) {
        return this.freeChars.length;
      } else {
        return 0;
      }
    },

    charsReadyToGoAll() {
      return this.getOpUsersReadyToGoAll.filter((q) => q.system_id == this.item.id);
    },

    OnTheWayCount() {
      if (this.charsReadyToGoAll) {
        return this.charsReadyToGoAll.length;
      } else {
        return 0;
      }
    },

    fabOnTheWayDisbale() {
      if (this.OnTheWayCount == 0) {
        return true;
      } else {
        return false;
      }
    },

    filterCharsOnTheWay() {
      var data = this.getOwnHackingCharOnOp(this.operationID);
      if (data) {
        var count = data.filter(
          (c) => c.system_id == this.item.id && c.user_status_id == 3
        ).length;
      } else {
        var count = 0;
      }

      if (count > 0) {
        return "green";
      } else {
        return "red";
      }
    },
  },
  beforeDestroy() {},
};
</script>
<style scoped>
.no-uppercase {
  text-transform: unset !important;
}
</style>
