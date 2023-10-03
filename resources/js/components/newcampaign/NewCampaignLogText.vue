<template>
  <div>
    {{ text }}
  </div>
</template>

<script setup>
const props = defineProps({
  item: [Object, Array],
});

let text = $computed(() => {
  if (props.item.log_name == "System Node") {
    if (props.item.event == "deleted") {
      return (
        "Deleted Node " +
        props.item.properties.old.name +
        " from system " +
        props.item.properties.old["system.system_name"]
      );
    }
    if (props.item.event == "created") {
      return (
        "Created Node " +
        props.item.properties.attributes.name +
        " in system " +
        props.item.properties.attributes["system.system_name"]
      );
    }

    if (props.item.event == "updated") {
      var t1 = null;
      if (
        props.item.properties.attributes.node_status !=
        props.item.properties.old.node_status
      ) {
        t1 =
          "Node status changed from " +
          props.item.properties.old["nodeStatus.name"] +
          " to " +
          props.item.properties.attributes["nodeStatus.name"];
      }

      return t1;
    }

    return "Report this number (" + props.item.id + ") in a bug report";
  }

  if (props.item.log_name == "User Node") {
    if (props.item.event == "updated") {
      var text = null;
      var t1 = null;
      var t2 = null;
      if (
        props.item.properties.old.end_time != props.item.properties.attributes.end_time
      ) {
        var oldTime = props.item.properties.old.end_time ?? "none";
        var newTime = props.item.properties.attributes.end_time ?? "none";
        t1 =
          props.item.properties.attributes.nodeStatus.name +
          " timer changed from " +
          oldTime +
          " to " +
          newTime;
      }

      if (
        props.item.properties.old.node_status_id !=
        props.item.properties.attributes.node_status_id
      ) {
        var oldStatus = props.item.properties.old.nodeStatus.name ?? "none";
        var newStatus = props.item.properties.attributes.nodeStatus.name ?? "none";

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

    if (props.item.event == "deleted") {
      return (
        "Hacker " +
        props.item.properties.old.opUser.name +
        " removed from node " +
        props.item.properties.old.node.name +
        " in system " +
        props.item.properties.old.node.system.system_name
      );
    }

    if (props.item.event == "created") {
      return (
        "Hacker " +
        props.item.properties.attributes.opUser.name +
        " added to node " +
        props.item.properties.attributes.node.name +
        " in system " +
        props.item.properties.attributes.node.system.system_name
      );
    }
    return "Report this number (" + props.item.id + ") in a bug report";
  }

  if (props.item.log_name == "Operations") {
    if (props.item.event == "updated") {
      if (props.item.properties.attributes.read_only == 1) {
        var action = "on";
      } else {
        var action = "off";
      }
      return "Read Only turned " + action;
    }
    return "Report this number (" + props.item.id + ") in a bug report";
  }
});
</script>

<style lang="scss"></style>
