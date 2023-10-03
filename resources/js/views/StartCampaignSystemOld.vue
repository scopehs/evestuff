<template>
  <div>
    <v-row no-gutters class="pb-2" justify="space-around">
      <v-col md="10">
        <v-card class="pr-2 pb-2 pl-2" tile width="100%">
          <v-card-title align="center" class="justify-center align-center">
            <p class="pt-5">
              Campaign
              {{ this.startcampaign[0]["name"] }}
            </p>
          </v-card-title>
        </v-card>
      </v-col>
    </v-row>
    <v-row no-gutters justify="space-around">
      <v-col md="10">
        <v-card
          class="pa-2 d-flex justify-space-between full-width align-center"
          tile
        >
          <div class="d-md-inline-flex">
            <v-btn
              v-if="$can('super')"
              v-show="showTable == false"
              @click="showTable = true"
              class="mr-4"
              color="blue darken-2"
              >Show Char table</v-btn
            >
            <v-btn
              v-if="$can('super')"
              v-show="showTable == true"
              @click="showTable = false"
              class="mr-4"
              color="orange darken-2"
              >Hide Char table</v-btn
            >
            <UsersChars :campaign_id="this.campaign_id"> </UsersChars>
            <WatchUserTable
              :campaign_id="startcampaign.id"
              v-if="$can('super')"
            >
            </WatchUserTable>

            <v-btn v-if="$can('super')" class="mr-4" color="blue">
              Campaign Logs
            </v-btn>
            <v-btn v-if="$can('super')"> test </v-btn>
            <v-btn v-if="$can('super')" fab dark class="mr-4" small>
              <font-awesome-icon icon="fa-solid fa-bullhorn" />
            </v-btn>
            <v-btn v-if="$can('super')" dark color="red" class="mr-4">
              Campaign Over
            </v-btn>
          </div>
          <v-spacer></v-spacer>
        </v-card>
      </v-col>
    </v-row>

    <v-row
      no-gutters
      justify="space-around"
      v-if="$can('super')"
      v-show="showTable == true"
    >
      table here
    </v-row>

    <v-row no-gutters justify="center">
      <StartSystemTable
        class="px-5 pt-5"
        v-for="(startcampaignjoin, index) in startcampaignjoins"
        :data="startcampaignjoin"
        :size="size"
        :index="index"
        :key="startcampaignjoin.id"
        @openSolaLog="openSolaLog($event)"
      >
      </StartSystemTable>
    </v-row>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {},
  title() {
    return `EveStuff - ${this.startcampaign[0]["name"]}`;
  },
  async created() {
    this.campaignId = this.$route.params.id;
    this.campaign_id = parseInt(this.$route.params.id);
    Echo.private("startcampaignsystem." + this.$route.params.id).listen(
      "StartCampaignSystemUpdate",
      (e) => {
        if (e.flag.message != null) {
          this.$store.dispatch("updateStartCampaignSystem", e.flag.message);
        }
      }
    );

    await this.$store.dispatch("getStartCampaigns");
    await this.$store.dispatch("getStartCampaignJoinData");
    await this.$store.dispatch("getUsersChars", this.$store.state.user_id);
    await this.$store.dispatch("getCampaignUsersRecords", this.campaign_id);
  },
  data() {
    return {
      campaignId: 0,
      campaign_id: "",
      showTable: false,
    };
  },

  async mounted() {
    this.log();
  },

  methods: {
    log() {
      var request = {
        url: this.$route.path,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/url",
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
    ...mapGetters(["getStartCampaignsById", "getStartCampaignById"]),

    startcampaign() {
      return this.getStartCampaignsById(this.$route.params.id);
    },

    startcampaignjoins() {
      return this.getStartCampaignById(this.$route.params.id);
    },

    size() {
      let count = this.getStartCampaignById(this.campaign_id).length;
      if (count > 1) {
        return 6;
      } else {
        return 10;
      }
    },
  },

  beforeDestroy() {
    Echo.leave("startcampaignsystem." + this.$route.params.id);
  },
};
</script>

<style></style>
