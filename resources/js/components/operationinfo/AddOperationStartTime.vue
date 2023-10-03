<template>
  <v-menu
    :close-on-content-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
    @click:outside="close()"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        rounded
        x-small
        v-bind="attrs"
        v-on="on"
        @click="open()"
        color="success"
        >Add Time</v-btn
      >
    </template>
    <v-card tile class="rounded-xl">
      <v-card-text>
        <v-row>
          <v-col cols="auto">
            <strong>Enter Start Time Until Timer</strong>
            <v-text-field
              v-model="startTime"
              label="Operation Starts YYYY.MM.DD hh:mm:ss"
              v-mask="'####.##.## ##:##:##'"
              placeholder="YYYY.MM.DD HH:mm:ss"
              @keyup.enter="(addShown = false), addStartTime()"
              @keyup.esc="(addShown = false), (startTime = null)"
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
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions
        ><v-row no-gutters>
          <v-col cols="auto"
            ><v-btn
              :disabled="hideSubmit"
              rounded
              color=" primary"
              @click="addStartTime()"
              >Add</v-btn
            ></v-col
          ><v-spacer /><v-col cols="auto"
            ><v-btn rounded color=" warning" @click="close()"
              >Close</v-btn
            ></v-col
          >
        </v-row>
      </v-card-actions>
    </v-card>
  </v-menu>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import moment from "moment";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {},
  data() {
    return {
      addShown: false,
      startTime: "",
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async open() {},
    close() {
      this.addShown = false;
      this.startTime = "";
    },
    async addStartTime() {
      var request = {
        start: this.startTime,
        opID: this.$store.state.operationInfoPage.id,
      };
      await axios({
        method: "post", //you can set what request you want to be
        url:
          "/api/operationinfostart/" + this.$store.state.operationInfoPage.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then((response) => {
        this.close();
      });
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    vaildDate() {
      if (this.count == 19) {
        var full = this.startTime.replace(".", "-");
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
      if (this.vaildDate == false) {
        var b = 1;
      } else {
        var b = 0;
      }

      var sum = b;
      if (sum == 0) {
        return false;
      } else {
        return true;
      }
    },

    count() {
      return this.startTime.length;
    },

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },
  },
  beforeDestroy() {},
};
</script>
