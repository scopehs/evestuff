<template>
  <v-col :cols="size" align-self="stretch">
    <v-card tile height="100%" class="d-flex flex-column">
      <v-card-text>
        <template>
          <v-card flat max-width elevation="24" color="grey darken-4">
            <v-card-title
              max-width
              class="d-flex justify-space-between align-center"
              style="width: 100%"
            >
              <div>{{ data.title }}</div>

              <div class="d-inline-flex">
                <v-divider class="mx-4 my-0" vertical></v-divider>
                <TaskInfo :task="data" class="pr-3"> </TaskInfo>
                <v-divider class="mx-4 my-0" vertical></v-divider>
                <DeleteReconTask
                  v-if="$can('edit_recon_task')"
                  :item="data"
                ></DeleteReconTask>
              </div>
            </v-card-title>
          </v-card>
        </template>
        <v-data-table
          :headers="headers"
          :items="filteredItems"
          item-key="node"
          hide-default-footer
          disable-pagination
          class="elevation-12"
        >
          <template v-slot:[`item.updated_at`]="{ item }"
            ><span v-if="item.user_id != null">{{ data.last_edit }}</span>
            <span v-else> N/A </span>
          </template>

          <template v-slot:[`item.count`]="{ item }"
            ><LastedCheckedTimerRecon :item="item"> </LastedCheckedTimerRecon>
          </template>

          <template slot="no-data">
            No nodes have shown up here..... yet!!!!
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    data: Object,
    size: Number,
  },
  data() {
    return {
      headers: [
        { text: "Region", value: "region_name", width: "10%" },
        { text: "Constellation", value: "constellation_name" },
        { text: "System", value: "system_name" },
        { text: "Last Checked (date)", value: "last_edit" },
        { text: "Checked by", value: "user_name" },
        {
          text: "Last Checked (time)",
          value: "count",
          width: "20%",
          align: "center",
        },

        {
          text: "",
          value: "actions",
          sortable: false,
          align: "end",
          width: "5%",
        },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
    };
  },

  async created() {
    Echo.private("recontask." + this.data.id).listen(
      "ReconTimerUpdate",
      (e) => {
        this.$store.dispatch("updateReconTaskSystems", e.flag.message);
      }
    );
  },

  async mounted() {},

  methods: {
    async update() {
      await this.$store.dispatch("getReconTaskSystemsRecords");
    },
  },

  computed: {
    ...mapState(["recontasksystems", "user_id"]),

    ...mapGetters([]),

    filteredItems() {
      // var timers = this.$store.state.timers;

      return this.recontasksystems.filter(
        (s) => s.recon_task_id == this.data.id
      );
    },
  },
  beforeDestroy() {
    Echo.leave("recontask." + this.data.id);
  },
};
</script>

<style></style>
