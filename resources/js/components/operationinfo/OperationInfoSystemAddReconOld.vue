<template>
  <v-menu
    :close-on-content-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
    max-width="500px"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn icon v-bind="attrs" v-on="on" @click="open()" color="green">
        <font-awesome-icon icon="fa-solid fa-plus" />
      </v-btn>
    </template>
    <v-row no-gutters>
      <v-col cols="auto">
        <v-card rounded="xl">
          <v-row no-gutters align="end" class="pt-2"
            ><v-col cols="auto"
              ><v-autocomplete
                v-model="systemInfo.recons"
                deletable-chips
                multiple
                :menu-props="{ top: true, offsetY: true }"
                chips
                clearable
                :items="dropDown"
                item-text="name"
                item-value="id"
                auto-select-first
                dense
                hint="Add a Cyno to the System"
                hide-selected
                persistent-hint
                rounded
                solo-inverted
              >
              </v-autocomplete>
            </v-col>
          </v-row>
          <v-card-actions>
            <v-row no-gutters justify="space-between">
              <v-col cols="auto">
                <v-btn rounded color="success" @click="done()"
                  >Add</v-btn
                ></v-col
              ><v-col cols="auto">
                <v-btn rounded color="error" @click="close()"
                  >Close</v-btn
                ></v-col
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
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    open() {
      this.addShown = true;
    },

    close() {
      this.addShown = false;
    },

    async done() {
      var request = {
        recons: this.systemInfo.recons,
        opID: this.opInfo.id,
      };
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinfosystemreconupdate/" + this.item.id,
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
    ...mapGetters([]),

    ...mapState(["operationInfoPage", "operationInfoRecon"]),

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },

    dropDown() {
      var data = this.operationInfoRecon.filter(
        (r) => r.operation_info_id == this.$store.state.operationInfoPage.id
      );
      return data;
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
