<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      v-model="showAttackNodeNotes"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-badge
          color="green"
          overlap
          :content="messageAttackCount"
          :value="showAttackNumber"
        >
          <v-btn icon>
            <font-awesome-icon
              color="red"
              v-bind="attrs"
              v-on="on"
              @click="open()"
              icon="fa-solid fa-crosshairs"
            />
          </v-btn>
        </v-badge>
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="700px"
        class="d-flex flex-column"
      >
        <v-card-title
          >Under Attack report for node {{ item.node }}. Campaign
          {{ item.text }}
        </v-card-title>
        <v-card-text>
          <div class="pb-2">
            {{ item.attack_adash_link }}
            <v-chip
              pill
              small
              outlined
              color="teal"
              @click="openAdash(item.attack_adash_link)"
              v-if="showLinkButton()"
            >
              view
            </v-chip>
          </div>
          <v-textarea
            height="300px"
            readonly
            no-resize
            v-model="item.attack_notes"
            outlined
            placeholder="No Notes"
          ></v-textarea>
          <v-divider></v-divider>
          <div>
            <v-text-field
              v-model="editAdashLink"
              auto-grow
              filled
              autofocus
              label="Enter/edit aDash link here"
            ></v-text-field>
            <v-text-field
              v-model="editText"
              auto-grow
              filled
              autofocus
              label="Enter New Notes Here"
            ></v-text-field>
          </div>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn
            class="white--text"
            color="green"
            @click="updatetext()"
            :disabled="submitActive"
          >
            Submit
          </v-btn>

          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn>
          <v-btn
            class="white--text"
            color="green"
            @click="clear()"
            :disabled="clearActive"
          >
            Safe
          </v-btn></v-card-actions
        >
      </v-card>

      <!-- <ShowAttackNodeNotes
                :nodeNoteItem="nodeNoteItem"
                v-if="$can('super')"
                @closeMessage="showAttackNodeNotes = false"
            >
            </ShowAttackNodeNotes> -->
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    item: Object,
  },
  data() {
    return {
      messageAttackCount: 0,
      showAttackNumber: false,
      showAttackkNodeNotes: false,
      editText: null,
      editAdashLink: null,
      showAttackNodeNotes: false,
    };
  },

  async created() {
    Echo.private("nodemessage." + this.item.id).listen(
      "NodeAttackMessageUpdate",
      (e) => {
        if (e.flag.type == 1) {
          this.showAttackNumber = true;
          this.messageAttackCount = this.messageAttackCount + 1;
          this.$store.dispatch("updateCampaignSystem", e.flag.message);
        } else {
          this.showAttackNumber = false;
          this.messageAttackCount = 0;
          this.$store.dispatch("updateCampaignSystem", e.flag.message);
        }
      }
    );
  },

  methods: {
    showMessage(item) {
      this.$emit("openMessage", item);
    },

    close() {
      this.editText = null;
      this.showAttackNodeNotes = false;
    },

    openAdash(url) {
      var win = window.open(url, "_blank");
      win.focus();
    },

    open() {
      (this.showAttackNumber = false), (this.messageAttackCount = 0);
    },

    clear() {
      this.item.attack_notes = null;
      this.item.attack_adash_link = null;
      this.item.under_attack = "0";
      this.editText = null;
      this.editAdashLink = null;
      this.showAttackNumber = 0;
      this.showAttackNodeNotes = false;
      this.$store.dispatch("updateCampaignSystem", this.item);
      let request = {
        attack_notes: null,
        attack_adash_link: null,
      };
      axios({
        method: "put",
        url: "/api/campaignsystemsattackmessage/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    showLinkButton() {
      if (this.item.attack_adash_link != null) {
        return true;
      } else {
        return false;
      }
    },

    updatetext() {
      var request = null;
      var note = null;
      var adashLink = null;
      if (this.editText != null) {
        this.editText = this.editText + "\n";
        if (this.item.attack_notes == null) {
          note =
            moment.utc().format("HH:mm:ss") +
            " - " +
            this.$store.state.user_name +
            ": " +
            this.editText;
        } else {
          note =
            moment.utc().format("HH:mm:ss") +
            " - " +
            this.$store.state.user_name +
            ": " +
            this.editText +
            this.item.attack_notes;
        }

        this.item.attack_notes = note;
        this.item.under_attack = "1";
      }
      if (this.editAdashLink != null) {
        adashLink = this.editAdashLink;
        this.item.attack_adash_link = adashLink;
        this.item.under_attack = "1";
      }

      if (note == null) {
        request = {
          attack_adash_link: this.editAdashLink,
        };
      } else if (adashLink == null) {
        request = {
          attack_notes: note,
        };
      } else {
        request = {
          attack_notes: note,
          attack_adash_link: this.editAdashLink,
        };
      }

      this.$store.dispatch("updateCampaignSystem", this.item);
      axios({
        method: "put",
        url: "/api/campaignsystemsattackmessage/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.editText = null;
    },
  },

  computed: {
    icon() {
      return "fa-solid fa-crosshairs";
    },

    submitActive() {
      if (this.editText != null || this.editAdashLink != null) {
        return false;
      } else {
        return true;
      }
    },

    clearActive() {
      if (
        this.item.attack_notes != null ||
        this.item.attack_adash_link != null
      ) {
        return false;
      } else {
        return true;
      }
    },
  },

  beforeDestroy() {
    Echo.leave("nodemessage." + this.item.id);
  },
};
</script>

<style></style>
