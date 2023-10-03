<template>
  <div>
    <v-menu offset-y v-if="checkShowAdd(item)">
      <template v-slot:activator="{ on, attrs }">
        <div>
          <v-btn v-bind="attrs" v-on="on" icon color="green darken-3">
            <font-awesome-icon icon="fa-solid fa-plus" size="2xl" />
          </v-btn>
        </div>
      </template>

      <v-list>
        <v-list-item
          v-for="(list, index) in charsFree"
          :key="index"
          @click="(charAddNode = list.id), clickCharAddNode(item)"
        >
          <v-list-item-title>{{ list.char_name }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    item: Object,
  },
  data() {
    return {};
  },

  methods: {
    showAdd() {
      this.$emit("openAdd", this.item);
    },

    async clickCharAddNode(item) {
      var addChar = this.chars.find((user) => user.id == this.charAddNode);
      var request = {
        campaign_id: item.campaign_id,
        campaign_system_id: item.id,
        campaign_user_id: addChar.id,
      };

      await axios({
        method: "post",
        url: "/api/nodejoin/" + item.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      request = null;
      request = {
        campaign_system_id: item.id,
        status_id: 4,
        system_id: item.system_id,
      };

      await axios({
        method: "put",
        url: "/api/campaignusers/" + addChar.id + "/" + item.campaign_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    checkShowAdd(item) {
      if (
        item.user_name != null &&
        this.freecharCount != 0 &&
        item.status_id != 4 &&
        item.status_id != 5 &&
        item.status_id != 7 &&
        item.status_id != 8
      ) {
        return true;
      } else {
        return false;
      }
    },
  },

  computed: {
    ...mapGetters([
      "getCampaignUsersByUserIdEntosisFree",
      "getCampaignUsersByUserIdEntosisCount",
      "getCampaignUsersByUserIdEntosisFreeCount",
      "getCampaignUsersByUserIdEntosis",
    ]),

    charsFree() {
      return this.getCampaignUsersByUserIdEntosisFree(
        this.$store.state.user_id
      );
    },

    charCount() {
      return this.getCampaignUsersByUserIdEntosisCount(
        this.$store.state.user_id
      );
    },

    freecharCount() {
      return this.getCampaignUsersByUserIdEntosisFreeCount(
        this.$store.state.user_id
      );
    },

    chars() {
      return this.getCampaignUsersByUserIdEntosis(this.$store.state.user_id);
    },
  },
};
</script>

<style></style>
