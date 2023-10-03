<template>
  <div>
    <!-- <div class="row justify-around">{{ store.getDscanLocalCorps }}</div> -->
    <div class="row justify-around">
      <div class="col">
        <q-card class="my-card">
          <q-card-section class="q-py-none text-center">
            All Alliances - {{ totalAllianceTotalInSystem }}
            <span :class="textColor(totalAllianceDff)"> ({{ totalAllianceDff }})</span>
          </q-card-section>
          <q-card-section class="overflow-auto" :style="h">
            <transition-group
              mode="out-in"
              enter-active-class="animate__animated animate__bounceIn animate__slower"
              leave-active-class="animate__animated animate__bounceOut animate__slower"
            >
              <q-list bordered dense>
                <q-item
                  clickable
                  :class="[
                    allianceBgColor(list.details.standing),
                    { ownD: !list.totalInSystem },
                  ]"
                  class="cursor-pointer"
                  v-for="(list, index) in store.dScanLocalAlliance"
                  :key="index"
                  @click="openAllIanceDetails(list)"
                >
                  <div
                    class="row full-width justify-between items-center"
                    :key="`${list.id}-row-alliance`"
                  >
                    <div class="col">
                      <q-avatar size="32px" :key="`${list.id}-ave-alliance`">
                        <q-img :src="list.details.url" :key="`${list.id}-img-alliance`" />
                      </q-avatar>
                    </div>
                    <div class="col" :key="`${list.id}-ticket-col-alliance`">
                      <span :key="`${list.id}-row-ticker-alliance`">
                        {{ list.details.ticker }}
                      </span>
                    </div>
                    <div class="col" :key="`${list.id}-totalinsystem-col-alliance`">
                      <transition
                        mode="out-in"
                        enter-active-class="animate__animated animate__flash "
                      >
                        <span
                          :key="`${list.totalInSystem}${list.id}-row-totalinsystem-alliance`"
                        >
                          {{ list.totalInSystem }}</span
                        ></transition
                      >

                      <transition
                        mode="out-in"
                        enter-active-class="animate__animated animate__flash "
                      >
                        <span
                          :class="textColor(list.diff)"
                          v-if="list.diff"
                          :key="`${list.totalInSystem}${list.id}-row-diffNumber`"
                        >
                          ({{ list.diff }})</span
                        ></transition
                      >
                    </div>
                  </div>
                </q-item>
              </q-list>
            </transition-group>
          </q-card-section>
        </q-card>
      </div>

      <div class="col">
        <q-card class="my-card">
          <q-card-section class="q-py-none text-center">
            <div class="row flex-center">
              All Corporations - {{ totalCorpTotalInSystem }}
              <span :class="textColor(totalCorpDff)"> ({{ totalCorpDff }})</span>
              <q-btn
                color="warning"
                flat
                v-if="unknownCount"
                padding="none"
                class="q-ml-sm"
                size="sm"
                round
                icon="fa-solid fa-arrows-rotate"
                @click="getNames"
              />
            </div>
          </q-card-section>
          <q-card-section class="overflow-auto" :style="h">
            <q-list bordered dense>
              <transition-group
                mode="out-in"
                enter-active-class="animate__animated animate__bounceIn animate__slower"
                leave-active-class="animate__animated animate__bounceOut animate__slower"
              >
                <q-item
                  clickable
                  :class="[corpBgColor(list.details), { ownD: !list.totalInSystem }]"
                  v-for="(list, index) in store.dScanLocalCorp"
                  :key="index"
                  @click="openCorpDetails(list)"
                >
                  <div
                    :key="`${list.id}-row-corp`"
                    class="row full-width justify-between items-center"
                  >
                    <div class="col" :key="`${list.id}-avatar-col-corp`">
                      <q-avatar size="32px" :key="`${list.id}-avatar-corp`">
                        <q-img :key="`${list.id}-img-corp`" :src="list.details.url" />
                      </q-avatar>
                    </div>
                    <div class="col" :key="`${list.id}-ticket-col-corp`">
                      <span :key="`${list.id}-row-ticker-corp`">
                        {{ list.details.ticker }}</span
                      >
                    </div>
                    <div class="col" :key="`${list.id}-totalinsystem-col-corp`">
                      <transition
                        mode="out-in"
                        enter-active-class="animate__animated animate__flash "
                      >
                        <span
                          :key="`${list.totalInSystem}${list.id}-row-totalinsystem-corp`"
                        >
                          {{ list.totalInSystem }}</span
                        ></transition
                      >

                      <transition
                        mode="out-in"
                        enter-active-class="animate__animated animate__flash "
                      >
                        <span
                          :class="textColor(list.diff)"
                          v-if="list.diff"
                          :key="`${list.totalInSystem}${list.id}-row-diffNumber-corp`"
                        >
                          ({{ list.diff }})</span
                        ></transition
                      >
                    </div>
                  </div>
                </q-item>
              </transition-group>
            </q-list>
          </q-card-section>
        </q-card>
      </div>
      <div class="col">
        <q-card class="my-card">
          <q-card-section class="q-py-none text-center">
            All Affiliations - {{ totalAffiliationTotalInSystem }}
            <span :class="textColor(totalAffiliationDff)">
              ({{ totalAffiliationDff }})</span
            >
          </q-card-section>
          <q-card-section class="overflow-auto" :style="h">
            <q-list bordered dense>
              <transition-group
                mode="out-in"
                enter-active-class="animate__animated animate__bounceIn animate__slower"
                leave-active-class="animate__animated animate__bounceOut animate__slower"
              >
                <q-item
                  clickable
                  :class="[
                    affiliationBgColor(list.details),
                    { ownD: !list.totalInSystem },
                  ]"
                  v-for="(list, index) in store.dScanLocalAffiliation"
                  :key="index"
                  @click="openAffiliationDetails(list)"
                >
                  <div
                    :key="`${list.id}-row-Affiliation`"
                    class="row full-width justify-between items-center"
                  >
                    <div class="col" :key="`${list.id}-ticket-col-Affiliation`">
                      <span :key="`${list.id}-row-ticker-Affiliation`">
                        {{ list.details.short_name }}</span
                      >
                    </div>
                    <div class="col" :key="`${list.id}-totalinsystem-col-Affiliation`">
                      <transition
                        mode="out-in"
                        enter-active-class="animate__animated animate__flash "
                      >
                        <span
                          :key="`${list.totalInSystem}${list.id}-row-totalinsystem-Affiliation`"
                        >
                          {{ list.totalInSystem }}</span
                        ></transition
                      >

                      <transition
                        mode="out-in"
                        enter-active-class="animate__animated animate__flash "
                      >
                        <span
                          :class="textColor(list.diff)"
                          v-if="list.diff"
                          :key="`${list.totalInSystem}${list.id}-row-diffNumber-Affiliation`"
                        >
                          ({{ list.diff }})</span
                        ></transition
                      >
                    </div>
                  </div>
                </q-item>
              </transition-group>
            </q-list>
          </q-card-section>
        </q-card>
      </div>
    </div>
    <q-dialog v-model="showAllianceDetails" persistent>
      <q-card class="myRoundTop" style="min-width: 20vw; max-width: 30vw">
        <q-card-section class="row items-center">
          <q-avatar size="32px" :key="`${clickedAlliace.id}-ave-alliance-dialog`">
            <q-img
              :src="clickedAlliace.details.url"
              :key="`${clickedAlliace.id}-img-alliance-dialog`"
            />
          </q-avatar>
          <span class="q-ml-sm">Pilots in {{ clickedAlliace.details.ticker }}.</span>
        </q-card-section>
        <q-card-section class="overflow-auto" :style="h">
          <q-list bordered dense>
            <q-item
              v-for="(list, index) in allianceDialogPilots"
              :key="index"
              dense
              :class="pilotBgColor(list.pivot)"
            >
              <div class="row full-width justify-start items-baseline">
                <div class="col-auto">
                  <q-avatar size="32px" :key="`${list.id}-ave-alliance-dialog-list`">
                    <q-img
                      :src="list.corp.url"
                      :key="`${list.id}-img-alliance-dialog-list`"
                    />
                  </q-avatar>
                  <q-avatar size="32px" :key="`${list.id}-ave-alliance-dialog-list`">
                    <q-img
                      :src="charURL(list.id)"
                      :key="`${list.id}-img-alliance-dialog-list`"
                    />
                  </q-avatar>
                </div>
                <div class="col-auto q-pl-lg">
                  {{ list.name }} - {{ list.corp.ticker }}
                </div>
              </div>
            </q-item>
          </q-list>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn rounded label="close" color="negative" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showCorpDetails" persistent>
      <q-card class="myRoundTop" style="min-width: 20vw; max-width: 30vw">
        <q-card-section class="row items-center">
          <q-avatar size="32px" :key="`${clickedCorp.id}-ave-alliance-dialog`">
            <q-img
              :src="clickedCorp.details.url"
              :key="`${clickedCorp.id}-img-alliance-dialog`"
            />
          </q-avatar>
          <span class="q-ml-sm">Pilots in {{ clickedCorp.details.ticker }}.</span>
        </q-card-section>
        <q-card-section class="overflow-auto" :style="h">
          <q-list bordered dense>
            <q-item
              v-for="(list, index) in CorpDialogPilots"
              :key="index"
              dense
              :class="pilotBgColor(list.pivot)"
            >
              <div class="row full-width justify-start items-baseline">
                <div class="col-auto">
                  <q-avatar size="32px" :key="`${list.id}-ave-alliance-dialog-list`">
                    <q-img
                      :src="charURL(list.id)"
                      :key="`${list.id}-img-alliance-dialog-list`"
                    />
                  </q-avatar>
                </div>
                <div class="col-auto q-pl-lg">
                  {{ list.name }} - {{ list.corp.ticker }}
                </div>
              </div>
            </q-item>
          </q-list>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn rounded label="close" color="negative" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-dialog v-model="showAffiliationDetails" persistent>
      <q-card class="myRoundTop" style="min-width: 20vw; max-width: 30vw">
        <q-card-section class="row items-center">
          <span class="q-ml-sm">Pilots in {{ clickedAffiliation.details.name }}.</span>
        </q-card-section>
        <q-card-section class="overflow-auto" :style="h">
          <q-list bordered dense>
            <q-item
              v-for="(list, index) in AffiliationDialogPilots"
              :key="index"
              dense
              :class="pilotBgColor(list.pivot)"
            >
              <div class="row full-width justify-start items-baseline">
                <div class="col-auto">
                  <q-avatar size="32px" :key="`${list.id}-ave-affilition-dialog-list`">
                    <q-img
                      :src="list.corp.alliance.url"
                      :key="`${list.id}-img-affilition-dialog-list`"
                    />
                  </q-avatar>
                  <q-avatar size="32px" :key="`${list.id}-ave-affilition-dialog-list`">
                    <q-img
                      :src="list.corp.url"
                      :key="`${list.id}-img-affilition-dialog-list`"
                    />
                  </q-avatar>
                  <q-avatar size="32px" :key="`${list.id}-ave-affilition-dialog-list`">
                    <q-img
                      :src="charURL(list.id)"
                      :key="`${list.id}-img-affilition-dialog-list`"
                    />
                  </q-avatar>
                </div>
                <div class="col-auto q-pl-lg">
                  {{ list.name }} - {{ list.corp.alliance.ticker }} - ({{
                    list.corp.ticker
                  }})
                </div>
              </div>
            </q-item>
          </q-list>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn rounded label="close" color="negative" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { useRoute, useRouter } from "vue-router";
let route = useRoute();
let router = useRouter();
let store = useMainStore();
let clickedAlliace = $ref(null);
let showAllianceDetails = $ref(false);
let clickedCorp = $ref(null);
let showCorpDetails = $ref(false);
let showAffiliationDetails = $ref(false);
let clickedAffiliation = $ref(null);

// const props = defineProps({
//   type: Number,
// });

let openAllIanceDetails = (alliance) => {
  clickedAlliace = alliance;
  showAllianceDetails = true;
};

let allianceDialogPilots = $computed(() => {
  if (clickedAlliace) {
    return store.dScan.locals
      .filter((l) => l.corp && l.corp.alliance_id == clickedAlliace.details.id)
      .sort((a, b) => a.name.localeCompare(b.name));
  }
});

let openCorpDetails = (corp) => {
  clickedCorp = corp;
  showCorpDetails = true;
};

let openAffiliationDetails = (corp) => {
  clickedAffiliation = corp;
  showAffiliationDetails = true;
};

let CorpDialogPilots = $computed(() => {
  if (clickedCorp) {
    return store.dScan.locals
      .filter((l) => l.corp_id == clickedCorp.details.id)
      .sort((a, b) => a.name.localeCompare(b.name));
  }
});

let AffiliationDialogPilots = $computed(() => {
  if (clickedAffiliation) {
    return store.dScan.locals
      .filter(
        (l) =>
          l.corp &&
          l.corp.alliance_id &&
          l.corp.alliance.affiliation_id == clickedAffiliation.details.id
      )
      .sort((a, b) => a.name.localeCompare(b.name));
  }
});

let charURL = (id) => {
  return `https://imageserver.eveonline.com/Character/${id}_64.jpg`;
};

let pilotBgColor = (pivot) => {
  if (pivot.new) {
    return "bg-positive";
  } else if (pivot.left) {
    return "bg-negative";
  }
};

let totalAllianceNew = $computed(() => {
  let total = 0;
  if (!store.dScanLocalAlliance || Object.values(store.dScanLocalAlliance).length == 0) {
    return 0;
  }
  Object.values(store.dScanLocalAlliance).forEach((alliance) => {
    total += alliance.new;
  });

  return total;
});

let totalAllianceleft = $computed(() => {
  let total = 0;
  if (!store.dScanLocalAlliance || Object.values(store.dScanLocalAlliance).length == 0) {
    return 0;
  }
  Object.values(store.dScanLocalAlliance).forEach((alliance) => {
    total += alliance.left;
  });

  return total;
});

let totalAllianceSame = $computed(() => {
  let total = 0;
  if (!store.dScanLocalAlliance || Object.values(store.dScanLocalAlliance).length == 0) {
    return 0;
  }
  Object.values(store.dScanLocalAlliance).forEach((alliance) => {
    total += alliance.same;
  });

  return total;
});

let totalAllianceTotalInSystem = $computed(() => {
  let total = 0;
  if (!store.dScanLocalAlliance || Object.values(store.dScanLocalAlliance).length == 0) {
    return 0;
  }
  Object.values(store.dScanLocalAlliance).forEach((alliance) => {
    total += alliance.totalInSystem;
  });

  return total;
});

let totalAllianceDff = $computed(() => {
  var newNum = totalAllianceNew;
  var leftNum = totalAllianceleft * -1;
  var sameNum = totalAllianceSame;

  if (sameNum > 0) {
    var diff = newNum + leftNum;
  } else {
    var diff = 0;
  }

  return diff;
});

let totalCorpNew = $computed(() => {
  let total = 0;
  if (!store.dScanLocalCorp || Object.values(store.dScanLocalCorp).length == 0) {
    return 0;
  }
  Object.values(store.dScanLocalCorp).forEach((corp) => {
    total += corp.new;
  });

  return total;
});

let totalCorpleft = $computed(() => {
  let total = 0;
  if (!store.dScanLocalCorp || Object.values(store.dScanLocalCorp).length == 0) {
    return 0;
  }
  Object.values(store.dScanLocalCorp).forEach((corp) => {
    total += corp.left;
  });

  return total;
});

let totalCorpSame = $computed(() => {
  let total = 0;
  if (!store.dScanLocalCorp || Object.values(store.dScanLocalCorp).length == 0) {
    return 0;
  }
  Object.values(store.dScanLocalCorp).forEach((corp) => {
    total += corp.same;
  });

  return total;
});

let totalCorpTotalInSystem = $computed(() => {
  let total = 0;
  if (!store.dScanLocalCorp || Object.values(store.dScanLocalCorp).length == 0) {
    return 0;
  }
  Object.values(store.dScanLocalCorp).forEach((corp) => {
    total += corp.totalInSystem;
  });

  return total;
});

let totalCorpDff = $computed(() => {
  var newNum = totalCorpNew;
  var leftNum = totalCorpleft * -1;
  var sameNum = totalCorpSame;

  if (sameNum > 0) {
    var diff = newNum + leftNum;
  } else {
    var diff = 0;
  }

  return diff;
});

///////#
let totalAffiliationNew = $computed(() => {
  let total = 0;
  if (
    !store.dScanLocalAffiliation ||
    Object.values(store.dScanLocalAffiliation).length == 0
  ) {
    return 0;
  }
  Object.values(store.dScanLocalAffiliation).forEach((affiliation) => {
    total += affiliation.new;
  });

  return total;
});

let totalAffiliationleft = $computed(() => {
  let total = 0;
  if (
    !store.dScanLocalAffiliation ||
    Object.values(store.dScanLocalAffiliation).length == 0
  ) {
    return 0;
  }
  Object.values(store.dScanLocalAffiliation).forEach((affiliation) => {
    total += affiliation.left;
  });

  return total;
});

let totalAffiliationSame = $computed(() => {
  let total = 0;
  if (
    !store.dScanLocalAffiliation ||
    Object.values(store.dScanLocalAffiliation).length == 0
  ) {
    return 0;
  }
  Object.values(store.dScanLocalAffiliation).forEach((affiliation) => {
    total += affiliation.same;
  });

  return total;
});

let totalAffiliationTotalInSystem = $computed(() => {
  let total = 0;
  if (
    !store.dScanLocalAffiliation ||
    Object.values(store.dScanLocalAffiliation).length == 0
  ) {
    return 0;
  }
  Object.values(store.dScanLocalAffiliation).forEach((affiliation) => {
    total += affiliation.totalInSystem;
  });

  return total;
});

let totalAffiliationDff = $computed(() => {
  var newNum = totalAffiliationNew;
  var leftNum = totalAffiliationleft * -1;
  var sameNum = totalAffiliationSame;

  if (sameNum > 0) {
    var diff = newNum + leftNum;
  } else {
    var diff = 0;
  }

  return diff;
});

///////

let textColor = (count) => {
  if (count > 0) {
    return "text-green";
  } else if (count < 0) {
    return "text-red";
  } else {
    return "text-white";
  }
};

let allianceBgColor = (standing) => {
  if (standing == 10) {
    return "bg-blue-10";
  }

  if (standing >= 5) {
    return "bg-blue-5";
  }

  if (standing > 0) {
    return "bg-light-blue-5";
  }

  if (standing == 0) {
    return "";
  }

  if (standing >= -5) {
    return "bg-red-5";
  }

  if (standing < -5) {
    return "bg-red-10";
  }
};

let corpBgColor = (details) => {
  var standing = 0;
  var cStanding = details.standing ? details.standing : 0;
  var aStanding = details.alliance ? details.alliance.standing : 0;

  if (cStanding == 0) {
    standing = aStanding;
  } else {
    standing = cStanding;
  }
  if (standing == 10) {
    return "bg-blue-10";
  }

  if (standing >= 5) {
    return "bg-blue-5";
  }

  if (standing > 0) {
    return "bg-light-blue-5";
  }
  if (standing == 0) {
    return "";
  }

  if (standing >= -5) {
    return "bg-red-5";
  }

  if (standing < -5) {
    return "bg-red-10";
  }
};

let affiliationBgColor = (details) => {
  if (details.color == 2) {
    return "bg-blue-10";
  }

  if (details.color == 1) {
    return "bg-red-5";
  }
};

let unknownCount = $computed(() => {
  return store.dScanLocalCorp &&
    Object.values(store.dScanLocalCorp).filter((corp) => corp.details.ticker == "???")
      .length
    ? true
    : false;
});

let getNames = async () => {
  var link = route.params.link;
  await axios({
    method: "post",
    withCredentials: true,
    url: "/api/dscan/localupdate/" + link,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let h = $computed(() => {
  let mins = 135;
  let window = store.size.height;

  let size = window - mins + "px";
  return "max-height:" + size;
});
</script>

<style lang="scss">
.ownD {
  opacity: 0.5;
}
</style>
