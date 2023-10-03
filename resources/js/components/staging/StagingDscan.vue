<template>
  <div class="cursor-pointer" @click="openDscan">
    {{ totalAllianceTotalInSystem
    }}<span :class="textColor(totalAllianceDff)">({{ totalAllianceDff }})</span>/{{
      totalCorpTotalInSystem
    }}<span :class="textColor(totalCorpDff)">({{ totalCorpDff }})</span>/{{
      totalAffiliationTotalInSystem
    }}<span :class="textColor(totalAffiliationDff)">({{ totalAffiliationDff }})</span> -
    {{ shipSystemTotals }}<span :class="textColor(shipDff)">({{ shipDff }})</span>/{{
      structureSystemTotals
    }}<span :class="textColor(strDff)">({{ strDff }})</span>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";

let store = useMainStore();
let can = inject("can");

const props = defineProps({
  item: { type: [Object, Array], required: false },
});

let textColor = (count) => {
  if (count > 0) {
    return "text-green";
  } else if (count < 0) {
    return "text-red";
  } else {
    return "text-webway";
  }
};

let alliance = $computed(() => {
  return props.item.dscan && props.item.dscan.allianceTotal
    ? props.item.dscan.allianceTotal
    : 0;
});

let openDscan = () => {
  const uuid = props.item.dscan.link;
  const dscanUrl = `https://evestuff.apps/dscan/${uuid}`;
  window.open(dscanUrl, "_blank");
};

let totalAllianceTotalInSystem = $computed(() => {
  let total = 0;
  if (!alliance || Object.values(alliance).length == 0) {
    return "?";
  }
  Object.values(alliance).forEach((alliance) => {
    total += alliance.totalInSystem;
  });

  return total;
});

let totalAllianceSame = $computed(() => {
  let total = 0;
  if (!alliance || Object.values(alliance).length == 0) {
    return 0;
  }
  Object.values(alliance).forEach((alliance) => {
    total += alliance.same;
  });

  return total;
});

let totalAllianceleft = $computed(() => {
  let total = 0;
  if (!alliance || Object.values(alliance).length == 0) {
    return 0;
  }
  Object.values(alliance).forEach((alliance) => {
    total += alliance.left;
  });

  return total;
});

let totalAllianceNew = $computed(() => {
  let total = 0;
  if (!alliance || Object.values(alliance).length == 0) {
    return 0;
  }
  Object.values(alliance).forEach((alliance) => {
    total += alliance.new;
  });

  return total;
});

let totalAllianceDff = $computed(() => {
  if (alliance) {
    var newNum = totalAllianceNew;
    var leftNum = totalAllianceleft * -1;
    var sameNum = totalAllianceSame;

    if (sameNum > 0) {
      var diff = newNum + leftNum;
    } else {
      var diff = 0;
    }

    return diff;
  }
  return "?";
});

let corp = $computed(() => {
  return props.item.dscan && props.item.dscan.corpTotal ? props.item.dscan.corpTotal : 0;
});

let totalCorpTotalInSystem = $computed(() => {
  let total = 0;
  if (!corp || Object.values(corp).length == 0) {
    return "?";
  }
  Object.values(corp).forEach((corp) => {
    total += corp.totalInSystem;
  });

  return total;
});

let totalCorpNew = $computed(() => {
  let total = 0;
  if (!corp || Object.values(corp).length == 0) {
    return 0;
  }
  Object.values(corp).forEach((corp) => {
    total += corp.new;
  });

  return total;
});

let totalCorpleft = $computed(() => {
  let total = 0;
  if (!corp || Object.values(corp).length == 0) {
    return 0;
  }
  Object.values(corp).forEach((corp) => {
    total += corp.left;
  });

  return total;
});

let totalCorpSame = $computed(() => {
  let total = 0;
  if (!corp || Object.values(corp).length == 0) {
    return 0;
  }
  Object.values(corp).forEach((corp) => {
    total += corp.same;
  });

  return total;
});

let totalCorpDff = $computed(() => {
  if (corp) {
    var newNum = totalCorpNew;
    var leftNum = totalCorpleft * -1;
    var sameNum = totalCorpSame;

    if (sameNum > 0) {
      var diff = newNum + leftNum;
    } else {
      var diff = 0;
    }

    return diff;
  }
  return "?";
});

let affiliation = $computed(() => {
  return props.item.dscan && props.item.dscan.affiliationsTotal
    ? props.item.dscan.affiliationsTotal
    : 0;
});

let totalAffiliationNew = $computed(() => {
  let total = 0;
  if (!affiliation || Object.values(affiliation).length == 0) {
    return 0;
  }
  Object.values(affiliation).forEach((affiliation) => {
    total += affiliation.new;
  });

  return total;
});

let totalAffiliationleft = $computed(() => {
  let total = 0;
  if (!affiliation || Object.values(affiliation).length == 0) {
    return 0;
  }
  Object.values(affiliation).forEach((affiliation) => {
    total += affiliation.left;
  });

  return total;
});

let totalAffiliationSame = $computed(() => {
  let total = 0;
  if (!affiliation || Object.values(affiliation).length == 0) {
    return 0;
  }
  Object.values(affiliation).forEach((affiliation) => {
    total += affiliation.same;
  });

  return total;
});

let totalAffiliationTotalInSystem = $computed(() => {
  let total = 0;
  if (!affiliation || Object.values(affiliation).length == 0) {
    return "?";
  }
  Object.values(affiliation).forEach((affiliation) => {
    total += affiliation.totalInSystem;
  });

  return total;
});

let totalAffiliationDff = $computed(() => {
  if (affiliation) {
    var newNum = totalAffiliationNew;
    var leftNum = totalAffiliationleft * -1;
    var sameNum = totalAffiliationSame;

    if (sameNum > 0) {
      var diff = newNum + leftNum;
    } else {
      var diff = 0;
    }

    return diff;
  }
  return "?";
});

let tableShips = $computed(() => {
  return props.item.dscan && props.item.dscan.itemsTotals
    ? props.item.dscan.itemsTotals.filter((i) => i.details.group.category_id == 6)
    : 0;
});

let shipSystemTotals = $computed(() => {
  if (tableShips) {
    return tableShips.reduce((acc, item) => acc + item.totalInSystem, 0);
  } else {
    return "?";
  }
});

let shipTotals = $computed(() => {
  if (tableShips) {
    return tableShips.reduce((acc, item) => acc + item.total, 0);
  } else {
    return 0;
  }
});

let shipDff = $computed(() => {
  if (tableShips) {
    var totalInSystem = shipSystemTotals;
    var totalShips = shipTotals;
    var diff = totalInSystem - totalShips;
    return diff;
  }
  return "?";
});

let tableStructure = $computed(() => {
  if (props.item.dscan && props.item.dscan.groupTotals) {
    return props.item.dscan.groupTotals.filter(
      (i) =>
        i.details.category_id == 65 ||
        i.details.category_id == 40 ||
        i.details.category_id == 22
    );
  } else {
    return 0;
  }
});

let structureSystemTotals = $computed(() => {
  if (tableStructure) {
    return tableStructure.reduce((acc, item) => acc + item.totalInSystem, 0);
  } else {
    return "?";
  }
});

let structureTotals = $computed(() => {
  if (tableStructure) {
    return tableStructure.reduce((acc, item) => acc + item.total, 0);
  } else {
    return 0;
  }
});

let strDff = $computed(() => {
  if (tableStructure) {
    var totalStructureInSystem = structureSystemTotals;
    var totalStructure = structureTotals;
    var diff = totalStructureInSystem - totalStructure;
    return diff;
  }
  return "?";
});
</script>

<style lang="scss"></style>
