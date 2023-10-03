import {
    createWebHistory,
    createRouter
} from "vue-router";
import pinia from "@/store.js";
import {
    useMainStore
} from "@/store/useMain";
// import Timers from "./views/Timers.vue";
// import Notifications from "./views/Notifications.vue";
// import Campagins from "./views/Campaigns.vue";
// import Campaign from "./views/CampaignSystem.vue";
// import MultiCampaign from "./views/MultiCampaignSystem.vue";
// import Stest from "./components/campaign/SystemTable.vue";
// import AdminPanel from "./views/AdminPanel.vue";
// import StationsRedirect from "./views/redirect/StationsRedirect.vue";
// import Structures from "./views/structure.vue"
// import Towers from "./views/Towers.vue";
// import store from "./store";
// import FeedBack from "./views/FeedBack.vue";
// import campaginSystemKick from "./views/redirect/campaginSystemKick.vue";
// import MultiCampagins from "./views/CustomCampaigns.vue";
// import campaignFinished from "./views/redirect/campaignOver.vue";
// import chillstations from "./views/ChillTimers.vue";
// import welptimers from "./views/WelpViolenceTimers.vue";
// import Gsol from "./views/Gsol.vue";
// import Recon from "./views/Recon.vue";
// import StartCampaign from "./views/StartCampaignSystem.vue";
// import KillList from "./views/RcSheet.vue";
// import RCMOVETIMER from "./views/RCMove.vue";
// import FleetKeys from "./views/FleetKeyPannel.vue";
// import CoordSheet from "./views/CoordSheet.vue";
// import SoloOperations from "./views/SoloOperations.vue";
// import Station from "./views/StationSheet.vue";
// import NewOperation from "./views/NewCampaign.vue";
// import OperationInfo from "./views/OperationInfo.vue";
// import OperationInfoPage from "./views/OperationInfoPage.vue";
// import OperationInfoOver from "./views/redirect/operationInfoOver.vue";

const routes = [{
    path: "/",
    name: "default",
    component: () => import("./views/SoloOperations.vue"),
},

{
    path: "/operations",
    name: "operations",
    component: () => import("./views/SoloOperations.vue"),
},


{
    path: "/dscan/:link/:tab",
    alias: ['/dscan', '/dscan/:link'],
    name: "dscan",
    component: () => import("./views/Dscan.vue"),

},


{
    path: "/staging",
    name: "staging",
    component: () => import("./views/StagingSystems.vue"),
    beforeEnter(to, from, next) {
        if (can("view_staging_page")) {
            next();
        } else {
            next("/");
        }
    },
},


// {
//     path: "/dscan/:id",
//     name: "testingpage",
//     component: () => import("./views/test.vue"),
// },



{
    path: "/windows",
    name: "windows",
    component: () => import("./views/Timers.vue"),
},

{
    path: "/notifications",
    name: "notifications",
    component: () => import("./views/Notifications.vue"),
    beforeEnter(to, from, next) {
        if (can("edit_notifications")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/mcampaigns",
    name: "mcampaigns",
    component: () => import("./views/CustomCampaigns.vue"),
    beforeEnter(to, from, next) {
        if (can("access_multi_campaigns")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/stations",
    name: "stations",
    component: () => import("./views/StationSheet.vue"),
},


{
    path: "/stationwatchlist/settings",
    name: "stationWatchListSettings",
    component: () => import("./views/StationWatchListSetupPage.vue"),
    beforeEnter(to, from, next) {
        if (can("view_station_watch_list_setup")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/addtimer",
    name: "timerstomove",
    component: () => import("./views/RCMove.vue"),
    beforeEnter(to, from, next) {
        if (can("view_move_timers")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/scampaign/:id",
    name: "scampaign",
    component: () => import("./views/StartCampaignSystem.vue"),

},


// {
//     path: "/scampaign/:id",
//     name: "scampaign",
//     component: StartCampaign,
//     props: (route) => {
//         const id = Number.parseInt(route.params.id, 10);
//         if (Number.isNaN(id)) {
//             return 0;
//         }
//         return { id };
//     },

{
    path: "/towers",
    name: "towers",
    component: () => import("./views/Towers.vue"),
    beforeEnter(to, from, next) {
        if (can("view_towers")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/feedback",
    name: "feedback",
    component: () => import("./views/FeedBacknew.vue"),
    beforeEnter(to, from, next) {
        if (can("super")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/pannel",
    name: "pannel",
    component: () => import("./views/AdminPanel.vue"),
    beforeEnter(to, from, next) {
        if (can("edit_users")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/affilations",
    name: "affilations",
    component: () => import("./views/Affilations.vue"),
    beforeEnter(to, from, next) {
        if (can("view_affiliation_page")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/operationinfo",
    name: "operationinfo",
    component: () => import("./views/OperationInfo.vue"),
    beforeEnter(to, from, next) {
        if (can("view_opertaion_info")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/operationinfo/:link",
    name: "operationinfopage",
    component: () => import("./views/OperationInfoPage.vue"),
    beforeEnter(to, from, next) {
        if (can("view_opertaion_info")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/operationinfoover",
    name: "operationInfoOver",
    component: () => import("./views/redirect/operationInfoOver.vue"),
    beforeEnter(to, from, next) {
        if (can("view_opertaion_info")) {
            next();
        } else {
            next("/");
        }
    },
},

{
    path: "/dscanisnomore",
    name: "dscanredirect",
    component: () => import("./views/redirect/dscanremoved.vue"),

},

{
    path: "/op/:id/:system?",
    name: "op",
    component: () => import("./views/NewCampaign.vue"),
    props: (route) => {
        const id = route.params.id;
        const routeSystem = route.params.system ? route.params.system : null;
        return {
            id,
            routeSystem
        };
    },
},


];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to) => {
    const store = useMainStore(pinia);
    await store.count;
});

function can(value) {
    if (window.Laravel.jsPermissions == 0) {
        return false;
    }
    let permissions = window.Laravel.jsPermissions.permissions;
    let _return = false;
    if (!Array.isArray(permissions)) {
        return false;
    }
    if (value.includes("|")) {
        value.split("|").forEach(function (item) {
            if (permissions.includes(item.trim())) {
                _return = true;
            }
        });
    } else if (value.includes("&")) {
        _return = true;
        value.split("&").forEach(function (item) {
            if (!permissions.includes(item.trim())) {
                _return = false;
            }
        });
    } else {
        _return = permissions.includes(value.trim());
    }
    return _return;
}

export default router;

// Vue.use(Router);

// export default new Router({
//     mode: "history",
//     routes: [
// {
//
// },
// {
//     path: "/",
//     name: "default",
//     component: Vtest,
// },
// {
//     path: "/campaign/:id",
//     name: "campaign",
//     component: Campaign,
//     props: (route) => {
//         const id = route.params.id;
//         return { id };
//     },
//     //   beforeEnter(to, from, next) {

//     //     // console.log(Permissions.indexOf('access_campaigns' )!== -1)
//     //     if(Permissions.indexOf('campaign ' )!== -1){
//     //         next()
//     //     }else{
//     //        next("/redirect/campagin")
//     //     }

//     //   }
// },

// {
//     path: "/op/:id/:system?",
//     name: "op",
//     component: NewOperation,
//     props: (route) => {
//         const id = route.params.id;
//         const routeSystem = route.params.system ?? null;
//         return { id, routeSystem };
//     },
// },

// {
//     path: "/mcampaign/:id/:name",
//     name: "mcampaign",
//     component: MultiCampaign,
//     props: (route) => {
//         const id = Number.parseInt(route.params.id, 10);
//         if (Number.isNaN(id)) {
//             return 0;
//         }
//         return { id };
//     },
//     //   beforeEnter(to, from, next) {

//     //     // console.log(Permissions.indexOf('access_campaigns' )!== -1)
//     //     if(Permissions.indexOf('campaign ' )!== -1){
//     //         next()
//     //     }else{
//     //        next("/redirect/campagin")
//     //     }

//     //   }
// },

// {
//     path: "/scampaign/:id",
//     name: "scampaign",
//     component: StartCampaign,
//     props: (route) => {
//         const id = Number.parseInt(route.params.id, 10);
//         if (Number.isNaN(id)) {
//             return 0;
//         }
//         return { id };
//     },
//     //   beforeEnter(to, from, next) {

//     //     // console.log(Permissions.indexOf('access_campaigns' )!== -1)
//     //     if(Permissions.indexOf('campaign ' )!== -1){
//     //         next()
//     //     }else{
//     //        next("/redirect/campagin")
//     //     }

//     //   }
// },

// {
//     path: "/timers",
//     name: "timers",
//     component: Timers,
// },

// {
//     path: "/structures",
//     name: "structures",
//     component: Structures,
//       beforeEnter(to, from, next) {
//         if(Permissions.indexOf('gunner' )!== -1 || Permissions.indexOf('edit_notifications' )!== -1){
//             next()
//         }else{
//            next("/redirect/structures")
//         }

//       }
// },

// {
//     path: "/campaignkick",
//     name: "campaginSystemKick",
//     component: campaginSystemKick,
// },

// {
//     path: "/operations",
//     name: "operations",
//     component: SoloOperations,
//     alias: "/campaigns",
// },

// {
//     path: "/coordsheet",
//     name: "coordsheet",
//     component: CoordSheet,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_coord_sheet") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/operationinfo",
//     name: "operationinfo",
//     component: OperationInfo,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_opertaion_info") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/operationinfo/:link",
//     name: "operationinfopage",
//     component: OperationInfoPage,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_opertaion_info") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/operationinfoover",
//     name: "operationInfoOver",
//     component: OperationInfoOver,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_opertaion_info") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/fleetkeys",
//     name: "fleetkeys",
//     component: FleetKeys,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_fleet_key") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/stationtimers",
//     name: "killlist",
//     component: KillList,
//     alias: "/fornatshealth",
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_killsheet") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/fornatshealth",
//     name: "killlist",
//     component: KillList,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_killsheet") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/gsol",
//     name: "gsol",
//     component: Gsol,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_gsol") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {

// {
//     path: "/stest",
//     name: "stest",
//     component: Stest,
// },

// {
//     path: "/redirect/stations",
//     name: "campagin_redirect",
//     component: StationsRedirect,
// },

// {
//     path: "/campaignfinished",
//     name: "campaignfinished",
//     component: campaignFinished,
// },

// {
//     path: "/dance",
//     name: "test",
//     component: Vtest,
// },

// {
//     path: "/pannel",
//     name: "pannel",
//     component: AdminPanel,
//     async beforeEnter(to, from, next) {
//         if (Permissions.indexOf("edit_users") !== -1) {
//             await store.dispatch("getUsers");
//             await store.dispatch("getRoles");
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/notifications",
//     name: "notifications",
//     component: Notifications,
// },

// {
//     path: "/campaigns",
//     name: "campaigns",
//     component: Campagins,
// },

// {
//     path: "/chillstations",
//     name: "chillstations",
//     component: chillstations,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_chill_timers") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/welpviolence",
//     name: "welpviolence",
//     component: welptimers,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_welp_timers") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/recon",
//     name: "recon",
//     component: Recon,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_recon") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },

// {
//     path: "/stations",
//     name: "stations",
//     component: Station,
//     beforeEnter(to, from, next) {
//         if (Permissions.indexOf("view_station_list") !== -1) {
//             next();
//         } else {
//             next("/notifications");
//         }
//     },
// },
// ],
// scrollBehavior(to, from, savedPosition) {
//   return { x: 0, y: 0 }
// },
// });
