<template>
  <v-menu
    :close-on-content-click="false"
    :value="editShown"
    transition="fab-transition"
    origin="100% -30%"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        icon
        @click="charEditForm(char), (editShown = true)"
        color="orange darken-3"
        v-bind="attrs"
        v-on="on"
      >
        <font-awesome-icon icon="fa-solid fa-pen-to-square"
      /></v-btn>
    </template>
    <!---edit/delete form------>
    <v-row no-gutters>
      <div>
        <v-card class="pa-2" tile width="100%">
          <v-form @submit.prevent="editCharForm(char)">
            <v-text-field
              v-model="editCharName"
              label="Name"
              required
              :placehoder="this.char.char_name"
            ></v-text-field>
            <v-select
              v-model="editRole"
              @change="roleEditForm($event)"
              :items="dropdown_roles"
              label="Role"
              required
              :placeholder="editTextRole"
            ></v-select>
            <v-text-field
              v-model="editShip"
              v-if="this.editrole == 1"
              label="Ship"
              required
              :placeholder="editTextShip"
            ></v-text-field>
            <v-radio-group
              v-model="editLink"
              v-if="this.editrole == 1"
              row
              label="Entosis Link"
              required
              :placeholder="editTextLink"
            >
              <v-radio label="Tech 1" value="1"></v-radio>
              <v-radio label="Tech 2" value="2"></v-radio>
            </v-radio-group>

            <v-btn class="mr-2" type="submit">submit</v-btn>

            <v-btn class="mr-2" @click="editFormClose()">Close</v-btn>
            <!-- <v-btn @click="clear">clear</v-btn> -->
          </v-form>
          <!---edit/delete form------>
        </v-card>
      </div>
    </v-row>
  </v-menu>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    char: Object,
    campaign_id: Number,
  },
  data() {
    return {
      dropdown_roles: [
        { text: "Hacker", value: 1 },
        { text: "Support", value: 5 },
        { text: "Scout", value: 2 },
        { text: "FC", value: 3 },
        { text: "Command", value: 4 },
      ],

      editShown: false,

      editCharName: null,
      editNameRules: [(v) => !!v || "Name is required"],
      editRole: 1,
      editTextRole: null,
      editRoleRules: [(v) => !!v || "You need to pick a role"],
      editShip: null,
      editTextShip: null,
      editShipRules: [(v) => !!v || "Ship is required"],
      editLink: null,
      editTextLink: null,
      editLinkRules: [(v) => !!v || "T1 or T2?"],
      editUserForm: 1,
      editrole_name: null,
      oldChar: [],
      editrole: 1,
    };
  },

  async mounted() {},

  methods: {
    setEditCharname() {
      this.editCharName = this.char.char_name;
    },

    charEditForm(item) {
      this.oldChar = this.char;
      this.editCharName = this.oldChar.char_name;
      this.editRole = this.oldChar.role_id;
      this.editTextShip = this.oldChar.ship;
      this.editTextLink = this.oldChar.link;
      this.editShip = this.oldChar.ship;
      if (this.oldChar.link == 1) {
        this.editLink = "1";
      } else if (this.oldChar.link == 2) {
        this.editLink = "2";
      } else {
        this.editLink = "0";
      }
      if (this.oldChar.role_id == 1) {
        this.editrole = "1";
      } else {
        this.editrole = 0;
      }
    },

    editFormClose() {
      this.editShown = false;
      this.editCharName = null;
      this.editRole = null;
      this.editTextRole = null;
      this.editShip = null;
      this.editTextShip = null;
      this.editLink = null;
      this.editTextLink = null;
      this.editrole = null;
    },

    roleEditForm(a) {
      this.editrole = a;
    },

    async editCharForm() {
      this.editShown = false;

      var link = this.oldChar.link;
      var char_name = this.oldChar.char_name;
      var ship = this.oldChar.ship;
      var role = this.oldChar.role_id;
      var role_name = this.oldChar.role_name;

      if (this.oldChar.role_id != this.editRole) {
        var role = this.editRole;
        var role_name = this.dropdown_roles.find(
          (droprole) => droprole.value == role
        ).text;
      }

      if (this.oldChar.ship != this.editShip) {
        var ship = this.editShip;
      }
      if (this.oldChar.link != this.editLink) {
        var link = this.editLink;
      }

      if (this.oldChar.char_name != this.editCharName) {
        var char_name = this.editCharName;
      }
      if (role != 1) {
        ship = null;
        link = null;
      }
      var request = {
        char_name: char_name,
        link: link,
        ship: ship,
        campaign_role_id: role,
      };

      var item = {
        id: this.oldChar.id,
        char_name: char_name,
        link: link,
        ship: ship,
        role_id: role,
        role_name: role_name,
      };
      this.$store.dispatch("updateUsersChars", item);
      if (this.char.campaign_id == this.campaign_id) {
        console;
        this.$store.dispatch("updateCampaignUsers", item);
      }

      await axios({
        method: "PUT", //you can set what request you want to be
        url: "/api/campaignusers/" + this.oldChar.id + "/" + this.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      this.editCharName = null;
      this.editRole = null;
      this.editTextRole = null;
      this.editShip = null;
      this.editTextShip = null;
      this.editLink = null;
      this.editTextLink = null;
      // this.$store.dispatch("getCampaignSystemsRecords");
    },
  },

  computed: {},
};
</script>

<style></style>
