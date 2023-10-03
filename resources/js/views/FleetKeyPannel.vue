<template>
  <div class="pr-16 pl-16">
    <v-row no-gutters>
      <v-col class="col-xs-12 col-md-12 d-inline-flex">
        <v-card
          tile
          flat
          color="#121212"
          class="d-inline-flex align-content-start"
        >
          <v-card-title>Add/Remove Keys</v-card-title>
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
        <EditKeys v-if="$can('edit_fleet_keys')"></EditKeys>
        <EditFleets v-if="$can('edit_fleet_keys')"></EditFleets>
      </v-col>
    </v-row>
    <v-row no-gutters>
      <v-col class="col-xs-12 col-md-6">
        <v-card tile flat color="#121212" class="align-start">
          <v-btn-toggle right v-model="toggle_exclusive" mandatory :value="0">
            <v-btn
              v-for="(list, index) in buttonList"
              :key="index"
              :loading="loadingf"
              :disabled="loadingf"
              @click="(keyflag = list.id), (toggle_exclusive = list.id)"
            >
              {{ list.name }}
            </v-btn>
          </v-btn-toggle>
        </v-card>
      </v-col>
    </v-row>
    <v-row no-gutters>
      <v-col class="col-xs-6 col-md-6">
        <v-card>
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
            <template v-slot:[`item.keys`]="{ item }">
              <div class="d-inline-flex" v-if="$can('edit_fleet_keys')">
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
                      v-for="(list, index) in filterDropdownList(item.keys)"
                      :key="index"
                      @click="(userAddKeyText = list.id), userAddKey(item)"
                    >
                      <v-list-item-title>{{ list.name }}</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </div>

              <div class="d-inline-flex">
                <div
                  v-for="(key, index) in filterKeys(item.keys)"
                  :key="index"
                  class="pr-2"
                >
                  <v-chip
                    pill
                    :close="pillClose(key.name)"
                    dark
                    @click:close="
                      (userRemoveKeyText = key.id), userRemoveKey(item)
                    "
                  >
                    <span> {{ key.name }}</span>
                  </v-chip>
                </div>
              </div>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <FCMessage class="mr-2" :user="item"></FCMessage>
            </template>
            <template slot="no-data"> Nothing matches your filters </template>
          </v-data-table>
        </v-card>
      </v-col>
      <v-col class="col-md-1"></v-col>

      <v-col class="col-md-4">
        <v-col class="col-md-12 d-flex flex-wrap">
          <v-card
            v-for="(list, index) in tableList"
            :key="index"
            max-height="300px"
            elevation="10"
            class="col-md-5 ma-4"
          >
            <v-card>
              <v-card-title class="justify-center" elevation="24"
                ><v-btn
                  text
                  @click="(keyflag = list.id), (toggle_exclusive = list.id)"
                  >{{ list.name }}</v-btn
                ></v-card-title
              >
              <v-card-text class="text-center">
                <v-list outlined max-height="200px" class="overflow-y-auto">
                  <v-list-item
                    class="font-weight-light text-center"
                    v-for="(fleet, index) in list.fleets"
                    :key="index"
                  >
                    <v-list-item-title>
                      {{ fleet.name }}
                    </v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-card-text>
            </v-card>
          </v-card>
        </v-col>
      </v-col>
      <v-col class="col-md-1"></v-col>
    </v-row>
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
    return `EveStuff - Fleet Keys`;
  },
  data() {
    return {
      //timersAll: [],

      headers: [
        { text: "Name", value: "name" },
        { text: "Keys", value: "keys", width: "75%" },
        { text: "", value: "actions" },
      ],
      loadingr: false,
      loadingf: false,
      loading: false,
      toggle_exclusive: 0,
      search: "",
      addShown: false,
      userAddKeyText: "",
      userRemoveKeyText: "",
      keyflag: 0,
      logs: false,
    };
  },

  async created() {
    Echo.private("fleetkeys").listen("FleetKeysUpdate", (e) => {
      if (e.flag.message != null) {
        this.$store.dispatch("updateKeyMessage", e.flag.message);
      } else {
        this.refresh();
      }
    });
    await this.$store.dispatch("getUserKeys");
    await this.$store.dispatch("getKeys");
    await this.$store.dispatch("getFleets");
    await this.$store.dispatch("getKeyFleets");
  },

  async mounted() {
    this.log();
  },

  methods: {
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
    filterKeys(keys) {
      return keys;
    },

    async refresh() {
      await this.$store.dispatch("getUserKeys");
      await this.$store.dispatch("getKeys");
      await this.$store.dispatch("getFleets");
      await this.$store.dispatch("getKeyFleets");
    },

    filterDropdownList(item) {
      let keyID = item.map((i) => i.id);

      var filter = this.keysList.filter((r) => !keyID.includes(r.id));
      filter = filter.filter((r) => r.name != "All");
      if (this.$can("edit_fleet_keys")) {
        return filter;
      }
    },

    pillClose(name) {
      if (this.$can("edit_fleet_keys")) {
        return true;
      } else {
        return false;
      }
    },

    fliterFleets(fleets) {
      return fleets;
    },

    async userAddKey(item) {
      var request = {
        key_type_id: this.userAddKeyText,
        user_id: item.id,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/keysadd",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async userRemoveKey(item) {
      var request = {
        key_type_id: this.userRemoveKeyText,
        user_id: item.id,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/keysremove",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    ...mapState(["userkeys", "keysList", "keyfleets"]),
    filteredItems() {
      var keyid = this.keyflag;
      if (this.keyflag != 0) {
        return this.userkeys.filter(function (u) {
          return u.keys.some(function (key) {
            return key.id == keyid;
          });
        });
      } else {
        return this.userkeys;
      }
    },

    buttonList() {
      var list = this.keysList;
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

    tableList() {
      return this.keyfleets;
    },
  },
  beforeDestroy() {
    Echo.leave("fleetkeys");
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
