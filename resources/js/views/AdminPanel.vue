<template>
  <div class="q-ma-md">
    <div class="row justify-between">
      <div class="col-8">
        <q-table
          title="Connections"
          class="myTableUsers myRound bg-webBack"
          :rows="filterEnd"
          :columns="columns"
          table-class=" text-webway"
          table-header-class=" text-weight-bolder"
          row-key="id"
          dark
          dense
          :filter="search"
          ref="tableRef"
          rounded
          :pagination="pagination"
        >
          <template v-slot:top="props">
            <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
              <div class="col-12 flex flex-center">
                <span class="text-h4">Users</span>
              </div>
            </div>
            <div class="row full-width q-pt-md justify-between">
              <div class="col-auto">
                <div class="row q-gutter-sm q-pl-md">
                  <q-input
                    rounded
                    standout
                    dense
                    debounce="300"
                    v-model="search"
                    clearable
                    placeholder="Search"
                  >
                    <template v-slot:append>
                      <q-icon name="fa-solid fa-magnifying-glass" />
                    </template>
                  </q-input>
                </div>
              </div>
            </div>
          </template>

          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="name" :props="props"> {{ props.row.name }} </q-td>
              <q-td key="roles" :props="props">
                <q-chip
                  v-for="(role, index) in filterList(props.row.roles)"
                  :key="index"
                  :removable="can('super')"
                  @remove="userRemoveRole(props.row.id, role.id)"
                  color="primary"
                  :label="role.name"
                />
                <q-btn
                  v-if="can('super')"
                  round
                  color="green"
                  flat
                  icon="fa-solid fa-plus"
                  ><q-menu>
                    <q-list style="min-width: 100px">
                      <q-item
                        v-for="(role, index) in store.rolesList"
                        :key="index"
                        clickable
                        v-close-popup
                      >
                        <q-item-section @click="userAddRole(role.value, props.row.id)">{{
                          role.text
                        }}</q-item-section>
                      </q-item>
                    </q-list>
                  </q-menu></q-btn
                >
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
      <div class="col-3">
        <q-card class="myRoundTop">
          <q-card-section class="bg-primary text-h5 text-center">
            <h4 class="no-margin">Filter By Roles</h4>
          </q-card-section>
          <q-card-section>
            <q-chip
              v-for="(role, index) in store.rolesList"
              :key="index"
              color="primary"
              :outline="!role.selected ? true : false"
              v-model:selected="role.selected"
              :label="role.text"
            />
          </q-card-section>
          <q-slide-transition>
            <q-card-section v-if="selectedCount">
              <q-btn
                color="primary"
                label="Clear"
                @click="clearSelected()"
              /> </q-card-section
          ></q-slide-transition>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

let store = useMainStore();
let can = inject("can");
let search = $ref();

onMounted(async () => {
  Echo.private("userupdate").listen("UserUpdate", (e) => {
    this.refresh;
  });
  if (can("view_admin_logs")) {
    await store.getLoggingAdmin();
  }

  await store.getUsers();
  await store.getRoles();
});

onBeforeUnmount(async () => {
  Echo.leave("userupdate");
});

let pagination = $ref({
  sortBy: "name",
  descending: false,
  page: 1,
  rowsPerPage: 50,
});

let selectedCount = $computed(() => {
  let countSelected = 0;
  for (let i = 0; i < store.rolesList.length; i++) {
    if (store.rolesList[i].selected === true) {
      countSelected++;
    }
  }
  return countSelected;
});

let clearSelected = () => {
  for (let i = 0; i < store.rolesList.length; i++) {
    store.rolesList[i].selected = false;
  }
};

let selectedRoles = $computed(() => {
  return store.rolesList.filter((r) => r.selected == true);
});

let filterEnd = $computed(() => {
  if (selectedCount) {
    const filteredUsers = store.users.filter((user) => {
      const userRoles = user.roles.map((role) => role.id);
      return store.rolesList.some(
        (role) => role.selected && userRoles.includes(role.value)
      );
    });
    return filteredUsers;
  } else {
    return store.users;
  }
});

let userRemoveRole = async (item, role) => {
  var request = {
    roleId: role,
    userId: item,
  };
  await axios({
    method: "put", //you can set what request you want to be
    url: "/api/rolesremove",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  store.getUsers();
};

let userAddRole = async (role, item) => {
  var request = {
    roleId: role,
    userId: item,
  };

  await axios({
    method: "put", //you can set what request you want to be
    url: "/api/rolesadd",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  store.getUsers();
};

let columns = $ref([
  {
    name: "name",
    align: "left",
    required: false,
    label: "Name",
    classes: "text-no-wrap",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "roles",
    required: true,
    align: "center",
    label: "Roles",
    classes: "text-no-wrap",
    style: "width: 80%",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
]);

let filterList = (roles) => {
  return roles.filter((r) => r.name != "Super Admin");
};
let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableUsers
  /* height or max-height is important */
  height: v-bind(h)

  .q-table__top
    padding-top: 0 !important
    padding-left: 0 !important
    padding-right: 0 !important



  .q-table__bottom,
  thead tr:first-child th
    /* bg color is important for th; just specify one */
    background-color: #202020

  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0

  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>

<style lang="sass" scoped>
.my-custom-toggle
  border: 1px solid #027be3
</style>
