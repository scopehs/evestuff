<template>
  <div class="d-inline-flex align-items-md-center">
    <div>
      <v-btn class="mr-4 ml-4 mb-4" color="green" small @click="checkClick()">
        <font-awesome-icon
          icon="fa-solid fa-magnifying-glass-location"
          pull="left"
        />
        System Checked</v-btn
      >
    </div>
    <div>
      <span
        v-if="CampaignSolaSystem[0]['last_checked_user_name'] != null"
        class="d-inline-flex mb-4"
      >
        Checked by {{ CampaignSolaSystem[0]["last_checked_user_name"] }}
        <VueCountUptimer
          :start-time="moment.utc(CampaignSolaSystem[0]['last_checked']).unix()"
          :end-text="'Window Closed'"
          :interval="1000"
          ><template slot="countup" slot-scope="scope"
            ><span v-if="scope.props.minutes < 5" class="green--text pl-2 pr-2"
              >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                scope.props.seconds
              }}</span
            >
            <span v-else class="red--text pl-2 pr-2"
              >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                scope.props.seconds
              }}</span
            >
          </template>
        </VueCountUptimer>
        ago
      </span>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    CampaignSolaSystem: Array,
  },
  data() {
    return {
      test1: "",
    };
  },

  methods: {
    async checkClick() {
      this.test1 = this.CampaignSolaSystem[0]["id"];
      var timeStamp = moment.utc().format("YYYY-MM-DD HH:mm:ss");

      var data = {
        id: this.CampaignSolaSystem[0]["id"],
        last_checked_user_id: this.$store.state.user_id,
        last_checked_user_name: this.$store.state.user_name,
        last_checked: timeStamp,
      };

      this.$store.dispatch("updateCampaignSolaSystem", data);

      var request = null;
      request = {
        last_checked_user_id: this.$store.state.user_id,
        last_checked: timeStamp,
      };

      await axios({
        //adds user name of last checked
        method: "put",
        url:
          "/api/campaignsolasystems/" +
          this.CampaignSolaSystem[0]["id"] +
          "/" +
          this.CampaignSolaSystem[0]["campaign_id"],
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      //------logging start------//
      var request = null;
      request = {
        user_id: this.$store.state.user_id,
        campaign_sola_system_id: this.CampaignSolaSystem[0]["id"],
      };

      await axios({
        method: "put",
        url:
          "/api/checklastedchecked/" +
          this.CampaignSolaSystem[0]["campaign_id"],
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {},
};
</script>

<style></style>
