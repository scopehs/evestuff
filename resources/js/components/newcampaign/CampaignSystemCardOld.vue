<template>
  <v-expansion-panels
    class="pb-5"
    v-model="showSystemTable"
    popout
    style="cursor: context-menu"
  >
    <v-expansion-panel class="rounded-xl" style="cursor: context-menu" readonly>
      <v-expansion-panel-header
        style="cursor: context-menu"
        class="py-0 pr-2"
        :class="filterRound"
        hide-actions
      >
        <v-row no-gutters>
          <!-- <v-col cols="1" class="d-flex justify-start align-center mr-2">
            {{ item.system_name }}
          </v-col> -->
          <!-- <v-divider class="mx-2" vertical></v-divider> -->
          <!-- <v-col cols="3" class="d-flex justify-start align-center">
            <SystemNodeCount :item="item.new_nodes" />
          </v-col> -->

          <v-col cols="6" class="d-flex justify-end align-center">
            <!-- <v-divider class="mx-2" vertical></v-divider> -->
            <!-- <OnTheWay :operationID="operationID" :item="item" /> -->
            <!-- <v-divider class="mx-2" vertical></v-divider> -->
            <!-- <ReadyToGo :operationID="operationID" :item="item" /> -->
          </v-col>
          <v-divider class="mx-2" vertical></v-divider>
          <!-- <v-col cols="1" class="d-flex justify-end align-center">
            <v-btn icon @click="clickIcon()">
              <font-awesome-icon
                icon="fa-solid fa-angle-up"
                :class="iconRotate"
                size="xl"
            /></v-btn>
          </v-col> -->
        </v-row>
      </v-expansion-panel-header>
      <v-expansion-panel-content id="expansion-panel-content-1"
        ><CampaignSystemCardContent :item="item" :operationID="operationID" />
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
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
    return {
      showSystemTable: 0,
    };
  },

  async created() {
    EventBus.$on("showSystemTable", (data) => {
      if (data == 0) {
        this.showSystemTable = null;
      } else {
        this.showSystemTable = 0;
      }
    });
    this.checkRoute();
  },

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    clickIcon() {
      if (this.showSystemTable == 0) {
        this.showSystemTable = null;
      } else {
        this.showSystemTable = 0;
      }
    },

    checkRoute() {
      if (this.$route.params.system) {
        if (this.$route.params.system == this.item.system_name) {
          this.showSystemTable = 0;
        } else {
          this.showSystemTable = null;
        }
      }
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    iconRotate() {
      if (this.showSystemTable == 0) {
        return "toggleUpDown";
      } else {
        return "toggleUpDown rotate";
      }
    },

    filterRound() {
      if (this.showSystemTable) {
        return "rounded-t-xl";
      } else {
        return "rounded-xl";
      }
    },
  },
  beforeDestroy() {},
};
</script>

<style scoped>
.toggleUpDown {
  transition: transform 0.3s ease-in-out !important;
}

.toggleUpDown.rotate {
  transform: rotate(180deg);
}

#expansion-panel-content-1::v-deep .v-expansion-panel-content__wrap {
  padding: 0 !important;
}
</style>
