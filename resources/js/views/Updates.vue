<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { EventBus } from "../event-bus";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {
    return `EVE`;
  },
  data() {
    return {
      timer_data: [],
      all_alliance_ids: [],
      all_system_ids: [],
      loading2: null,
      system_fetch_ids: [],
      system_fetch: [],
      alliance_fetch: [],
      alliance_data: [],
      count: null,
      new_alliance_ids: [],
      url: null,
      loop: 0,
      system_data: [],
      system_data_length: 0,
      check: null,
      limt: 100,
      limtTime: 100,

      headers: [
        { text: "Designation", value: "name", width: "35%" },
        { text: "System", value: "system_name" },
        { text: "Structure type", value: "structure_type_name" },
        {
          text: "Occupancy Level",
          value: "vulnerability_occupancy_level",
        },
        {
          text: "Vulnerable Start Time",
          value: "vulnerable_start_time",
        },
      ],
    };
  },
  async mounted() {
    await this.getTimers();
    await this.sameTimers();
  },
  methods: {
    async getTimers() {
      this.timer_data = [];
      const res = await axios
        .get(
          "https://esi.evetech.net/latest/sovereignty/structures/?datasource=tranquility"
        )
        .then((res) => {
          if (res.status == 200) {
            for (var i = 0; i < res.data.length; i++) {
              this.timer_data.push(res.data[i]);
            }
          }
        });
    },

    async sameTimers() {
      this.check = "sameTimers";
      const alliance_data = await axios.post("/saveTimers", this.timer_data);
      this.loading2 = false;
      EventBus.$emit("buttonupdate", this.loading2);
    },
  },
};

computed: {
}
</script>
