<template>
  <div>
    <q-btn
      v-if="props.edit == 0"
      color="positive"
      icon="fa-solid fa-plus"
      label="Add"
      rounded
      @click="showAddEdit = !showAddEdit"
    />
    <q-btn
      v-if="props.edit == 1"
      color="positive"
      flat
      icon="fa-solid fa-edit"
      round
      size="xs"
      @click="showAddEdit = !showAddEdit"
    />
    <q-dialog
      @before-show="open()"
      @before-hide="close()"
      v-model="showAddEdit"
      persistent
    >
      <q-card class="myRoundTop" style="max-width: 1000px">
        <q-card-section class="bg-primary text-center text-h4 q-pa-none">
          Make a WatchList
        </q-card-section>
        <q-card-section>
          <q-input v-model="namePicked" dense type="text" label="Name of Watch List" />
        </q-card-section>
        <q-card-section>
          <q-input
            v-model="descriptionPicked"
            dense
            type="text"
            label="What is this list for"
          />
        </q-card-section>
        <q-separator color="webChip" />
        <q-card-section class="text-center text-subtitle1">
          Here is where you select which roles and users can see this list
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="col">
              <q-select
                v-model="rolesPicked"
                option-label="text"
                option-value="value"
                label="Roles with access"
                :options="filtRolesList"
                @filter="filtRolesListStart"
                emit-value
                map-options
                rounded
                standout
                dense
                input-debounce="0"
                label-color="webway"
                ref="rolesDropDown"
                use-input
                use-chips
                multiple
                style="width: 100%"
              >
                <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                  <q-item v-bind="itemProps">
                    <q-item-section>
                      <q-item-label v-html="opt.text" />
                    </q-item-section>
                    <q-item-section side>
                      <q-toggle
                        :model-value="selected"
                        @update:model-value="toggleOption(opt)"
                      />
                    </q-item-section>
                  </q-item>
                </template>

                <template v-slot:selected-item="scope">
                  <q-chip
                    removable
                    @remove="scope.removeAtIndex(scope.index)"
                    :tabindex="scope.tabindex"
                    color="webWay"
                    text-color="white"
                    class="q-ma-none"
                  >
                    <span class="text-xs"> {{ scope.opt.text }} </span>
                  </q-chip>
                </template>
              </q-select>
            </div>
            <div class="col">
              <q-select
                v-model="usersPicked"
                option-label="name"
                option-value="id"
                label="Users with access"
                :options="filtUsersList"
                @filter="filtUsersListStart"
                emit-value
                map-options
                rounded
                standout
                dense
                input-debounce="0"
                label-color="webway"
                ref="usersDropDown"
                use-input
                use-chips
                multiple
                style="width: 100%"
              >
                <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                  <q-item v-bind="itemProps">
                    <q-item-section>
                      <q-item-label v-html="opt.name" />
                    </q-item-section>
                    <q-item-section side>
                      <q-toggle
                        :model-value="selected"
                        @update:model-value="toggleOption(opt)"
                      />
                    </q-item-section>
                  </q-item>
                </template>

                <template v-slot:selected-item="scope">
                  <q-chip
                    removable
                    @remove="scope.removeAtIndex(scope.index)"
                    :tabindex="scope.tabindex"
                    color="webWay"
                    text-color="white"
                    class="q-ma-none"
                  >
                    <span class="text-xs"> {{ scope.opt.name }} </span>
                  </q-chip>
                </template>
              </q-select>
            </div>
          </div>
        </q-card-section>
        <q-separator color="webChip" />
        <q-card-section class="text-center text-subtitle1">
          Here you select the Owership and Type of stations
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="col">
              <q-select
                v-model="alliancePicked"
                option-label="text"
                option-value="value"
                label="Alliance"
                :options="filtAllianceList"
                @filter="filtAllianceListStart"
                emit-value
                map-options
                rounded
                standout
                dense
                input-debounce="0"
                label-color="webway"
                ref="aliianceDropDown"
                use-input
                use-chips
                multiple
                style="width: 100%"
              >
                <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                  <q-item v-bind="itemProps">
                    <q-item-section>
                      <q-item-label v-html="opt.text" />
                    </q-item-section>
                    <q-item-section side>
                      <q-toggle
                        :model-value="selected"
                        @update:model-value="toggleOption(opt)"
                      />
                    </q-item-section>
                  </q-item>
                </template>

                <template v-slot:selected-item="scope">
                  <q-chip
                    removable
                    @remove="scope.removeAtIndex(scope.index)"
                    :tabindex="scope.tabindex"
                    color="webWay"
                    text-color="white"
                    class="q-ma-none"
                  >
                    <span class="text-xs"> {{ scope.opt.text }} </span>
                  </q-chip>
                </template>
              </q-select>
            </div>
            <div class="col">
              <q-select
                v-model="typePicked"
                option-label="text"
                option-value="value"
                label="Station Type"
                :options="filtTypeList"
                @filter="filtTypeListStart"
                emit-value
                map-options
                rounded
                standout
                dense
                input-debounce="0"
                label-color="webway"
                ref="TypeDropDown"
                use-input
                use-chips
                multiple
                style="width: 100%"
              >
                <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                  <q-item v-bind="itemProps">
                    <q-item-section>
                      <q-item-label v-html="opt.text" />
                    </q-item-section>
                    <q-item-section side>
                      <q-toggle
                        :model-value="selected"
                        @update:model-value="toggleOption(opt)"
                      />
                    </q-item-section>
                  </q-item>
                </template>

                <template v-slot:selected-item="scope">
                  <q-chip
                    removable
                    @remove="scope.removeAtIndex(scope.index)"
                    :tabindex="scope.tabindex"
                    color="webWay"
                    text-color="white"
                    class="q-ma-none"
                  >
                    <span class="text-xs"> {{ scope.opt.text }} </span>
                  </q-chip>
                </template>
              </q-select>
            </div>
          </div>
        </q-card-section>
        <q-separator color="webChip" />
        <q-card-section class="text-center">
          <span class="text-subtitle1">
            Here is where you select which stations, systems, constellations and regions
            you want to add to this list.
          </span>

          <br />

          <span class="text-subtitle2">
            If you cant find what you are looking for, make sure you have selected the
            correct region on the setting pannel.</span
          >
        </q-card-section>
        <q-card-section>
          <q-select
            v-model="stationsPicked"
            option-label="text"
            option-value="value"
            label="Stations"
            :options="filtStationList"
            @filter="filtStationListStart"
            emit-value
            map-options
            rounded
            dense
            standout
            input-debounce="0"
            label-color="webway"
            ref="stationsDropDown"
            use-input
            use-chips
            multiple
            style="width: 100%"
          >
            <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
              <q-item v-bind="itemProps">
                <q-item-section>
                  <q-item-label v-html="opt.text" />
                </q-item-section>
                <q-item-section side>
                  <q-toggle
                    :model-value="selected"
                    @update:model-value="toggleOption(opt)"
                  />
                </q-item-section>
              </q-item>
            </template>

            <template v-slot:selected-item="scope">
              <q-chip
                removable
                @remove="scope.removeAtIndex(scope.index)"
                :tabindex="scope.tabindex"
                color="webWay"
                text-color="white"
                class="q-ma-none"
              >
                <span class="text-xs"> {{ scope.opt.text }} </span>
              </q-chip>
            </template>
          </q-select>
          <q-select
            v-model="systemsPicked"
            option-label="text"
            option-value="value"
            label="Systems"
            :options="filtSystemList"
            @filter="filtSystemListStart"
            emit-value
            map-options
            rounded
            dense
            standout
            input-debounce="0"
            label-color="webway"
            ref="system"
            use-input
            use-chips
            multiple
            style="width: 100%"
          >
            <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
              <q-item v-bind="itemProps">
                <q-item-section>
                  <q-item-label v-html="opt.text" />
                </q-item-section>
                <q-item-section side>
                  <q-toggle
                    :model-value="selected"
                    @update:model-value="toggleOption(opt)"
                  />
                </q-item-section>
              </q-item>
            </template>

            <template v-slot:selected-item="scope">
              <q-chip
                removable
                @remove="scope.removeAtIndex(scope.index)"
                :tabindex="scope.tabindex"
                color="webWay"
                text-color="white"
                class="q-ma-none"
              >
                <span class="text-xs"> {{ scope.opt.text }} </span>
              </q-chip>
            </template>
          </q-select>
          <q-select
            v-model="constellationsPicked"
            dense
            option-label="text"
            option-value="value"
            label="Constellations"
            :options="filtConstellationList"
            @filter="filtConstellationListStart"
            emit-value
            map-options
            rounded
            standout
            input-debounce="0"
            label-color="webway"
            ref="constelations"
            use-input
            use-chips
            multiple
            style="width: 100%"
          >
            <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
              <q-item v-bind="itemProps">
                <q-item-section>
                  <q-item-label v-html="opt.text" />
                </q-item-section>
                <q-item-section side>
                  <q-toggle
                    :model-value="selected"
                    @update:model-value="toggleOption(opt)"
                  />
                </q-item-section>
              </q-item>
            </template>

            <template v-slot:selected-item="scope">
              <q-chip
                removable
                @remove="scope.removeAtIndex(scope.index)"
                :tabindex="scope.tabindex"
                color="webWay"
                text-color="white"
                class="q-ma-none"
              >
                <span class="text-xs"> {{ scope.opt.text }} </span>
              </q-chip>
            </template>
          </q-select>

          <q-select
            v-model="regionsPicked"
            dense
            option-label="text"
            regiondropdownlist
            label="Regions"
            :options="filtRegionList"
            @filter="filtRegionListStart"
            emit-value
            map-options
            rounded
            standout
            input-debounce="0"
            label-color="webway"
            ref="regions"
            use-input
            use-chips
            multiple
            style="width: 100%"
          >
            <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
              <q-item v-bind="itemProps">
                <q-item-section>
                  <q-item-label v-html="opt.text" />
                </q-item-section>
                <q-item-section side>
                  <q-toggle
                    :model-value="selected"
                    @update:model-value="toggleOption(opt)"
                  />
                </q-item-section>
              </q-item>
            </template>

            <template v-slot:selected-item="scope">
              <q-chip
                removable
                @remove="scope.removeAtIndex(scope.index)"
                :tabindex="scope.tabindex"
                color="webWay"
                text-color="white"
                class="q-ma-none"
              >
                <span class="text-xs"> {{ scope.opt.text }} </span>
              </q-chip>
            </template>
          </q-select>
        </q-card-section>
        <q-card-actions align="around">
          <q-btn
            rounded
            color="positive"
            :label="addLabel"
            @click="submit"
            v-close-popup
          />
          <q-btn rounded color="negative" label="close" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();

const props = defineProps({
  edit: { type: Number, default: 0 },
  list: { type: Object, required: false },
});

let showAddEdit = $ref(false);
let namePicked = $ref(null);
let descriptionPicked = $ref(null);

//////////Start of station filter
let stationsPicked = $ref([]);
let stationUpdateText = $ref();
let filtStationList = $computed(() => {
  if (stationUpdateText) {
    return store.stationWatchList.filter(
      (d) => d.text.toLowerCase().indexOf(stationUpdateText) > -1
    );
  }
  return store.stationWatchList;
});

let filtStationListStart = (val, update, abort) => {
  update(() => {
    stationUpdateText = val.toLowerCase();
  });
};

/////////edit of station filter

//////////Start of system filter
let systemsPicked = $ref([]);
let systemUpdateText = $ref();
let filtSystemList = $computed(() => {
  if (systemUpdateText) {
    return store.systemdropdownlist.filter(
      (d) => d.text.toLowerCase().indexOf(systemUpdateText) > -1
    );
  }
  return store.systemdropdownlist;
});

let filtSystemListStart = (val, update, abort) => {
  update(() => {
    systemUpdateText = val.toLowerCase();
  });
};

/////////edit of system filter

//////////Start of constellation filter
let constellationsPicked = $ref([]);
let constellationUpdateText = $ref();
let filtConstellationList = $computed(() => {
  if (constellationUpdateText) {
    return store.constellationDropDownlist.filter(
      (d) => d.text.toLowerCase().indexOf(constellationUpdateText) > -1
    );
  }
  return store.constellationDropDownlist;
});

let filtConstellationListStart = (val, update, abort) => {
  update(() => {
    constellationUpdateText = val.toLowerCase();
  });
};

/////////edit of constellation filter

//////////Start of region filter
let regionsPicked = $ref([]);
let regionUpdateText = $ref();
let filtRegionList = $computed(() => {
  if (regionUpdateText) {
    return store.regiondropdownlist.filter(
      (d) => d.text.toLowerCase().indexOf(regionUpdateText) > -1
    );
  }
  return store.regiondropdownlist;
});

let filtRegionListStart = (val, update, abort) => {
  update(() => {
    regionUpdateText = val.toLowerCase();
  });
};

/////////edit of region filter

//////////Start of Roles filter
let rolesPicked = $ref([]);
let rolesUpdateText = $ref();
let filtRolesList = $computed(() => {
  if (rolesUpdateText) {
    return store.roleListForWatchListSetting.filter(
      (d) => d.text.toLowerCase().indexOf(rolesUpdateText) > -1
    );
  }
  return store.roleListForWatchListSetting;
});

let filtRolesListStart = (val, update, abort) => {
  update(() => {
    rolesUpdateText = val.toLowerCase();
  });
};

/////////edit of Roles filter

//////////Start of Users filter
let usersPicked = $ref([]);
let usersUpdateText = $ref();
let filtUsersList = $computed(() => {
  if (usersUpdateText) {
    return store.userList.filter(
      (d) => d.name.toLowerCase().indexOf(usersUpdateText) > -1
    );
  }
  return store.userList;
});

let filtUsersListStart = (val, update, abort) => {
  update(() => {
    usersUpdateText = val.toLowerCase();
  });
};

/////////edit of Alliance filter

//////////Start of Users filter
let alliancePicked = $ref([]);
let allianceUpdateText = $ref();
let filtAllianceList = $computed(() => {
  if (allianceUpdateText) {
    return store.stationWatchListAllianceList.filter(
      (d) => d.text.toLowerCase().indexOf(allianceUpdateText) > -1
    );
  }
  return store.stationWatchListAllianceList;
});

let filtAllianceListStart = (val, update, abort) => {
  update(() => {
    allianceUpdateText = val.toLowerCase();
  });
};

/////////edit of Alliance filter

//////////Start of Type filter
let typePicked = $ref([]);
let typeUpdateText = $ref();
let filtTypeList = $computed(() => {
  if (typeUpdateText) {
    return store.stationWatchListItemList.filter(
      (d) => d.text.toLowerCase().indexOf(typeUpdateText) > -1
    );
  }
  return store.stationWatchListItemList;
});

let filtTypeListStart = (val, update, abort) => {
  update(() => {
    typeUpdateText = val.toLowerCase();
  });
};

/////////edit of Type filter

let submit = async () => {
  var data = {
    name: namePicked,
    description: descriptionPicked,
    station_id: stationsPicked,
    system_id: systemsPicked,
    constellation_id: constellationsPicked,
    region_id: regionsPicked,
    role_id: rolesPicked,
    user_id: usersPicked,
    alliance_id: alliancePicked,
    type_id: typePicked,
  };
  if (props.edit == 0) {
    await axios({
      method: "post",
      url: "/api/watchlist",
      withCredentials: true,
      data: data,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
  } else {
    await axios({
      method: "put",
      url: "/api/watchlist/" + props.list.id,
      withCredentials: true,
      data: data,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
  }
};

let close = () => {
  showAddEdit = false;
  namePicked = null;
  descriptionPicked = null;
  stationsPicked = [];
  systemsPicked = [];
  constellationsPicked = [];
  regionsPicked = [];
  rolesPicked = [];
  usersPicked = [];
};

let open = () => {
  if (props.edit == 1) {
    namePicked = props.list.name ?? null;
    descriptionPicked = props.list.description ?? null;
    stationsPicked = props.list.settings.station_id ?? [];
    systemsPicked = props.list.settings.system_id ?? [];
    constellationsPicked = props.list.settings.constellation_id ?? [];
    regionsPicked = props.list.settings.region_id ?? [];
    rolesPicked = props.list.settings.role_id ?? [];
    usersPicked = props.list.settings.user_id ?? [];
    alliancePicked = props.list.settings.alliance_id ?? [];
    typePicked = props.list.settings.type_id ?? [];
  }
};

let addLabel = $computed(() => {
  if (props.edit == 0) {
    return "Add";
  } else {
    return "Update";
  }
});
</script>

<style lang="scss"></style>
