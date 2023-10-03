<template>
  <v-menu
    :close-on-content-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn icon v-bind="attrs" v-on="on" @click="open()" color="blue">
        <font-awesome-icon icon="fa-solid fa-poo-storm" size="xl" />
      </v-btn>
    </template>
    <v-row no-gutters>
      <v-col cols="auto">
        <v-card rounded="xl">
          <!-- <v-card-title class="blue py-1"
            >Jammer Status for {{ item.system_name }}</v-card-title
          > -->
          <v-card-text>
            <v-row no-gutters align="end" class="pt-2">
              <!-- <v-col cols="auto"
                ><v-autocomplete
                  v-model="systemInfo.pivot.jammed_status"
                  :items="dropDown"
                  item-text="name"
                  item-value="id"
                  auto-select-first
                  dense
                  hint="What is the jammer status"
                  hide-selected
                  persistent-hint
                  rounded
                  solo-inverted
                >
                </v-autocomplete>
              </v-col> -->
            </v-row>
            <v-row no-gutters align="end" class="pt-2">
              <!-- <v-col cols="12">
                <v-slider
                  class="pt-3"
                  persistent-hint
                  min="0"
                  max="10"
                  hint="Needed Cynos"
                  v-model="systemInfo.pivot.cynos_needed"
                  thumb-label="always"
                ></v-slider>
              </v-col> -->
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-row no-gutters justify="space-between">
              <v-col cols="auto">
                <v-btn rounded color="success" @click="done()">Update</v-btn></v-col
              ><v-col cols="auto">
                <v-btn rounded color="error" @click="close()">Close</v-btn></v-col
              ></v-row
            ></v-card-actions
          >
        </v-card>
      </v-col>
    </v-row>
  </v-menu>
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
    loaded: Boolean,
    item: [Array, Object],
  },
  data() {
    return {
      addShown: false,
      old: null,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    // open() {
    //   this.old = this.systemInfo.pivot.jammed_status;
    //   this.addShown = true;
    // },

    // close() {
    //   this.systemInfo.pivot.jammed_status = this.old;
    //   this.addShown = false;
    // },

    async done() {
      var request = {
        jam: this.systemInfo.pivot.jammed_status,
        cynos: this.systemInfo.pivot.cynos_needed,
      };
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinfosystemjamupdate/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then((reponse) => {
        this.addShown = false;
      });
    },
  },

  computed: {
    ...mapGetters(["getOperationSystemInfo"]),

    ...mapState(["operationInfoPage", "operationInfoJamList"]),

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },

    dropDown() {
      return this.operationInfoJamList;
    },

    systemInfo: {
      get() {
        return this.$store.getters.getOperationSystemInfo(this.item.id);
      },
      set(newValue) {},
    },

    showEnter() {
      if (this.loaded == true) {
        return "animate__animated animate__flash animate__faster";
      }
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__flash animate__faster";
      }
    },
  },
  beforeDestroy() {},
};
</script>
