<template>
  <div class="pr-16 pl-16" v-resize="onResize">
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="8">
          <v-card rounded="xl">
            <v-data-table
              :headers="headers"
              :items="filteredItems"
              item-key="id"
              :height="height"
              fixed-header
              :loading="loading"
              :sort-by="['name']"
              :search="search"
              :items-per-page="50"
              :footer-props="{
                'items-per-page-options': [10, 20, 30, 50, 100, -1],
              }"
              class="elevation-5"
            >
              <template v-slot:[`item.roles`]="{ item }">
                <div class="d-inline-flex">
                  <v-menu>
                    <template v-slot:activator="{ on, attrs }">
                      <div>
                        <v-btn icon v-bind="attrs" v-on="on" color="success"
                          ><font-awesome-icon icon="fa-solid fa-plus"
                        /></v-btn>
                      </div>
                    </template>

                    <v-list>
                      <v-list-item
                        v-for="(list, index) in filterDropdownList(item.roles)"
                        :key="index"
                        @click="(userAddRoleText = list.id), userAddRole(item)"
                      >
                        <v-list-item-title>{{ list.name }}</v-list-item-title>
                      </v-list-item>
                    </v-list>
                  </v-menu>
                </div>

                <div class="d-inline-flex">
                  <div
                    v-for="(role, index) in filterRoles(item.roles)"
                    :key="index"
                    class="pr-2"
                  >
                    <v-chip
                      pill
                      :class="mittin(item)"
                      :close="pillClose(role.name)"
                      dark
                      @click:close="(userRemoveRoleText = role.id), userRemoveRole(item)"
                    >
                      <font-awesome-icon
                        icon="fa-solid fa-hat-wizard"
                        v-if="role.name == 'Wizard'"
                        size="sm"
                        pull="left"
                      />
                      <span> {{ role.name }}</span>
                    </v-chip>
                  </div>
                </div>
              </template>
              <template slot="no-data"> No Active or Upcoming Campaigns </template>

              <template v-slot:top>
                <v-row no-gutters>
                  <v-col
                    cols="12"
                    align-self="center"
                    class="justify-around d-flex flex-col"
                  >
                    <v-card tile flat class="d-inline-flex align-content-start">
                      <v-card-title>Add/Remove Roles</v-card-title>
                    </v-card>
                    <v-spacer></v-spacer>
                    <v-card tile flat class="align-start">
                      <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search for Users"
                        single-line
                        hide-details
                      ></v-text-field>
                    </v-card>
                  </v-col>
                </v-row>
              </template>
            </v-data-table>
          </v-card>
        </v-col>
        <v-col cols="4" class="pl-4 d-lg-flex flex-column">
          <v-card elevation="10" rounded="xl" class="mb-5">
            <v-card-title
              class="pa-3 primary text-center text-capitalize"
              dark
              rounded="t-xl"
            >
              Roles
            </v-card-title>

            <v-card-text class="pa-4">
              <v-chip-group active-class="primary--text" column v-model="wroles">
                <v-chip
                  v-for="(list, index) in buttonList"
                  :key="index"
                  filter
                  :value="list.id"
                  outlined
                  small
                >
                  {{ list.name }}
                </v-chip>
              </v-chip-group>
            </v-card-text>
            <v-card-actions class="justify-content-center">
              <v-btn @click="clearClass" color=" warning" rounded v-if="showClassButton"
                >Clear</v-btn
              >
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
    <v-overlay z-index="0" :value="logs">
      <AdminLogging v-if="$can('view_admin_logs')" @closeLog="logs = false">
      </AdminLogging>
    </v-overlay>
  </div>
</template>
<script>
import { mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `EveStuff - Admin`;
  },
  data() {
    return {
      //timersAll: [],

      headers: [
        { text: "Name", value: "name" },
        { text: "Roles", value: "roles", width: "80%" },
      ],
      loadingr: false,
      loadingf: false,
      loading: false,
      toggle_exclusive: 0,
      search: "",
      addShown: false,
      userAddRoleText: "",
      userRemoveRoleText: "",
      roleflag: 10,
      logs: false,
      wroles: 0,
      windowSize: {
        x: 0,
        y: 0,
      },
    };
  },

  async created() {
    Echo.private("userupdate").listen("UserUpdate", (e) => {
      this.refresh();
    });
    if (this.$can("view_admin_logs")) {
      await this.$store.dispatch("getLoggingAdmin");
    }
  },

  async mounted() {
    this.onResize();
    await this.$store.dispatch("getUsers");
    await this.$store.dispatch("getRoles");
    this.log();
  },

  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    showClassButton() {
      if (this.wroles.length > 0) {
        return true;
      }
      return false;
    },

    clearClass() {
      this.wroles = [];
    },
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

    filterRoles(roles) {
      return roles.filter((r) => r.name != "Super Admin");
    },

    async refresh() {
      await this.$store.dispatch("getUsers");
      await this.$store.dispatch("getRoles");
    },

    filterDropdownList(item) {
      let roleID = item.map((i) => i.id);
      const filter = this.rolesList.filter((r) => !roleID.includes(r.id));
      let chill = [];
      let fc = [];
      let gsfoeFC = [];
      let gunner = [];
      let recon = [];
      let scout = [];
      let superChilled = [];
      let start = [];
      let topChill = [];
      let megaSheet = [];
      let welp = [];
      let violence = [];
      if (this.$can("edit_all_users")) {
        return filter.filter((f) => f.name != "Nats");
      }
      if (this.$can("edit_welp_users")) {
        welp = filter.filter((f) => f.name == "Welp");
      }

      if (this.$can("edit_violence_users")) {
        violence = filter.filter((f) => f.name == "Violence");
      }

      if (this.$can("edit_chill_users")) {
        chill = filter.filter((f) => f.name == "Chilled");
      }
      if (this.$can("edit_gsfoe_fc")) {
        gsfoeFC = filter.filter((f) => f.name == "GSFOE FC");
      }
      if (this.$can("edit_gunner_users")) {
        gunner = filter.filter((f) => f.name == "Gunner");
      }
      if (this.$can("edit_super_chilled_users")) {
        superChilled = filter.filter((f) => f.name == "Super Chilled");
      }
      if (this.$can("edit_top_chill_users")) {
        topChill = filter.filter((f) => f.name == "Top Chill");
      }

      if (this.$can("edit_mega_sheet_user")) {
        megaSheet = filter.filter((f) => f.name == "Mega Sheet");
      }

      return start.concat(
        chill,
        fc,
        gsfoeFC,
        gunner,
        recon,
        scout,
        superChilled,
        topChill,
        megaSheet,
        welp,
        violence
      );
    },

    pillClose(name) {
      if (this.$can("edit_all_users")) {
        if (name == "Wizard" || name == "Nats") {
          return false;
        } else {
          return true;
        }
      } else if (
        this.$can("edit_gsfoe_fc") &&
        this.$can("edit_recon_users") &&
        this.$can("edit_scout_users") &&
        this.$can("edit_mega_sheet_user")
      ) {
        if (name == "Coord" || name == "Director" || name == "Wizard") {
          return false;
        } else {
          return true;
        }
      } else if (this.$can("edit_recon_users") && this.$can("edit_scout_users")) {
        if (name == "Recon" || name == "Scout") {
          return true;
        } else {
          return false;
        }
      } else if (this.$can("edit_gsfoe_fc")) {
        if (name == "GSFOE FC") {
          return true;
        } else {
          return false;
        }
      } else if (this.$can("edit_scout_users")) {
        if (name == "Scout") {
          return true;
        } else {
          return false;
        }
      } else if (this.$can("edit_chill_users")) {
        if (name == "Chilled") {
          return true;
        } else {
          return false;
        }
      } else if (this.$can("edit_super_chilled_users")) {
        if (name == "Super Chilled") {
          return true;
        } else {
          return false;
        }
      } else if (this.$can("edit_welp_users")) {
        if (name == "Welp") {
          return true;
        } else {
          return false;
        }
      } else if (this.$can("edit_violence_users")) {
        if (name == "Violence") {
          return true;
        } else {
          return false;
        }
      }
    },

    async userAddRole(item) {
      var request = {
        roleId: this.userAddRoleText,
        userId: item.id,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/rolesadd",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.$store.dispatch("getUsers");
      request = null;
      request = {
        roleId: this.userAddRoleText,
        userId: item.id,
        user_id: this.$store.state.user_id,
        type: 15,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/checkroleaddremove",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      if (this.$can("view_admin_logs")) {
        await this.$store.dispatch("getLoggingAdmin");
      }
    },

    mittin(item) {
      if (item.id == 92) {
        return "rainbow-2";
      } else {
        return;
      }
    },

    async userRemoveRole(item) {
      var request = {
        roleId: this.userRemoveRoleText,
        userId: item.id,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/rolesremove",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.$store.dispatch("getUsers");

      request = null;
      request = {
        roleId: this.userAddRoleText,
        userId: item.id,
        user_id: this.$store.state.user_id,
        type: 16,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/checkroleaddremove",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      if (this.$can("view_admin_logs")) {
        await this.$store.dispatch("getLoggingAdmin");
      }
    },
  },

  computed: {
    ...mapState(["users", "rolesList"]),
    filteredItems() {
      var roleid = this.wroles;
      if (this.wroles != 0) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == roleid;
          });
        });
      } else {
        return this.users;
      }
      // return this.users;
    },

    buttonList() {
      var list = this.rolesList;
      var data = {
        id: 0,
        name: "All",
      };
      list.push(data);
      list.sort(function (a, b) {
        return a.id - b.id || a.name.localeCompare(b.name);
      });

      return list;
    },

    height() {
      let num = this.windowSize.y - 315;
      return num;
    },
  },
  beforeDestroy() {
    Echo.leave("userupdate");
  },
};
</script>
<style scoped>
.rainbow-2:hover {
  background-image: linear-gradient(
    to right,
    red,
    orange,
    yellow,
    green,
    blue,
    indigo,
    violet,
    red
  );
  animation: slidebg 2s linear infinite;
}

@keyframes slidebg {
  to {
    background-position: 20vw;
  }
}

.follow {
  margin-top: 40px;
}

.follow a {
  color: black;
  padding: 8px 16px;
  text-decoration: none;
}
</style>
