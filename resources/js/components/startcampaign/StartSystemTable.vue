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
              <div>{{ data.constellation_name }}</div>
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

          <template v-slot:[`item.main_name`]="{ item }">
            <div class="d-inline-flex align-items-center">
              <v-menu offset-y v-if="checkShowAdd(item)">
                <template v-slot:activator="{ on, attrs }">
                  <div>
                    <v-chip
                      v-bind="attrs"
                      v-on="on"
                      pill
                      outlined
                      small
                      color="light-green accent-3"
                    >
                      Add
                    </v-chip>
                  </div>
                </template>

                <v-list>
                  <v-list-item
                    v-for="(list, index) in charsFree"
                    :key="index"
                    @click="(charAddNode = list.id), clickaddchar(item)"
                  >
                    <v-list-item-title>{{ list.char_name }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
              <div
                v-else-if="item.user_name != null"
                class="d-inline-flex align-items-center"
              >
                {{ item.user_name }}
                <v-btn
                  v-if="checkShowAddRemove(item)"
                  icon
                  @click="
                    (item.user_name = null),
                      (item.main_name = null),
                      clickremovechar(item)
                  "
                  color="orange darken-3"
                >
                  <font-awesome-icon icon="fa-solid fa-trash-can" size="2xl"
                /></v-btn>
                <!-- <NodeExtraChar :item="item"></NodeExtraChar> -->
              </div>
            </div>
          </template>

          <template v-slot:[`item.count`]="{ item }">
            <StartSystemTableTimer :item="item"> </StartSystemTableTimer>
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
      campaignId: 0,
      campaign_id: "",
      charAddNode: null,

      headers: [
        { text: "System", value: "system_name", width: "15%" },
        {
          text: "Pilot",
          value: "main_name",
          align: "start",
          width: "20%",
        },
        { text: "Timer", value: "count" },

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

  async mounted() {
    this.$store.dispatch("getStartCampaignSystemsRecords");
  },

  async created() {},

  methods: {
    async clickaddchar(item) {
      var addChar = this.chars.find((user) => user.id == this.charAddNode);
      var data = {
        id: item.id,
        user_id: addChar.id,
        site_id: this.$store.state.user_id,
        user_name: addChar.char_name,
        main_name: addChar.main_name,
        user_ship: addChar.ship,
        user_link: addChar.link,
      };

      var request = {
        user_id: addChar.id,
        sys: item.system_id,
      };

      this.$store.dispatch("updateStartCampaignSystem", data);

      await axios({
        method: "put",
        url:
          "/api/startcampaignsystemupdate/" +
          item.id +
          "/" +
          this.data.start_campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async clickremovechar(item) {
      var userId = item.user_id;
      item.user_id = null;
      item.user_ship = null;
      item.user_link = null;
      this.$store.dispatch("updateStartCampaignSystem", item);

      await axios({
        method: "delete",
        url:
          "/api/startcampaignsystemremovechar/" +
          item.id +
          "/" +
          userId +
          "/" +
          this.data.start_campaign_id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    checkShowAdd(item) {
      if (
        item.user_name == null &&
        this.freecharCount != 0 &&
        item.status_id != 4 &&
        item.status_id != 5 &&
        item.status_id != 7 &&
        item.status_id != 8
      ) {
        return true;
      } else {
        return false;
      }
    },
    checkShowAddRemove(item) {
      if (
        item.user_name != null &&
        this.charCount != 0 &&
        item.status_id != 4 &&
        item.status_id != 5 &&
        item.status_id != 7 &&
        item.status_id != 8
      ) {
        return true;
      } else if (this.$can("campaigns_admin_access")) {
        return true;
      } else {
        return false;
      }
    },
  },

  computed: {
    ...mapState(["startcampaignsystems", "user_id"]),

    ...mapGetters([
      "getCampaignUsersByUserIdEntosisFreeCount",
      "getCampaignUsersByUserIdEntosisFree",
      "getCampaignUsersByUserIdEntosis",
    ]),

    filteredItems() {
      return this.startcampaignsystems.filter(
        (s) =>
          s.constellation_id == this.data.constellation_id &&
          s.start_campaign_id == this.data.start_campaign_id
      );
    },

    freecharCount() {
      return this.getCampaignUsersByUserIdEntosisFreeCount(
        this.$store.state.user_id
      );
    },

    charsFree() {
      return this.getCampaignUsersByUserIdEntosisFree(
        this.$store.state.user_id
      );
    },

    chars() {
      return this.getCampaignUsersByUserIdEntosis(this.$store.state.user_id);
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
