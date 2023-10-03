<template>
  <div>
    <q-card class="myRoundTop col-auto q-pb-xs" style="height: fit-content">
      <q-card-section class="bg-negative text-center q-py-sm">
        <span class="no-margin text-h6 q-ma-none">{{ fleet.name }}</span>
        <q-btn
          color="green"
          icon="fa-solid fa-edit"
          round
          size="xs"
          @click="showEditMenu = true"
        />
      </q-card-section>
      <q-card-section>
        <div class="column items-center">
          <span class="text-webway q-pa-none"> FC - {{ fcText }}</span>
          <span class="text-webway q-pa-none"> Boss - {{ bossText }} </span>
          <span class="text-webway q-pa-none"> Doctrine - {{ docText }} </span>
          <span class="text-webway q-pa-none"> Mumble - {{ mumbleText }} </span>
        </div>
      </q-card-section>
      <q-card-section class="text-center q-py-none">
        <div class="column items-center">
          <q-btn
            class="col"
            color="primary"
            no-caps
            style="width: 50%"
            rounded=""
            label="Recon"
            @click="showReconEdit = true"
          />
          <span class="text-webway" v-for="(recon, index) in fleet.recons" :key="index">
            {{ recon.fleet_role.name }} - {{ recon.name }}
            <span v-if="recon.system"> - {{ recon.system.system_name }} </span>

            <q-tooltip :delay="500">
              <span>Main - {{ recon.main.name }}</span>
              <span v-if="recon.system">
                <br />
                System - {{ recon.system.system_name }}
              </span>
            </q-tooltip>
          </span>
        </div>
      </q-card-section>
    </q-card>

    <q-dialog @before-show="openEdit()" v-model="showEditMenu" persistent>
      <q-card class="myRoundTop col-auto" style="min-height: 300px; width: 500px">
        <q-card-section class="bg-primary text-center q-py-sm">
          <span class="no-margin text-h6 q-ma-none">Edit Fleet - {{ fleet.name }}</span
          ><q-btn
            color="warning"
            flat
            icon="fa-solid fa-trash-can"
            @click="deleteFleet()"
            v-close-popup
        /></q-card-section>
        <q-card-section>
          <div class="column q-gutter-lg">
            <q-input
              dense
              outlined
              rounded
              v-model="editFleetName"
              type="text"
              label="Fleet Name"
            />
            <q-select
              v-model="editFC"
              dense
              outlined
              rounded
              use-input
              new-value-mode="add-unique"
              option-label="name"
              clearable
              option-value="id"
              :options="fcFindList"
              label="FC"
              @filter="filterFnFcFinish"
              @filter-abort="abortFilterFn"
            />
            <q-select
              v-model="editBoss"
              dense
              outlined
              rounded
              option-label="name"
              clearable
              option-value="id"
              use-input
              :options="bossFindList"
              label="Boss"
              @filter="filterFnBossFinish"
              @filter-abort="abortFilterFn"
            />

            <q-select
              v-model="editDoctrine"
              dense
              outlined
              option-label="name"
              clearable
              option-value="id"
              use-input
              :options="doctrineFindList"
              label="Doctrine"
              @filter="filterFnDoctrineFinish"
              @filter-abort="abortFilterFn"
              rounded
            />

            <q-select
              v-model="editMumble"
              dense
              outlined
              option-label="name"
              clearable
              option-value="id"
              :options="mumbleFindList"
              label="Mumble"
              use-input
              rounded
              @filter="filterFnMumbleFinish"
              @filter-abort="abortFilterFn"
            />
          </div>
        </q-card-section>
        <q-card-actions align="around">
          <q-btn
            rounded
            color="positive"
            v-close-popup
            label="Update"
            :disable="!editFleetName"
            @click="update()"
          />
          <q-btn rounded color="negative" v-close-popup label="Close" />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="showReconEdit" persistent @before-hide="closeRecon()">
      <q-card class="myRoundTop col-auto" style="width: 500px">
        <q-card-section class="bg-primary text-center q-py-sm">
          <span class="no-margin text-h6 q-ma-none"
            >Edit Recon for {{ fleet.name }}</span
          ></q-card-section
        >
        <q-card-section>
          <div class="row full-width items-center">
            <div class="col-5">
              <q-select
                v-model="pickedRecon"
                dense
                option-label="name"
                clearable
                option-value="id"
                :options="reconFindList"
                label="Recon"
                outlined
                rounded
                @filter="filterFnReconFinish"
                @filter-abort="abortFilterFn"
                use-input
              />
            </div>
            <div class="col-5">
              <q-select
                v-model="pickedRole"
                dense
                option-label="name"
                clearable
                option-value="id"
                :options="store.operationInfoReconFleetRoleList"
                label="Role"
                outlined
                rounded
              />
            </div>
            <div class="col-2 q-pl-xs">
              <q-btn
                dense
                color="positive"
                rounded
                label="Add"
                @click="addRecon()"
                :disable="!pickedRecon || !pickedRole"
              />
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <div class="column">
            <span class="text-webway" v-for="(recon, index) in fleet.recons" :key="index">
              {{ recon.name }} - {{ recon.fleet_role.name }}
              <q-btn flat round size="xs" color="secondary" icon="fa-solid fa-edit"
                ><q-menu>
                  <q-list style="min-width: 100px">
                    <q-item
                      clickable
                      @click="editReconRole(recon, role)"
                      v-close-popup
                      v-for="(role, index) in store.operationInfoReconFleetRoleList"
                      :key="index"
                    >
                      <q-item-section>{{ role.name }}</q-item-section>
                    </q-item>
                    <q-separator />
                  </q-list> </q-menu
              ></q-btn>
              <q-btn
                flat
                round
                size="xs"
                color="negative"
                icon="fa-solid fa-trash-can"
                @click="removeReconFromFleet(recon)"
              />
            </span>
          </div>
        </q-card-section>
        <q-card-actions align="center">
          <q-btn rounded color="negative" v-close-popup label="Close" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import axios from "axios";
let store = useMainStore();
const props = defineProps({
  fleet: [Array, Object],
});

let showEditMenu = $ref(false);
let showReconEdit = $ref(false);
let editFC = $ref();
let editBoss = $ref();
let editDoctrine = $ref();
let editMumble = $ref();
let editFleetName = $ref();

let pickedRecon = $ref();
let pickedRole = $ref();
let pickedNewRole = $ref();

let openEdit = () => {
  editFC = props.fleet.fc;
  editBoss = props.fleet.boss;
  editDoctrine = props.fleet.doctrine;
  editMumble = props.fleet.mumble;
  editFleetName = props.fleet.name;
};

let update = () => {
  var fcName = null;
  var bossName = null;
  if (typeFC == "string") {
    fcName = editFC;
  } else {
    if (editFC) {
      fcName = editFC.name;
    }
  }

  if (typeBoss == "string") {
    bossName = editBoss;
  } else {
    if (editBoss) {
      bossName = editBoss.name;
    }
  }

  var data = {
    fleet_name: editFleetName,
    fc_name: fcName,
    boss_name: bossName,
    doctrine_id: editDoctrine,
    mumble_id: editMumble,
    opID: store.operationInfoPage.id,
  };

  axios({
    method: "put",
    url: "/api/operationinfo/fleet/update/" + props.fleet.id,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let deleteFleet = () => {
  axios({
    method: "delete",
    url: "/api/operationinfofleet/" + props.fleet.id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let fcText = $computed(() => {
  if (props.fleet.fc_id) {
    return props.fleet.fc.name;
  }
  return "None";
});

let bossText = $computed(() => {
  if (props.fleet.boss_id) {
    return props.fleet.boss.name;
  }
  return "None";
});

let docText = $computed(() => {
  if (props.fleet.doctrine_id) {
    return props.fleet.doctrine.name;
  }
  return "None";
});

let mumbleText = $computed(() => {
  if (props.fleet.mumble_id) {
    return props.fleet.mumble.name;
  }
  return "None";
});

let typeFC = $computed(() => {
  return typeof editFC;
});

let typeBoss = $computed(() => {
  return typeof editBoss;
});

let reconDropDown = $computed(() => {
  return store.operationInfoRecon.filter(
    (r) =>
      r.operation_info_id == store.operationInfoPage.id &&
      r.operation_info_fleet_id == null
  );
});

let closeRecon = () => {
  pickedRecon = null;
  pickedRole = null;
};

let addRecon = () => {
  var data = {
    reconID: pickedRecon.id,
    role_id: pickedRole.id,
    opID: store.operationInfoPage.id,
  };

  axios({
    method: "post",
    url: "/api/operationinfofleetrecon/" + props.fleet.id,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((response) => {
    pickedRecon = null;
    pickedRole = null;
  });
};

let removeReconFromFleet = (item) => {
  var data = item;
  axios({
    method: "post",
    url: "/api/operationinfofleetreconremove/" + item.operation_info_id,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let editReconRole = (recon, role) => {
  var data = {
    reconID: recon.id,
    role_id: role.id,
    opID: store.operationInfoPage.id,
  };

  axios({
    method: "post",
    url: "/api/operationinfofleetrecon/" + props.fleet.id,
    withCredentials: true,
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let abortFilterFn = () => {};

let editFCText = $ref();
let fcFindList = $computed(() => {
  if (editFCText) {
    return store.operationInfoUsers.filter(
      (d) => d.name.toLowerCase().indexOf(editFCText) > -1
    );
  }
  return store.operationInfoUsers;
});

let filterFnFcFinish = (val, update, abort) => {
  update(() => {
    editFCText = val.toLowerCase();
    if (fcFindList.length > 0 && val) {
      editFC = fcFindList[0];
    }
  });
};

let editBossText = $ref();
let bossFindList = $computed(() => {
  if (editBossText) {
    return store.operationInfoUsers.filter(
      (d) => d.name.toLowerCase().indexOf(editBossText) > -1
    );
  }
  return store.operationInfoUsers;
});

let filterFnBossFinish = (val, update, abort) => {
  update(() => {
    editBossText = val.toLowerCase();
    if (bossFindList.length > 0 && val) {
      editBoss = bossFindList[0];
    }
  });
};

let editDoctrineText = $ref();
let doctrineFindList = $computed(() => {
  if (editDoctrineText) {
    return store.operationInfoDoctrines.filter(
      (d) => d.name.toLowerCase().indexOf(editDoctrineText) > -1
    );
  }
  return store.operationInfoDoctrines;
});

let filterFnDoctrineFinish = (val, update, abort) => {
  update(() => {
    editDoctrineText = val.toLowerCase();
    if (doctrineFindList.length > 0 && val) {
      editDoctrine = doctrineFindList[0];
    }
  });
};

let editMumbleText = $ref();
let mumbleFindList = $computed(() => {
  if (editMumbleText) {
    return store.operationInfoMumble.filter(
      (d) => d.name.toLowerCase().indexOf(editMumbleText) > -1
    );
  }
  return store.operationInfoMumble;
});

let filterFnMumbleFinish = (val, update, abort) => {
  update(() => {
    editMumbleText = val.toLowerCase();
    if (mumbleFindList.length > 0 && val) {
      editMumble = mumbleFindList[0];
    }
  });
};

let editReconText = $ref();
let reconFindList = $computed(() => {
  if (editReconText) {
    return reconDropDown.filter((d) => d.name.toLowerCase().indexOf(editReconText) > -1);
  }
  return reconDropDown;
});

let filterFnReconFinish = (val, update, abort) => {
  update(() => {
    editReconText = val.toLowerCase();
    if (reconFindList.length > 0 && val) {
      pickedRecon = reconFindList[0];
    }
  });
};
</script>

<style lang="scss"></style>
