<template>
  <div v-resize="onResize">
    <!-- <v-row no-gutters justify="center" class="pb-5">
      <v-col cols="10">
        <CampaignTitleBar
          :operationID="operationID"
          :title="newOperationInfo.title"
          :activeCampaigns="activeCampaigns"
          :warmUpCampaigns="warmUpCampaigns"
        ></CampaignTitleBar>
      </v-col>
    </v-row> -->
    <v-row no-gutters justify="center" class="pb-5">
      <v-col cols="10">
        <!-- <CampaignActiveBar
          :windowSize="windowSize"
          :operationID="operationID"
        ></CampaignActiveBar> -->
      </v-col>
    </v-row>
    <!-- <v-row no-gutters justify="space-around">
      <v-col cols="6" class="px-5" v-for="(item, index) in openSystems" :key="index.id">
        <transition
          name="custom-classes"
          enter-active-class="animate__animated animate__heartBeat animate__repeat-2"
          leave-active-class="animate__animated animate__flash animate__faster"
          mode="out-in"
        >
          <CampaignSystemCard
            :key="`${index.id}-card`"
            :item="item"
            :operationID="operationID"
          ></CampaignSystemCard>
        </transition>
      </v-col>
    </v-row> -->
    <!-- <v-overlay :value="showOverlay == 1">
      <v-card :width="cardWidth" rounded="xl">
        <v-card-title> MAKE SURE TO ADD A CHARACTER </v-card-title>
        <v-card-text>
          Remeber to add any chars you have in the campaign by pressing the green
          "CHARACTER" button</v-card-text
        >
        <v-card-actions>
          <v-btn class="white--text" color="teal" @click="closeOverlay()"> Close </v-btn>
          <v-btn class="white--text" color="green" @click="closeOverlayAddChar()">
            Characters
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-overlay> -->
    <!-- <v-overlay :value="showOverlay == 2">
      <v-card :width="cardWidth" rounded="xl">
        <v-card-title class="warning"> WHAT TO DO </v-card-title>
        <v-card-text>
          <p>
            Thanks for participating in this Entosis campaign. In order to participate, we
            need you to add the characters you are using to actively hack to this tool -
            don't worry - we don't require an ESI link.
          </p>
          <p>
            To do this, press the "CHARACTERS" button and pick **Hacker** as the role for
            each alt.
          </p>
          <p>
            Once you have added all of your characters, await instructions from your FC.
            You will be assigned a specific system, which is done in-game via Squads.
          </p>
          <p>
            Check the name of your squad for the assigned system. Once instructed, move to
            this system and prepare to hack. If in doubt, ask for help.
          </p></v-card-text
        >
        <v-card-actions>
          <v-btn class="white--text" color="teal" @click="closeOverlay()"> Close </v-btn>
          <v-btn class="white--text" color="green" @click="closeOverlayAddChar()">
            Characters
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-overlay> -->
  </div>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
import moment from "moment";

function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  data() {
    return {
      windowSize: {
        x: 0,
        y: 0,
      },
    };
  },

  async created() {
    this.operationLink = this.$route.params.id;
    // await this.$store.dispatch("getOperationInfo", this.operationLink);
    // await this.$store.dispatch("getCampaignsList", this.operationID);
    // Echo.private("operations." + this.operationID).listen(
    //   "OperationUpdate",
    //   (e) => {
    //     if (e.flag.flag == 1) {
    //     }

    //     // * Set ReadOnlyFlag
    //     if (e.flag.flag == 2) {
    //       this.$store.dispatch("setReadOnly", e.flag.message);
    //     }

    //     // * refresh Op info
    //     if (e.flag.flag == 3) {
    //       this.$store.dispatch("updateOperationInfo", e.flag.message);
    //     }

    //     // * solo system update
    //     if (e.flag.flag == 4) {
    //       this.$store.dispatch("updateNewCampaigns", e.flag.message);
    //     }

    //     // * 5 is to remove op char from  chartable
    //     if (e.flag.flag == 5) {
    //       this.$store.dispatch("removeCharfromOpList", e.flag.userid);
    //     }

    //     // * 6 update op char table
    //     if (e.flag.flag == 6) {
    //       this.$store.dispatch("updateOpChar", e.flag.message);
    //     }

    //     if (e.flag.flag == 7) {
    //       this.$store.dispatch("updateNewCampaignSystem", e.flag.message);
    //     }

    //     if (e.flag.flag == 8) {
    //       this.$store.dispatch(
    //         "updateNewCampaigns",
    //         e.flag.message.campaign[0]
    //       );
    //     }

    //     if (e.flag.flag == 9) {
    //       this.$store.dispatch("updateCampaignSystemAll", e.flag.message);
    //     }
    //   }
    // );

    // Echo.private(
    //   "operationsown." + this.$store.state.user_id + "-" + this.operationID
    // ).listen("OperationOwnUpdate", (e) => {
    //   if (e.flag.flag == 1) {
    //     this.$store.dispatch("updateOperationOverLay", parseInt(e.flag.type));
    //   }

    //   if (e.flag.flag == 2) {
    //   }
    //   // * 3 add/update char to char table
    //   if (e.flag.flag == 3) {
    //     this.$store.dispatch("updateNewOwnChar", e.flag.message);
    //   }
    //   if (e.flag.flag == 4) {
    //   }
    //   // * 5 is to remove op char from own list
    //   if (e.flag.flag == 5) {
    //     this.$store.dispatch("removeCharfromOwnList", e.flag.userid);
    //   }

    //   if (e.flag.flag == 6) {
    //   }

    //   if (e.flag.flag == 7) {
    //   }

    //   if (e.flag.flag == 8) {
    //   }
    // });

    // if (this.$can("view_campaign_members")) {
    //   Echo.private("operationsadmin." + this.operationID).listen(
    //     "OperationAdminUpdate",
    //     (e) => {
    //       // update watching user list
    //       if (e.flag.flag == 1) {
    //         this.$store.dispatch("updateOperationUserList", e.flag.message);
    //       }

    //       if (e.flag.flag == 2) {
    //       }
    //       if (e.flag.flag == 3) {
    //       }
    //       if (e.flag.flag == 4) {
    //       }
    //       if (e.flag.flag == 5) {
    //       }

    //       if (e.flag.flag == 6) {
    //       }

    //       if (e.flag.flag == 7) {
    //       }

    //       if (e.flag.flag == 8) {
    //       }
    //     }
    //   );
    // }
  },

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {
    this.onResize();
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    closeOverlay() {
      this.$store.dispatch("updateOperationOverLay", 0);
    },

    closeOverlayAddChar() {
      this.$store.dispatch("updateOperationOverLay", 0);
      this.$store.dispatch("setOpenOperationAddChar", true);
    },
  },

  computed: {
    ...mapState([
      "newOperationInfo",
      "newCampaignSystems",
      "newCampaigns",
      "newOperationMessageOverlay",
    ]),
    ...mapGetters([]),

    operationID() {
      return this.newOperationInfo.id;
    },

    showOverlay() {
      return this.newOperationMessageOverlay;
    },

    systems() {
      return this.newCampaignSystems;
    },

    CampaignAllIDs() {
      var check = this.newCampaigns.length;
      if (check > 0) {
        return this.newCampaigns.map((c) => c.id);
      } else {
        return [];
      }
    },

    // * active = able to hack
    activeCampaigns() {
      var check = this.newCampaigns.length;
      if (check > 0) {
        var campaigns = this.newCampaigns.filter((c) => {
          if (c.status_id == 2) {
            return true;
          } else {
            return false;
          }
        });

        return campaigns;
      } else {
        return [];
      }
    },

    activeCampaignsIDs() {
      if (this.activeCampaigns.length > 0) {
        var ids = this.activeCampaigns.map((c) => c.id);
        return ids;
      } else {
        return [];
      }
    },

    warmUpCampaigns() {
      var check = this.newCampaigns.length;
      if (check > 0) {
        var campaigns = this.newCampaigns.filter((c) => {
          if (c.status_id == 5) {
            return true;
          } else {
            return false;
          }
        });
        return campaigns;
      } else {
        return [];
      }
    },

    warmUpIDs() {
      if (this.warmUpCampaigns.length > 0) {
        var ids = this.warmUpCampaigns.map((c) => c.id);
        return ids;
      } else {
        return [];
      }
    },

    // * warmup and active
    openCampaings() {
      if (this.activeCampaigns.length > 0 && this.warmUpCampaigns.length > 0) {
        let open = this.activeCampaigns.concat(this.warmUpCampaigns);
        open = open.filter((item, index) => {
          return open.indexOf(item) == index;
        });
        return open;
      } else if (this.activeCampaigns.length > 0) {
        return this.activeCampaigns;
      } else if (this.warmUpCampaigns.length > 0) {
        return this.warmUpCampaigns;
      } else {
        return [];
      }
    },

    openCampaignIDs() {
      if (this.openCampaings.length > 0) {
        var ids = this.openCampaings.map((c) => c.id);
        return ids;
      } else {
        return [];
      }
    },

    upComingCampaigns() {
      var campaigns = this.newCampaigns.filter((c) => c.status_id == 1);
      return campaigns;
    },

    upComingCampaignIDs() {
      if (this.upComingCampaigns.length > 0) {
        var ids = this.upComingCampaigns.map((c) => c.id);
        return ids;
      } else {
        return [];
      }
    },

    overCampaigns() {
      if (this.newCampaigns.length > 0) {
        var campaigns = this.newCampaigns.filter(
          (c) => c.status_id == 3 || c.status_id == 4
        );
        return campaigns;
      } else {
        return [];
      }
    },

    overCampaignsIDs() {
      if (this.overCampaigns.length > 0) {
        var ids = this.overCampaigns.map((c) => c.id);
        return ids;
      } else {
        return [];
      }
    },

    test() {
      if (this.$route.params.system) {
        return "true";
      } else {
        return "false";
      }
    },

    openSystems() {
      var systems = this.newCampaignSystems.filter((s) => {
        let systems = s.new_campaigns.filter((c) => this.openCampaignIDs.includes(c.id));
        if (systems.length > 0) {
          return true;
        } else {
          return false;
        }
      });
      return systems;
    },

    activeSystem() {
      var systems = this.newCampaignSystems.filter((s) => {
        let systems = s.new_campaigns.filter((c) => this.activeCampaigns.includes(c.id));
        if (systems.length > 0) {
          return true;
        } else {
          return false;
        }
      });
      return systems;
    },

    cardWidth() {
      var x = this.windowSize.x;
      var num = 1268;
      return x - num;
    },
  },
  //   beforeDestroy() {
  //     Echo.leave("operations." + this.operationID);
  //     Echo.leave("operationsown." + this.$store.state.user_id + "-" + this.operationID);
  //     Echo.leave("operationsadmin." + this.operationID);
  //   },
};
</script>
