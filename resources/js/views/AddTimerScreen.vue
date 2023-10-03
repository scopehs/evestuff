<template>
  <div class="pr-16 pl-16">
    <v-row no-gutters justify="center">
      <v-col class="d-inline-flex" cols="9">
        <v-card
          tile
          flat
          color="#121212"
          class="d-inline-flex align-content-start"
        >
          <v-card-title>Add/Remove Roles</v-card-title>
        </v-card>
        <v-card width="500" tile flat color="#121212" class="align-start">
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search for Users"
            single-line
            hide-details
          ></v-text-field>
        </v-card>
        <v-card tile flat color="#121212" class="pt-2 pl-2">
          <v-btn
            v-if="$can('view_admin_logs')"
            @click="logs = true"
            class="mr-4"
            color="blue"
          >
            Role Logs
          </v-btn>
        </v-card>
      </v-col>
    </v-row>
    <v-row no-gutters justify="center">
      <v-col class="d-inline-flex" cols="9">
        <v-spacer></v-spacer>
        <v-card tile flat color="#121212" class="align-end">
          <v-btn-toggle right v-model="toggle_exclusive" mandatory :value="1">
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 10"
            >
              All
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 4"
            >
              Coord
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 13"
            >
              Director
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 12"
            >
              FC
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 9"
            >
              GSFFOE FC
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 16"
            >
              GSFOE Leader
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 21"
            >
              GSOL Leader
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 17"
            >
              GSOL
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 6"
            >
              Ops
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 5"
            >
              Recon
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 14"
            >
              Recon Leader
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 7"
            >
              Scout
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 18"
            >
              Gunner
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 20"
            >
              Chill
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 19"
            >
              Super Chill
            </v-btn>
            <v-btn
              :loading="loadingf"
              :disabled="loadingf"
              @click="roleflag = 23"
            >
              Mega Sheet
            </v-btn>
          </v-btn-toggle>
        </v-card>
      </v-col>
    </v-row>
    <v-row no-gutters justify="center">
      <v-col class="d-inline-flex justify-content-center w-auto" cols="9">
        <v-card width="100%">
          <v-data-table
            :headers="headers"
            :items="filteredItems"
            item-key="id"
            :loading="loading"
            :sort-by="['name']"
            :items-per-page="20"
            :search="search"
            :footer-props="{
              'items-per-page-options': [10, 20, 50, 100, -1],
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
                    @click:close="
                      (userRemoveRoleText = role.id), userRemoveRole(item)
                    "
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
            <template slot="no-data">
              No Active or Upcoming Campaigns
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
    <v-overlay z-index="0" :value="logs">
      <AdminLogging v-if="$can('view_admin_logs')" @closeLog="logs = false">
      </AdminLogging>
    </v-overlay>
  </div>
</template>
<script>
import Axios from "axios";
import moment, { now, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `EVE — Admin`;
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
    await this.$store.dispatch("getUsers");
    await this.$store.dispatch("getRoles");
  },

  methods: {
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
      if (this.$can("edit_all_users")) {
        return filter;
      }
      if (this.$can("edit_chill_users")) {
        chill = filter.filter((f) => f.name == "Chilled");
      }
      if (this.$can("edit_fc_users")) {
        fc = filter.filter((f) => f.name == "FC");
      }
      if (this.$can("edit_gsfoe_fc")) {
        gsfoeFC = filter.filter((f) => f.name == "GSFOE FC");
      }
      if (this.$can("edit_gunner_users")) {
        gunner = filter.filter((f) => f.name == "Gunner");
      }
      if (this.$can("edit_recon_users")) {
        recon = filter.filter((f) => f.name == "Recon");
      }
      if (this.$can("edit_scout_users")) {
        scout = filter.filter((f) => f.name == "Scout");
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
        megaSheet
      );
    },

    pillClose(name) {
      if (this.$can("edit_all_users")) {
        if (name == "Wizard") {
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
      } else if (
        this.$can("edit_recon_users") &&
        this.$can("edit_scout_users")
      ) {
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
      if (this.roleflag == 4) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 4;
          });
        });
      }
      if (this.roleflag == 5) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 5;
          });
        });
      }
      if (this.roleflag == 6) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 6;
          });
        });
      }
      if (this.roleflag == 7) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 7;
          });
        });
      }
      if (this.roleflag == 8) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 8;
          });
        });
      }
      if (this.roleflag == 9) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 9;
          });
        });
      }
      if (this.roleflag == 11) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 11;
          });
        });
      }
      if (this.roleflag == 12) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 12;
          });
        });
      }
      if (this.roleflag == 13) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 13;
          });
        });
      }
      if (this.roleflag == 14) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 14;
          });
        });
      }
      if (this.roleflag == 16) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 16;
          });
        });
      }

      if (this.roleflag == 17) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 17;
          });
        });
      }

      if (this.roleflag == 18) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 18;
          });
        });
      }

      if (this.roleflag == 19) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 19;
          });
        });
      }

      if (this.roleflag == 20) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 20;
          });
        });
      }

      if (this.roleflag == 21) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 21;
          });
        });
      }

      if (this.roleflag == 23) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == 23;
          });
        });
      } else {
        return this.users;
      }
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
