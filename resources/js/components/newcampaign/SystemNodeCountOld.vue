<template>
  <v-row no-gutters>
    <v-col class="d-flex justify-start align-center">
      <span> Nodes -</span>
      <Vep
        :progress="blueProgress"
        :size="50"
        :legend-value="blueNode"
        fontSize="0.80rem"
        color="#00ff00"
        :thickness="4"
        :emptyThickness="1"
        emptyColor="#a4fca4"
      >
        <template v-slot:legend-value>
          <span slot="legend-value"> /{{ totalNode }}</span>
        </template>
      </Vep>
      <Vep
        :progress="redProgress"
        :size="50"
        :legend-value="redNode"
        fontSize="0.80rem"
        color="#ff0000"
        :thickness="4"
        :emptyThickness="1"
        emptyColor="#f08d8d"
      >
        <template v-slot:legend-value>
          <span slot="legend-value"> /{{ totalNode }}</span>
        </template>
      </Vep>
    </v-col>
  </v-row>
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
    item: Array,
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    totalNode() {
      var count = null;
      if (this.item) {
        count = this.item.length;
      } else {
        count = 0;
      }

      return count;
    },

    blueNode() {
      var count = null;
      if (this.item) {
        count = this.item.filter(
          (c) =>
            c.node_status.id == 2 ||
            c.node_status.id == 3 ||
            c.node_status.id == 4 ||
            c.node_status.id == 8
        ).length;
      } else {
        count = 0;
      }
      return count;
    },

    redNode() {
      var count = null;
      if (this.item) {
        count = this.item.filter(
          (n) => n.node_status.id == 5 || n.node_status.id == 7
        ).length;
      } else {
        count = 0;
      }
      return count;
    },

    blueProgress() {
      if (this.totalNode) {
        var num = (this.blueNode / this.totalNode) * 100;
        return num;
      } else {
        return 0;
      }
    },

    redProgress() {
      if (this.totalNode) {
        var num = (this.redNode / this.totalNode) * 100;
        return num;
      } else {
        return 0;
      }
    },
  },
  beforeDestroy() {},
};
</script>
