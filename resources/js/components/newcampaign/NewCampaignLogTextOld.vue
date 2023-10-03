<template>
  <v-col cols="12"> {{ text }}</v-col>
</template>

<script>
import moment from "moment";
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: { windowSize: Object, item: [Object, Array] },
  data() {
    return {};
  },

  methods: {},

  computed: {
    text() {
      if (this.item.log_name == "System Node") {
        if (this.item.event == "deleted") {
          return (
            "Deleted Node " +
            this.item.properties.old.name +
            " from system " +
            this.item.properties.old["system.system_name"]
          );
        }
        if (this.item.event == "created") {
          return (
            "Created Node " +
            this.item.properties.attributes.name +
            " in system " +
            this.item.properties.attributes["system.system_name"]
          );
        }

        if (this.item.event == "updated") {
          var t1 = null;
          if (
            this.item.properties.attributes.node_status !=
            this.item.properties.old.node_status
          ) {
            t1 =
              "Node status changed from " +
              this.item.properties.old["nodeStatus.name"] +
              " to " +
              this.item.properties.attributes["nodeStatus.name"];
          }

          return t1;
        }

        return "Report this number (" + this.item.id + ") in a bug report";
      }

      if (this.item.log_name == "User Node") {
        if (this.item.event == "updated") {
          var text = null;
          var t1 = null;
          var t2 = null;
          if (
            this.item.properties.old.end_time !=
            this.item.properties.attributes.end_time
          ) {
            var oldTime = this.item.properties.old.end_time ?? "none";
            var newTime = this.item.properties.attributes.end_time ?? "none";
            t1 =
              this.item.properties.attributes.nodeStatus.name +
              " timer changed from " +
              oldTime +
              " to " +
              newTime;
          }

          if (
            this.item.properties.old.node_status_id !=
            this.item.properties.attributes.node_status_id
          ) {
            var oldStatus = this.item.properties.old.nodeStatus.name ?? "none";
            var newStatus =
              this.item.properties.attributes.nodeStatus.name ?? "none";

            t2 = "Node Status Changed from " + oldStatus + " to " + newStatus;
          }

          if (t1) {
            text = t1;
          }
          if (t2) {
            text = t2;
          }

          return text;
        }

        if (this.item.event == "deleted") {
          return (
            "Hacker " +
            this.item.properties.old.opUser.name +
            " removed from node " +
            this.item.properties.old.node.name +
            " in system " +
            this.item.properties.old.node.system.system_name
          );
        }

        if (this.item.event == "created") {
          return (
            "Hacker " +
            this.item.properties.attributes.opUser.name +
            " added to node " +
            this.item.properties.attributes.node.name +
            " in system " +
            this.item.properties.attributes.node.system.system_name
          );
        }
        return "Report this number (" + this.item.id + ") in a bug report";
      }

      if (this.item.log_name == "Operations") {
        if (this.item.event == "updated") {
          if (this.item.properties.attributes.read_only == 1) {
            var action = "on";
          } else {
            var action = "off";
          }
          return "Read Only turned " + action;
        }
        return "Report this number (" + this.item.id + ") in a bug report";
      }
    },
  },
};
</script>

<style></style>
