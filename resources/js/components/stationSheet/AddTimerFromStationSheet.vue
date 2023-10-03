<template>
  <div>
    <v-dialog
      max-width="700px"
      persistent
      z-index="0"
      v-model="showAddTimer"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon color="blue" v-bind="attrs" v-on="on" @click="open()">
          <font-awesome-icon icon="fa-solid fa-clock" size="2xl" />
        </v-btn>
      </template>

      <v-card
        max-width="700px"
        min-height="200px"
        max-height="1000px"
        class="d-flex flex-column"
        elevation="10"
        rounded="xl"
      >
        <v-card-title class="justify-center primary">
          Enter Details for {{ item.name }}</v-card-title
        >

        <v-card-text class="pt-2">
          <v-fade-transition>
            <div>
              <div>
                <v-text-field
                  v-model="item.name"
                  readonly
                  outlined
                  label="Station Name"
                ></v-text-field>
              </div>
              <div class="d-inline-flex justify-content-around">
                <v-text-field
                  v-model="item.system.system_name"
                  readonly
                  outlined
                  label="System Name"
                ></v-text-field>
                <v-text-field
                  class="ml-2"
                  outlined
                  v-model="item.corp.ticker"
                  label="Corp Ticker"
                ></v-text-field>
              </div>
              <div>
                <h5><strong>Timer Type</strong></h5>
                <v-radio-group v-model="refType" row :rules="[rules.required]">
                  <v-radio label="Armor" value="5"></v-radio>
                  <v-radio label="Hull" value="13"></v-radio>
                </v-radio-group>
              </div>
              <div>
                <h5><strong>Image Link</strong></h5>
                <v-img src="../image/info.png"> </v-img>
                <v-text-field
                  v-model="imageLink"
                  autofocus
                  label="Selected Items Screen Shot"
                ></v-text-field>
              </div>
              <div>
                <h5>
                  <strong>Enter Reinforced Until Timer</strong>
                </h5>
                <v-text-field
                  v-model="refTime"
                  label="Reinforced unit YYYY.MM.DD hh:mm:ss"
                  v-mask="'####.##.## ##:##:##'"
                  placeholder="YYYY.MM.DD HH:mm:ss"
                  @keyup.enter="(timerShown = false), addHacktime()"
                  @keyup.esc="(timerShown = false), (hackTime = null)"
                ></v-text-field>

                <v-alert
                  :value="showWarning"
                  dark
                  type="warning"
                  border="top"
                  icon="mdi-home"
                  transition="scale-transition"
                >
                  <span class="text-center">
                    TIMER IS NOT VAILD OR INCORRECT FORMAT
                  </span>
                </v-alert>
              </div>
            </div>
          </v-fade-transition>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn>
          <v-btn
            class="white--text"
            color="green"
            @click="submit()"
            :disabled="hideSubmit"
          >
            Submit
          </v-btn></v-card-actions
        >
      </v-card>
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

  async created() {},
  data() {
    return {
      showAddTimer: false,
      imageLink: null,
      refType: null,
      refTime: {
        d: "",
        hh: "",
        mm: "",
        ss: "",
      },

      rules: {
        required: (value) => !!value || "Required",
      },
    };
  },

  methods: {
    close() {
      this.refType = null;
      this.refTime = "";
      this.showAddTimer = false;
    },

    async submit() {
      var outTime = null;
      var editText = "Added the timer";

      editText = editText + "\n";
      if (this.item.notes == null) {
        var note =
          moment.utc().format("HH:mm:ss") +
          " - " +
          this.$store.state.user_name +
          ": " +
          editText;
      } else {
        var note =
          moment.utc().format("HH:mm:ss") +
          " - " +
          this.$store.state.user_name +
          ": " +
          editText +
          this.item.notes;
      }

      var full = this.refTime.replace(".", "-");
      full = full.replace(".", "-");
      var outTime = moment(full).format("YYYY-MM-DD HH:mm:ss");

      if (this.$can("add_timer")) {
        var request = {
          station_status_id: this.refType,
          out_time: outTime,
          timer_image_link: this.imageLink,
          rc_fc_id: 0,
          rc_recon_id: 0,
          rc_gsol_id: 0,
          show_on_rc_move: 0,
          show_on_rc: 1,
          show_on_coord: 0,
          status_update: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
          notes: note,
        };
      } else {
        var request = {
          station_status_id: this.refType,
          out_time: outTime,
          timer_image_link: this.imageLink,
          rc_fc_id: 0,
          rc_recon_id: 0,
          rc_gsol_id: 0,
          show_on_rc_move: 1,
          show_on_rc: 0,
          show_on_coord: 0,
          status_update: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
          notes: note,
        };
      }

      await axios({
        method: "put", //you can set whfffat request you want to be
        url: "api/updatetimerinfo/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then(this.close());
    },

    async open() {},
  },

  computed: {
    ...mapGetters([]),
    ...mapState([]),

    vaildDate() {
      if (this.count == 19) {
        var full = this.refTime.replace(".", "-");
        full = full.replace(".", "-");
        var vaild = moment(full).format("YYYY-MM-DD HH:mm:ss", true);
        if (vaild == "Invalid date") {
          return false;
        } else {
          if (vaild > moment.utc().format("YYYY-MM-DD HH:mm:ss")) {
            return true;
          } else {
            return false;
          }
        }
      } else {
        return false;
      }
    },

    showWarning() {
      if (this.count == 19 && this.vaildDate == false) {
        return true;
      } else {
        return false;
      }
    },
    hideSubmit() {
      if (this.imageLink == null || this.imageLink == "") {
        var a = 1;
      } else {
        var a = 0;
      }

      if (this.vaildDate == false) {
        var b = 1;
      } else {
        var b = 0;
      }

      if (this.refType == null) {
        var c = 1;
      } else {
        var c = 0;
      }

      var sum = a + b + c;
      if (sum == 0) {
        return false;
      } else {
        return true;
      }
    },

    count() {
      return this.refTime.length;
    },
  },

  beforeDestroy() {},
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
