<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      v-model="showAmmoRequest"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on: menu, attrs }">
        <v-tooltip
          color="#121212"
          bottom
          :open-delay="2000"
          :disabled="$store.state.tooltipToggle"
        >
          <template v-slot:activator="{ on: tooltip }">
            <v-chip
              v-bind="attrs"
              v-on="{ ...tooltip, ...menu }"
              pill
              class="ml-2"
              small
              outlined
              v-show="station.ammo_request == 0"
              color="teal"
            >
              Request Ammo Reload
            </v-chip>
          </template>
          <span> Request GSOL to restock the station </span>
        </v-tooltip>
        <v-chip
          v-show="station.ammo_request == 1"
          pill
          small
          class="ml-2"
          color="teal"
        >
          Request Made
        </v-chip>
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="800px"
        class="d-flex flex-column"
        elevation="24"
      >
        <v-card-title
          >Ammo request for Station {{ station.station_name }}.
        </v-card-title>
        <v-card-text>
          <v-textarea
            height="300px"
            no-resize
            filled
            label="Current ammo/fighter levels"
            v-model="editLoadout"
            autofocus
            placeholder="Just copy and paste from the ammo hangers"
          ></v-textarea>
          <v-divider></v-divider>
          <div>
            <v-text-field
              height="200px"
              v-model="editText"
              auto-grow
              filled
              label="Enter your request here"
            ></v-text-field>
          </div>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn
            class="white--text"
            color="green"
            @click="submitAmmo()"
            :disabled="submitActive"
          >
            Submit
          </v-btn>

          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn></v-card-actions
        >
      </v-card>

      <!-- <showAmmoRequest
                :nodeNoteItem="nodeNoteItem"
                v-if="$can('super')"
                @closeMessage="showAmmoRequest = false"
            >
            </showAmmoRequest> -->
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    station: Object,
  },
  data() {
    return {
      showAmmoRequest: false,
      editText: null,
      editLoadout: null,
    };
  },

  async created() {},

  methods: {
    close() {
      this.editText = null;
      this.showAmmoRequest = false;
      this.editLoadout = null;
    },

    open() {},

    async submitAmmo() {
      var request = {
        station_id: this.station.id,
        current_ammo: this.editLoadout,
        request_text: this.editText,
      };

      await axios({
        method: "post", //you can set what request you want to be
        url: "api/ammorequest",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.editText = null;
      this.editLoadout = null;
      this.showAmmoRequest = false;
    },
  },

  computed: {
    submitActive() {
      if (this.editText != null && this.editLoadout != null) {
        return false;
      } else {
        return true;
      }
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
