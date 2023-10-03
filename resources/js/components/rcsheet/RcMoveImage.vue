<template>
  <div>
    <v-menu bottom left offset-y>
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon color="blue" v-bind="attrs" v-on="on" @click="open()">
          <font-awesome-icon :icon="icon" size="2xl"
        /></v-btn>
      </template>
      <v-card>
        <v-card-title> </v-card-title>
        <v-btn text :href="station.timer_image_link" target="_blank"
          >Open Image</v-btn
        >
        <v-card-text>
          <v-img :src="station.timer_image_link"></v-img>
        </v-card-text>
      </v-card>
    </v-menu>
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
      messageCount: 0,
      showNumber: false,
      showMoveImage: false,
      editText: null,
    };
  },

  async created() {
    Echo.private("rcsheet").listen("RcSheetMessageUpdate", (e) => {
      if (e.flag.id == this.station.id) {
        this.$store.dispatch("updateRcStation", e.flag.message);
        this.showNumber = true;
        if (this.showMoveImage == false) {
          this.messageCount = this.messageCount + 1;
        }
      }
    });
  },

  methods: {
    close() {
      this.editText = null;
      this.showMoveImage = false;
    },

    open() {
      (this.showNumber = false), (this.messageCount = 0);
    },

    updatetext() {
      this.editText = this.editText + "\n";
      if (this.station.notes == null) {
        var note =
          moment.utc().format("HH:mm:ss") +
          " - " +
          this.$store.state.user_name +
          ": " +
          this.editText;
      } else {
        var note =
          moment.utc().format("HH:mm:ss") +
          " - " +
          this.$store.state.user_name +
          ": " +
          this.editText +
          this.station.notes;
      }

      this.station.notes = note;
      let request = {
        notes: note,
      };
      this.$store.dispatch("updateRcStation", this.station);
      axios({
        method: "put",
        url: "/api/sheetmessage/" + this.station.id,
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
      return "fa-regular fa-image";
    },

    submitActive() {
      if (this.editText != null) {
        return false;
      } else {
        return true;
      }
    },
  },

  beforeDestroy() {
    Echo.leave("rcsheet");
  },
};
</script>

<style>
#Imageview.v-dialog {
  position: absolute;
  bottom: 0;
  right: 0;
}
</style>
