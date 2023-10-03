<template>
  <v-menu
    :close-on-content-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
    :close-on-click="false"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        fab
        x-small
        v-bind="attrs"
        v-on="on"
        @click="open()"
        :color="fabColor"
        ><font-awesome-icon :icon="buttonIcon" size="2xl"
      /></v-btn>
    </template>
    <v-card tile class="rounded-xl">
      <v-card-title>{{ cardTitle }}</v-card-title>
      <v-card-text>
        <v-row no-gutters>
          <v-textarea
            outlined
            dense
            rounded
            label="link"
            v-model="text"
          ></v-textarea>
        </v-row>
        <v-row no-gutters>
          <v-autocomplete
            v-if="showMain"
            outlined
            :items="dropDownMain"
            v-model="mainName"
            item-text="name"
            item-value="id"
            hide-details
            rounded
            dense
          ></v-autocomplete>
        </v-row>
      </v-card-text>
      <v-card-actions
        ><v-row no-gutters>
          <v-col cols="auto"
            ><v-btn rounded color=" primary" @click="addDankLink()">{{
              doneButton
            }}</v-btn></v-col
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
      name: null,
      infoShown: false,
      showMain: 0,
      mainName: null,
      text: null,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {
    this.setText();
  },
  methods: {
    close() {
      this.addShown = false;
    },

    open() {
      this.setText();
      this.addShown = true;
    },

    setText() {
      if (this.checkDankLink) {
        this.text = this.opInfo.dankop.link;
      } else {
        this.text = null;
      }
    },

    async addDankLink() {
      var request = {
        link: this.text,
        opID: this.$store.state.operationInfoPage.id,
      };

      await axios({
        method: "POST",
        url: "/api/operationdanklink",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then(this.close());
    },
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

    checkDankLink() {
      if (this.opInfo.dankop) {
        if (this.opInfo.dankop.link) {
          return true;
        }
      }
      return false;
    },

    cardTitle() {
      if (this.checkDankLink) {
        return "Edit Link";
      } else {
        return "Add Link";
      }
    },

    fabColor() {
      if (this.checkDankLink) {
        return "green";
      } else {
        return "blue";
      }
    },

    doneButton() {
      if (this.checkDankLink) {
        return "Update";
      } else {
        return "Add";
      }
    },

    buttonIcon() {
      if (this.checkDankLink) {
        return "fa-solid fa-pen-to-square";
      } else {
        return "fa-solid fa-plus";
      }
    },
  },
  beforeDestroy() {},
};
</script>
