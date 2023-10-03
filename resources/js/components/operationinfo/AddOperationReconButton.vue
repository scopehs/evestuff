<template>
  <div>
    <q-btn size="lg" padding="none" rounded icon="add" color="accent"
      ><q-menu class="myRound" @before-hide="close()" persistent>
        <q-card class="myRound">
          <q-card-section>
            <q-btn
              color="primary"
              icon="fa-solid fa-circle-info"
              label="How To add Cynos"
              rounded
              ><q-menu>
                <q-card flat>
                  <q-card-section class="text-webway">
                    If you have added a Cyno before you will find the characters in the
                    drop down list below. Just select it and press ADD<br />
                    <br />
                    <span class="text-bold"> Character not in the dropdown:</span><br />
                    If you own the character just enter the fullname in the dropdown list
                    and hit ADD.<br /><br />
                    <span class="text-bold"
                      >If the character is own by someone else:</span
                    >
                    <br />
                    Enter the fullname in the drop down list, then toggle the "Add
                    Main"<br />Once toggled a new dropdown should show, find the main
                    character in the dropdown list select it and press add<br />If you
                    cant find the name, that means the person has never logged into the
                    site.
                  </q-card-section>
                </q-card>
              </q-menu></q-btn
            ></q-card-section
          >
          <q-card-section>
            <q-select
              v-model="name"
              option-label="name"
              option-value="id"
              :options="cynoList"
              label="Cyno Character"
              @filter="filterFnCynosFinish"
              @filter-abort="abortFilterFn"
              new-value-mode="add-unique"
              outlined
              rounded
              input-style="max-width: 15px"
              dense
              :use-input="useInputCyno"
              @clear="name = ''"
              clearable
          /></q-card-section>
          <q-card-section>
            <q-toggle v-model="showMain" color="green" label="Add Main"
          /></q-card-section>
          <q-card-section>
            <q-slide-transition>
              <q-select
                v-if="showMain"
                v-model="mainName"
                option-label="name"
                option-value="id"
                :options="mainList"
                label="Main Character"
                @filter="filterFnMainFinish"
                @filter-abort="abortFilterFn"
                outlined
                rounded
                input-style="max-width: 15px"
                clearable
                use-input
                dense /></q-slide-transition
          ></q-card-section>

          <q-card-actions align="center">
            <q-btn
              color="positive"
              label="Submit"
              @click="addRecon()"
              rounded
              v-close-popup
            />
            <q-btn label="Close" rounded color="negative" v-close-popup />
          </q-card-actions>
        </q-card> </q-menu
    ></q-btn>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useQuasar } from "quasar";
let store = useMainStore();
let name = $ref("");
let mainName = $ref();
let showMain = $ref(false);
const $q = useQuasar();

let dropDown = $computed(() => {
  var data = store.operationInfoRecon.filter(
    (r) => r.operation_info_id != store.operationInfoPage.id
  );

  return data;
});

let dropDownMain = $computed(() => {
  return store.userList;
});

let cynoText = $ref();
let cynoList = $computed(() => {
  if (cynoText) {
    return dropDown.filter((d) => d.name.toLowerCase().indexOf(cynoText) > -1);
  }
  return dropDown;
});

let filterFnCynosFinish = (val, update, abort) => {
  update(() => {
    cynoText = val.toLowerCase();
    if (cynoList.length == 0) {
      name = cynoText;
    }
  });
};

let mainText = $ref();
let mainList = $computed(() => {
  if (mainText) {
    return dropDownMain.filter((d) => d.name.toLowerCase().indexOf(mainText) > -1);
  }
  return dropDownMain;
});

let filterFnMainFinish = (val, update, abort) => {
  update(() => {
    mainText = val.toLowerCase();
    if (mainList.length > 0 && val) {
      mainName = mainList[0];
    }
  });
};

let addRecon = async () => {
  if (type == "string") {
    var nameAdd = name;
  } else {
    var nameAdd = name.name;
  }

  if (showMain) {
    var mainID = mainName.id;
  } else {
    var mainID = store.user_id;
  }
  var request = {
    name: nameAdd,
    user_id: mainID,
    opID: store.operationInfoPage.id,
  };
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/operationinforecon",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((response) => {
    var code = response.status;
    if (code == 201) {
      var text = nameAdd + " not found";
      name = "";
      $q.notify({
        type: "negative",
        message: text,
      });
    } else {
      var text = nameAdd + " added";
      name = "";
      $q.notify({
        type: "positive",
        message: text,
      });
    }
  });
};

let close = () => {
  name = "";
  mainName = null;
  showMain = false;
};

let useInputCyno = $computed(() => {
  if (type == "object") {
    return false;
  } else {
    return true;
  }
});

let type = $computed(() => {
  return typeof name;
});

let abortFilterFn = () => {
};
</script>

<style lang="scss"></style>
