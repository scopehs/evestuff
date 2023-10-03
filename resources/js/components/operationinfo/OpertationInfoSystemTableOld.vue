<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="12">
          <v-data-table
            :headers="headers"
            :items="opInfo.systems"
            disable-sort
            dense
            hide-default-footer
            disable-pagination
            class="elevation-24 rounded-xl full-width"
          >
            <!-- <template slot="no-data"> No Systems Picked for this Operation </template> -->
            <template v-slot:[`item.actions`]="{ item }">
              <!-- <OperationInfoSystemAddNotes :loaded="loaded" :item="item" /> -->
              <OperationInfoSystemJammerSetting :loaded="loaded" :item="item" />
            </template>

            <!-- <template v-slot:[`item.region.region_name`]="{ item }">
              <div class="text-no-wrap">
                {{ item.region.region_name }}
              </div>
            </template> -->

            <!-- <template v-slot:[`item.constellation.constellation_name`]="{ item }">
              <div class="text-no-wrap">
                {{ item.constellation.constellation_name }}
              </div>
            </template> -->

            <!-- <template v-slot:[`item.system_name`]="{ item }">
              <div class="text-no-wrap">
                {{ item.system_name }}
              </div>
            </template> -->

            <template v-slot:[`item.TODORecon`]="{ item }">
              <v-row no-gutters justify="start" align="center">
                <!-- <v-col :cols="colCount(item)">
                  <OperationInfoSystemReconChips
                    :windowSize="windowSize"
                    :loaded="loaded"
                    :item="item"
                /></v-col> -->
                <!-- <v-col cols="1">
                  <OperationInfoSystemAddRecon :loaded="loaded" :item="item"
                /></v-col> -->
                <v-col cols="1" v-if="showReconCount(item)">
                  {{ reconCount(item) }} / {{ item.pivot.cynos_needed }}
                </v-col>
              </v-row>
            </template>

            <!-- <template v-slot:[`item.pivot.jammed_status`]="{ item }">
              {{ jamerText(item.pivot.jammed_status) }}
            </template> -->
          </v-data-table>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    loaded: Boolean,
    windowSize: Object,
  },
  data() {
    return {
      singleExpand: false,
      headers: [
        // {
        //   text: "Region",
        //   value: "region.region_name",
        //   sortable: false,
        //   align: "start",
        //   width: "5%",
        // },
        // {
        //   text: "Constellation",
        //   value: "constellation.constellation_name",
        //   sortable: true,
        //   align: "start",
        //   width: "5%",
        // },
        // {
        //   text: "System",
        //   value: "system_name",
        //   sortable: true,
        //   align: "start",
        //   width: "5%",
        // },
        // {
        //   text: "Recon",
        //   value: "TODORecon",
        //   sortable: true,
        //   cellClass: "pl-0 pr-0",
        //   align: "start",
        // },
        // {
        //   text: "Jammed",
        //   value: "pivot.jammed_status",
        //   sortable: true,
        //   align: "start",
        //   width: "5%",
        // },
        // {
        //   text: "Notes",
        //   value: "pivot.notes",
        //   sortable: true,
        //   width: "15%",
        // },
        // {
        //   text: "",
        //   value: "actions",
        //   sortable: true,
        //   align: "end",
        //   width: "8%",
        // },
      ],
    };
  },

  methods: {
    addNotes(item) {},

    // jamerText(num) {
    //   switch (num) {
    //     case 1:
    //       return "Unknown";
    //     case 2:
    //       return "No";
    //     case 3:
    //       return "Yes";
    //   }
    // },

    // colCount(item) {
    //   if (item.pivot.cynos_needed > 0) {
    //     return "10";
    //   } else {
    //     return "11";
    //   }
    // },

    // showReconCount(item) {
    //   if (item.pivot.cynos_needed > 0) {
    //     return true;
    //   } else {
    //     return false;
    //   }
    // },

    // reconCount(item) {
    //   return item.recons.length;
    // },
  },

  computed: {
    ...mapGetters([]),
    ...mapState["operationInfoPage"],

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },
  },
};
</script>
