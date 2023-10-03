import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        operationInfo: [],
        operationInfoSetting: {
            showReconTable: true,
            showSystemTable: true,
            showTickList: true,
            showFleets: true,
        },

        // operationInfoPage: [],

        // operationInfoUsers: [],
        // operationInfoMumble: [],
        // operationInfoDoctrines: [],
        // operationInfoRecon: [],
        // operationInfoReconFleetRoleList: [],
        // operationInfoOperationList: [],
        // operationInfoJamList: [],

        operationInfoMessageCount: 0,

        allianceticklist: [],
        ammoRequest: [],
        campaigns: [],
        campaignsRegion: [],
        campaignJoin: [],
        campaignSolaSystems: [],
        campaignslist: [],
        campaignusers: [],
        campaignsystems: [],
        campaignmembers: [],
        chillstations: [],
        chillsheetRegion: [],
        chillsheetItem: [],
        chillsheetStatus: [],
        // constellationlist: [],
        cores: [],
        delveLink: "",
        eveUserCount: 0,
        fleets: [],
        items: [],
        keysList: [],
        keyfleets: [],
        loggingAdmin: [],
        loggingcampaign: [],
        loggingRcSheet: [],
        loggingStations: [],
        loggingNewCampaign: [],
        missingCorpID: 0,
        missingCorpTick: "",
        moonlist: [],
        multicampaigns: [],
        nodeJoin: [],
        notifications: [],
        periodbasisLink: "",
        queriousLink: "",
        rcfcs: [],
        rcsheetRegion: [],
        rcsheetItem: [],
        rcsheetStatus: [],
        coordsheetRegion: [],
        coordsheetItem: [],
        coordsheetStatus: [],
        rcstations: [],
        recontasksystems: [],
        rolesList: [],
        startcampaigns: [],
        startcampaignJoin: [],
        startcampaignsystems: [],
        stations: [],
        stationFits: [],
        structurelist: [],
        // systemlist: [],
        // ticklist: [],
        // timers: [],
        // timersRegions: [],
        tooltipToggle: true,
        towers: [],
        towerlist: [],
        users: [],
        user_id: 0,
        user_name: "",
        userschars: [],
        userkeys: [],
        welpstations: [],
        welpsheetRegion: [],
        welpsheetItem: [],
        welpsheetStatus: [],
        // webwayStartSystems: [],
        // newSoloOperations: [],
        // newSoloOperationsRegionList: [],
        // newSoloOperationsConstellationList: [],

        // stationList: [],

        // stationListPullRegions: [],
        // stationListFCRegions: [],
        // stationListRegionList: [],

        webwaySelectedStartSystem: {
            value: 30004759,
            text: "1DQ1-A",
        },

        newOperationInfo: [],
        // newCampaigns: [],
        // opUsers: [],
        // ownChars: {},
        // operationUserList: [],
        // newCampaignSystems: [],
        // newCampaignsList: [],
        newOperationList: [],
        newOperationMessageOverlay: 0,
        setOpenOperationAddChar: false,
        userList: [],

        operationInfoUserList: [],
    },
    mutations: {
        DELETE_OP_CHAR_FROM_OWN_LIST(state, id) {
            let check = state.ownChars.filter((e) => e.id == id).length;
            if (check > 0) {
                state.ownChars = state.ownChars.filter((e) => e.id != id);
            }
        },

        // DELETE_OP_CHAR_FROM_CHAR_LIST(state, id) {
        //     let check = state.opUsers.filter((e) => e.id == id).length;
        //     if (check > 0) {
        //         state.opUsers = state.opUsers.filter((e) => e.id != id);
        //     }
        // },

        REMOVE_OPERATION_PAGE_INFO(state, id) {
            let check = state.operationInfo.filter((e) => e.id == id).length;
            if (check > 0) {
                state.operationInfo = state.operationInfo.filter(
                    (e) => e.id != id
                );
            } else {
                state.operationInfo = [];
            }
        },

        // CLEAR_OPERATION_INFO_SOLO(state) {
        //     state.operationInfoPage = [];
        //     state.operationInfoUsers = [];
        //     state.operationInfoMumble = [];
        //     state.operationInfoDoctrines = [];
        //     state.operationInfoRecon = [];
        //     state.operationInfoReconFleetRoleList = [];
        //     state.operationInfoOperationList = [];
        //     state.operationInfoJamList = [];
        // },

        // DELETE_OPERATION_SHEET_INFO_PAGE_FLEET(state, id) {
        //     let check = state.operationInfoPage.fleets.filter(
        //         (e) => e.id == id
        //     ).length;
        //     if (check > 0) {
        //         state.operationInfoPage.fleets =
        //             state.operationInfoPage.fleets.filter((e) => e.id != id);
        //     }
        // },

        // UPDATE_OWN_CHAR(state, data) {
        //     const item = state.ownChars.find((item) => item.id === data.id);
        //     const count = state.ownChars.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     } else {
        //         state.ownChars.push(data);
        //     }
        // },

        // UPDATE_NEW_CAMPAIGNS(state, data) {
        //     const item = state.newCampaigns.find((item) => item.id === data.id);
        //     const count = state.newCampaigns.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     } else {
        //         state.newCampaigns.push(data);
        //     }
        // },

        // UPDATE_OPERATION_USER_LIST(state, data) {
        //     state.operationUserList = data;
        // },

        // UPDATE_OPERATION_INFO_USER_LIST(state, data) {
        //     state.operationInfoUserList = data;
        // },

        // UPDATE_OPERATION_INFO_UPDATE_ALL_FLEETS(state, data) {
        //     state.operationInfoPage.fleets = data;
        // },

        // UPDATE_OPERATION_INFO_ADD_DANK_OP(state, data) {
        //     state.operationInfoPage.dankop = data;
        // },

        // UPDATE_OP_CHAR(state, data) {
        //     const item = state.opUsers.find((item) => item.id === data.id);
        //     const count = state.opUsers.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     } else {
        //         state.opUsers.push(data);
        //     }
        // },

        ADD_NEW_OP_CHAR(state, data) {
            state.opUsers.push(data);
        },

        // SET_STATION_LIST(state, stations) {
        //     state.stationList = stations;
        // },

        // SET_USER_LIST(state, data) {
        //     state.userList = data;
        // },

        // SET_OPERATION_INFO(state, data) {
        //     state.operationInfo = data;
        // },

        // SET_OPERATION_INFO_OPERATION_LIST(state, operations) {
        //     state.operationInfoOperationList = operations;
        // },

        // SET_OPERATION_INFO_PAGE(state, data) {
        //     state.operationInfoPage = data;
        // },

        // UPDATE_OPERATION_INFO_PAGE(state, data) {
        //     const item = state.operationInfoPage;
        //     Object.assign(item, data);
        // },

        // SET_OPERATION_INFO_USERS(state, data) {
        //     state.operationInfoUsers = data;
        // },

        // SET_OPERATION_INFO_RECON(state, data) {
        //     state.operationInfoRecon = data;
        // },

        // SET_OPERATION_INFO_MUMBLE(state, data) {
        //     state.operationInfoMumble = data;
        // },

        // SET_OPERATION_INFO_JAM_LIST(state, data) {
        //     state.operationInfoJamList = data;
        // },

        // SET_OPERATION_INFO_RECON_FLEET_ROLE_LIST(state, data) {
        //     state.operationInfoReconFleetRoleList = data;
        // },

        // SET_OPERATION_INFO_DOCTRINES(state, data) {
        //     state.operationInfoDoctrines = data;
        // },

        // SET_NEW_OPERATION_MESSAGE_OVERLAY(state, num) {
        //     state.newOperationMessageOverlay = num;
        // },

        NEW_OPEN_OPERATION_ADD_CHAR(state, num) {
            state.setOpenOperationAddChar = num;
        },

        // SET_NEW_OPERATION_READ_ONLY(state, num) {
        //     state.newOperationInfo.read_only = num;
        // },

        // SET_OPERATION_PAGE(state, data) {
        //     state.newOperationInfo = data.data;
        //     state.newCampaignSystems = data.systems;
        //     state.opUsers = data.opUsers;
        //     state.ownChars = data.ownChars;
        //     state.newCampaigns = data.data.campaign;
        //     state.operationUserList = data.userList;
        // },

        // UPDATE_OPERATION_SYSTEMS_ALL(state, data) {
        //     state.newCampaignSystems = data;
        // },

        // UPDATE_OPERATION_PAGE(state, data) {
        //     state.newOperationInfo = data;
        // },

        // UPDATE_CAMPAIGN_SYSTEMS(state, data) {
        //     const item = state.newCampaignSystems.find(
        //         (item) => item.id === data.id
        //     );
        //     const count = state.newCampaignSystems.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     } else {
        //         state.newCampaignSystems.push(data);
        //     }
        // },

        // UPDATE_CAMPAIGN_SYSTEMS_INFO(state, data) {
        //     const item = state.operationInfoPage.systems.find(
        //         (item) => item.id === data.id
        //     );
        //     const count = state.operationInfoPage.systems.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     } else {
        //         state.operationInfoPage.systems.push(data);
        //     }
        // },

        // UPDATE_OPERATION_PAGE_INFO(state, data) {
        //     const item = state.operationInfo.find(
        //         (item) => item.id === data.id
        //     );
        //     const count = state.operationInfo.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     } else {
        //         state.operationInfo.push(data);
        //     }
        // },

        SET_WEBWAY_SELECTED_START_SYSTEM(state, data) {
            state.webwaySelectedStartSystem = data;
        },

        // SET_WEBWAY_START_SYSTEMS(state, systems) {
        //     state.webwayStartSystems = systems;
        // },

        // SET_STATION_REGION_LIST(state, regionlist) {
        //     state.stationListRegionList = regionlist;
        // },

        // SET_STATION_PULL_REGIONS(state, pull) {
        //     state.stationListPullRegions = pull;
        // },

        // SET_STATION_LIST_FC(state, fcs) {
        //     state.stationListFCRegions = fcs;
        // },

        // UPDATE_STATION_LIST(state, data) {
        //     const item = state.stationList.find((item) => item.id === data.id);
        //     const count = state.stationList.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     } else {
        //         state.stationList.push(data);
        //     }
        // },

        // UPDATE_FLEET_INFO(state, data) {
        //     const count = state.operationInfoPage.fleets.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         const item = state.operationInfoPage.fleets.find(
        //             (f) => f.id === data.id
        //         );
        //         Object.assign(item, data);
        //     } else {
        //         state.operationInfoPage.fleets.push(data);
        //     }
        // },

        // UPDATE_OPERATION_INFO_SYSTEM(state, data) {
        //     const count = state.operationInfoPage.systems.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         const item = state.operationInfoPage.systems.find(
        //             (f) => f.id === data.id
        //         );
        //         Object.assign(item, data);
        //     } else {
        //         state.operationInfoPage.systems.push(data);
        //     }
        // },

        // UPDATE_OPERATION_RECON_SOLO(state, data) {
        //     const count = state.operationInfoPage.recons.filter(
        //         (item) => item.id === data.id
        //     ).length;

        //     if (count > 0) {
        //         const item = state.operationInfoPage.recons.find(
        //             (f) => f.id === data.id
        //         );
        //         Object.assign(item, data);
        //     } else {
        //         state.operationInfoPage.recons.push(data);
        //     }

        //     const countRecon = state.operationInfoRecon.filter(
        //         (item) => item.id === data.id
        //     ).length;

        //     if (countRecon > 0) {
        //         const item = state.operationInfoRecon.find(
        //             (f) => f.id === data.id
        //         );
        //         Object.assign(item, data);
        //     } else {
        //         state.operationInfoRecon.push(data);
        //     }
        // },

        // REMOVED_OPERATION_RECON(state, data) {
        //     console.log(data);
        //     var info = state.operationInfoPage.recons.filter(
        //         (r) => r.id != data.id
        //     );
        //     state.operationInfoPage.recons = info;

        //     const count = state.operationInfoRecon.filter(
        //         (item) => item.id === data.id
        //     ).length;

        //     if (count > 0) {
        //         const item = state.operationInfoRecon.find(
        //             (f) => f.id === data.id
        //         );
        //         Object.assign(item, data);
        //     } else {
        //         state.operationInfoRecon.push(data);
        //     }
        // },

        // UPDATE_OPERATION_MESSAGE(state, data) {
        //     state.operationInfoPage.messages = data;
        //     state.operationInfoMessageCount =
        //         state.operationInfoMessageCount + 1;
        // },

        CLEAR_OPERATION_MESSAGE_COUNT(state) {
            state.operationInfoMessageCount = 0;
        },

        // UPDATE_OPERATION_STATUS(state, data) {
        //     state.operationInfoPage.status = data;
        // },

        // UPDATE_OPERATION_OPERATION(state, data) {
        //     state.operationInfoPage.operation = data;
        // },

        UPDATE_OPERATION_CAMPAIGNS(state, data) {
            state.operationInfoPage.campaigns = data;
        },

        // UPDATE_OPERATION_CAMPAIGNS_SOLO(state, data) {
        //     const item = state.operationInfoPage.campaigns.find(
        //         (item) => item.id === data.id
        //     );
        //     const count = state.operationInfoPage.campaigns.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     } else {
        //         state.operationInfoPage.campaigns.push(data);
        //     }
        // },

        // UPDATE_OPERATION_SYSTEMS(state, data) {
        //     state.operationInfoPage.systems = data;
        // },

        // SET_NEW_SOLO_OPERATIONS(state, solooplist) {
        //     state.newSoloOperations = solooplist;
        // },

        // UPDATE_NEW_SOLO_OPERATIONS(state, data) {
        //     const item = state.newSoloOperations.find(
        //         (item) => item.id === data.id
        //     );
        //     const count = state.newSoloOperations.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     } else {
        //         state.newSoloOperations.push(data);
        //     }
        // },

        // SET_NEW_SOLO_OPERATIONS_REGIONS(state, regionList) {
        //     state.newSoloOperationsRegionList = regionList;
        // },

        // SET_NEW_SOLO_OPERATIONS_CONSTELLATION(state, constellationList) {
        //     state.newSoloOperationsConstellationList = constellationList;
        // },

        SET_AMMO_REQUEST(state, ammorequest) {
            state.ammoRequest = ammorequest;
        },

        SET_MISSING_CORP_ID(state, data) {
            state.missingCorpID = data;
        },

        SET_MISSING_CORP_TICK(state, data) {
            state.missingCorpTick = data;
        },

        DELETE_AMMO_REQUEST(state, id) {
            let index = state.ammoRequest.findIndex((e) => e.id == id);
            if (index >= 0) {
                state.ammoRequest.splice(index, 1);
            }
        },

        ADD_AMMO_REQUEST(state, data) {
            state.ammoRequest.push(data);
        },

        UPDATE_AMMO_REQUEST(state, data) {
            const item = state.ammoRequest.find((item) => item.id === data.id);
            const count = state.ammoRequest.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.ammoRequest.push(data);
            }
        },

        // SET_SYSTEMLIST(state, systemlist) {
        //     state.systemlist = systemlist;
        // },

        SET_CONSTELLATION_LIST(state, constellationlist) {
            state.constellationlist = constellationlist;
        },

        UPDATE_TOOLTIP_TOGGLE(state, tooltipToggle) {
            if (tooltipToggle) {
                state.tooltipToggle = false;
            } else {
                state.tooltipToggle = true;
            }
        },

        SET_MOONLIST(state, moonlist) {
            state.moonlist = moonlist;
        },

        // SET_STRUCTURELIST(state, structurelist) {
        //     state.structurelist = structurelist;
        // },

        SET_TOWERLIST(state, towerlist) {
            state.towerlist = towerlist;
        },

        // SET_TICKLIST(state, ticklist) {
        //     state.ticklist = ticklist;
        // },

        UPDATE_TICKLIST(state, data) {
            state.ticklist.push(data);
        },

        // SET_ALLIANCE_TICKLIST(state, allianceticklist) {
        //     state.allianceticklist = allianceticklist;
        // },

        // SET_TIMERS(state, timers) {
        //     state.timers = timers;
        // },

        // SET_TIMERS_REGIONS(state, timersRegions) {
        //     state.timersRegions = timersRegions;
        // },

        // MARK_TIMER_OVER(state, timer) {
        //     const item = state.timers.find((item) => item.id === timer.id);
        //     Object.assign(item, timer);
        // },

        SET_NODE_JOIN(state, nodeJoin) {
            state.nodeJoin = nodeJoin;
        },

        ADD_NODE_JOIN(state, data) {
            state.nodeJoin.push(data);
        },

        UPDATE_NODE_JOIN(state, data) {
            const item = state.nodeJoin.find((c) => c.id === data.id);
            Object.assign(item, data);
        },

        DELETE_NODE_JOIN(state, id) {
            let index = state.nodeJoin.findIndex((e) => e.id == id);
            if (index >= 0) {
                state.nodeJoin.splice(index, 1);
            }
        },

        SET_ITEMS(state, items) {
            state.items = items;
        },

        SET_CAMPAIGN_JOIN(state, campaignJoin) {
            state.campaignJoin = campaignJoin;
        },

        SET_START_CAMPAIGN_JOIN(state, startcampaignJoin) {
            state.startcampaignJoin = startcampaignJoin;
        },

        // SET_TOWERS(state, towers) {
        //     state.towers = towers;
        // },

        // UPDATE_TOWERS(state, data) {
        //     const item = state.towers.find((c) => c.id === data.id);
        //     Object.assign(item, data);
        // },

        // DELETE_TOWERS(state, id) {
        //     let index = state.towers.findIndex((e) => e.id == id);
        //     if (index >= 0) {
        //         state.towers.splice(index, 1);
        //     }
        // },

        SET_CAMPAIGN_SOLA_SYSTEMS(state, campaignSolaSystems) {
            state.campaignSolaSystems = campaignSolaSystems;
        },

        UPDATE_CAMPAIGN_SOLA_SYSTEMS(state, data) {
            const item = state.campaignSolaSystems.find(
                (c) => c.id === data.id
            );
            Object.assign(item, data);
        },

        // SET_STATIONS(state, stations) {
        //     state.stations = stations;
        // },

        SET_STATIONS_FIT(state, fit) {
            state.stationFits = fit;
        },

        UPDATE_STATION_NOTIFICATION(state, data) {
            const item = state.stations.find((item) => item.id === data.id);
            const count = state.stations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.stations.push(data);
            }
        },

        UPDATE_KEY_MESSAGE(state, data) {
            const item = state.userkeys.find((item) => item.id === data.id);
            const count = state.userkeys.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.userkeys.push(data);
            }
        },

        UPDATE_RC_STATION(state, data) {
            const item = state.rcstations.find((item) => item.id === data.id);
            const count = state.rcstations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.rcstations.push(data);
            }
        },

        UPDATE_CHILL_STATION(state, data) {
            const item = state.chillstations.find(
                (item) => item.id === data.id
            );
            const count = state.chillstations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.chillstations.push(data);
            }
        },

        UPDATE_WELP_STATION(state, data) {
            const item = state.welpstations.find((item) => item.id === data.id);
            const count = state.welpstations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.welpstations.push(data);
            }
        },

        UPDATE_CHILL_STATION_CURRENT(state, data) {
            const item = state.chillstations.find(
                (item) => item.id === data.id
            );
            const count = state.chillstations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            }
        },

        UPDATE_WELP_STATION_CURRENT(state, data) {
            const item = state.welpstations.find((item) => item.id === data.id);
            const count = state.welpstations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            }
        },

        UPDATE_RC_STATION_CURRENT(state, data) {
            const item = state.rcstations.find((item) => item.id === data.id);
            const count = state.rcstations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            }
        },

        UPDATE_RC_FC(state, data) {
            const item = state.rcfcs.find((item) => item.id === data.id);
            const count = state.rcfcs.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.rcfcs.push(data);
            }
        },

        SET_CORES(state, cores) {
            state.cores = cores;
        },

        UPDATE_CORES(state, data) {
            const item = state.cores.find(
                (c) => c.station_id === data.station_id
            );
            Object.assign(item, data);
        },

        SET_CAMPAIGN_MEMBERS(state, users) {
            state.campaignmembers = users;
        },

        // SET_USERS(state, users) {
        //     state.users = users;
        // },

        SET_USER_KEYS(state, userskeys) {
            state.userkeys = userskeys;
        },

        SET_KEY_FLEETS(state, keyfleets) {
            state.keyfleets = keyfleets;
        },

        // SET_USERS_CHARS(state, data) {
        //     state.userschars = data;
        // },

        UPDATE_USERS_CHARS(state, data) {
            const item = state.userschars.find((item) => item.id == data.id);
            if (item != null) {
                Object.assign(item, data);
            }
        },

        DELETE_USER_CHAR(state, id) {
            let index = state.userschars.findIndex((user) => user.id == id);
            if (index >= 0) {
                state.userschars.splice(index, 1);
            }
        },

        ADD_USER_CHAR(state, data) {
            state.userschars.push(data);
        },

        // SET_ROLES(state, roles) {
        //     state.rolesList = roles;
        // },

        SET_KEYS(state, keys) {
            state.keysList = keys;
        },

        SET_CAMPAIGNS(state, campaigns) {
            state.campaigns = campaigns;
        },

        SET_CAMPAIGNS_REGION(state, campaignsRegion) {
            state.campaignsRegion = campaignsRegion;
        },

        SET_RC_REGION(state, rcsheetRegion) {
            state.rcsheetRegion = rcsheetRegion;
        },

        SET_CHILL_REGION(state, chillsheetRegion) {
            state.chillsheetRegion = chillsheetRegion;
        },

        SET_WELP_REGION(state, welpsheetRegion) {
            state.welpsheetRegion = welpsheetRegion;
        },

        SET_RC_TYPE(state, rcsheetItem) {
            state.rcsheetItem = rcsheetItem;
        },

        SET_CHILL_TYPE(state, chillsheetItem) {
            state.chillsheetItem = chillsheetItem;
        },

        SET_WELP_TYPE(state, welpsheetItem) {
            state.welpsheetItem = welpsheetItem;
        },

        SET_RC_STATUS(state, rcsheetStatus) {
            state.rcsheetStatus = rcsheetStatus;
        },

        SET_CHILL_STATUS(state, chillsheetStatus) {
            state.chillsheetStatus = chillsheetStatus;
        },

        SET_WELP_STATUS(state, welpsheetStatus) {
            state.welpsheetStatus = welpsheetStatus;
        },

        SET_COORD_REGION(state, coordsheetRegion) {
            state.coordsheetRegion = coordsheetRegion;
        },

        SET_COORD_ITEM(state, coordsheetItem) {
            state.coordsheetItem = coordsheetItem;
        },

        SET_COORD_STATUS(state, coordsheetStatus) {
            state.coordsheetStatus = coordsheetStatus;
        },

        UPDATE_CAMPAIGNS(state, data) {
            const item = state.campaigns.find((c) => c.id === data.id);
            Object.assign(item, data);
        },

        UPDATE_CAMPAIGN(state, data) {
            const item = state.campaigns.find((item) => item.id == data.id);
            Object.assign(item, data);
        },

        SET_MULTI_CAMPAIGNS(state, multicampaigns) {
            state.multicampaigns = multicampaigns;
        },

        // SET_START_CAMPAIGNS(state, startcampaigns) {
        //     state.startcampaigns = startcampaigns;
        // },

        // SET_CAMPAIGN_LOGS(state, logs) {
        //     state.loggingNewCampaign = logs;
        // },

        // SET_CAMPAIGNSLIST(state, campaignslist) {
        //     state.campaignslist = campaignslist;
        // },

        // SET_NEW_CAMPAIGNSLIST(state, campaignslist) {
        //     state.newCampaignsList = campaignslist;
        // },

        // SET_NEW_OPERATION_LIST(state, operations) {
        //     state.newOperationList = operations;
        // },

        // UPDATE_NEW_OPERATION_LIST(state, data) {
        //     const item = state.newOperationList.find(
        //         (item) => item.id === data.id
        //     );
        //     const count = state.newOperationList.filter(
        //         (item) => item.id === data.id
        //     ).length;
        //     if (count > 0) {
        //         Object.assign(item, data);
        //     }
        // },

        SET_LOGGING_CAMPAIGN(state, logs) {
            state.loggingcampaign = logs;
        },

        SET_LOGGING_RC_SHEET(state, logs) {
            state.loggingRcSheet = logs;
        },

        SET_LOGGING_STATIONS(state, logs) {
            state.loggingStations = logs;
        },

        ADD_LOGGING_RC_SHEET(state, data) {
            state.loggingcampaign.push(data);
        },

        ADD_LOGGING_STATION(state, data) {
            state.loggingStations.push(data);
        },

        ADD_LOGGING_CAMPGIN(state, data) {
            state.loggingcampaign.push(data);
        },

        // SET_LOGGING_ADMIN(state, logs) {
        //     state.loggingAdmin = logs;
        // },

        // SET_NOTIFICATIONS(state, notifications) {
        //     state.notifications = notifications;
        // },

        // UPDATE_NOTIFICATIONS(state, data) {
        //     const item = state.notifications.find(
        //         (item) => item.id === data.id
        //     );
        //     Object.assign(item, data);
        // },

        // SET_CAMPAIGN_SYSTEMS(state, systems) {
        //     state.campaignsystems = systems;
        // },

        SET_START_CAMPAIGN_SYSTEMS(state, systems) {
            state.startcampaignsystems = systems;
        },

        UPDATE_START_CAMPAIGN_SYSTEM(state, data) {
            const item = state.startcampaignsystems.find(
                (item) => item.id === data.id
            );
            Object.assign(item, data);
        },

        // UPDATE_OPERATION_INFO_SETTING(state, data) {
        //     state.operationInfoSetting = data;
        // },

        UPDATE_CAMPAIGN_SYSTEM(state, data) {
            const item = state.campaignsystems.find(
                (item) => item.id === data.id
            );
            Object.assign(item, data);
        },

        ADD_CAMPAIGN_SYSTEM(state, data) {
            state.campaignsystems.push(data);
        },

        UPDATE_CAMPAIGN_SYSTEM_BY_USER_ID(state, payload) {
            const item = state.campaignsystems.find(
                (item) => item.user_id == payload.user_id
            );
            if (item > 0) {
                Object.assign(item, payload.data);
            }
        },

        UPDATE_CAMPAIGN_SYSTEM_UPDATE(state, data) {
            const item = state.campaignsystems.find(
                (item) => item.campaign_id === data.id
            );
            Object.assign(item, data);
        },

        DELETE_CAMPAIGN_SYSTEM(state, id) {
            let index = state.campaignsystems.findIndex((e) => e.id == id);
            if (index >= 0) {
                state.campaignsystems.splice(index, 1);
            }
        },

        // SET_CAMPAIGN_USERS(state, data) {
        //     state.campaignusers = data;
        // },

        UPDATE_CAMPAIGN_USERS(state, data) {
            const item = state.campaignusers.find(
                (item) => item.id === data.id
            );
            if (item != null) {
                Object.assign(item, data);
            }
        },

        ADD_CAMPAIGN_USERS(state, data) {
            state.campaignusers.push(data);
        },

        DELETE_CAMPAIGN_USER(state, id) {
            let index = state.campaignusers.findIndex((user) => user.id == id);
            if (index >= 0) {
                state.campaignusers.splice(index, 1);
            }
        },

        ADD_STATION_NOTIFICATION(state, data) {
            const check = state.stations.find(
                (station) => station.id == data.id
            );
            if (check != null) {
                Object.assign(check, data);
            } else {
                state.stations.push(data);
            }
        },

        // ADD_NEW_OPERATION_LIST(state, data) {
        //     const check = state.newOperationList.find(
        //         (station) => station.id == data.id
        //     );
        //     if (check != null) {
        //         Object.assign(check, data);
        //     } else {
        //         state.newOperationList.push(data);
        //     }
        // },

        // DELETE_OPERATION_FROM_LSIT(state, num) {
        //     const count = state.newOperationList.filter(
        //         (o) => o.id == num
        //     ).length;
        //     if (count > 0) {
        //         state.newOperationList = state.newOperationList.filter(
        //             (o) => o.id != num
        //         );
        //     }
        // },

        // DELETE_STATION_NOTIFICATION(state, id) {
        //     let index = state.stations.findIndex((s) => s.id == id);
        //     if (index >= 0) {
        //         state.stations.splice(index, 1);
        //     }
        // },

        DELETE_STATION_SHEET_NOTIFICATION(state, id) {
            let index = state.stationList.findIndex((s) => s.id == id);
            if (index >= 0) {
                state.stationList.splice(index, 1);
            }
        },

        SET_RECON_TASK_SYSTEMS(state, systems) {
            state.recontasksystems = systems;
        },

        SET_RC_STATIONS(state, stations) {
            state.rcstations = stations;
        },

        SET_CHILL_STATIONS(state, stations) {
            state.chillstations = stations;
        },

        SET_WELP_STATIONS(state, stations) {
            state.welpstations = stations;
        },

        SET_COORD_STATIONS(state, stations) {
            state.stations = stations;
        },

        SET_RC_FCS(state, fcs) {
            state.rcfcs = fcs;
        },

        SET_FLEETS(state, fleets) {
            state.fleets = fleets;
        },

        UPDATE_RECON_TASK_SYSTEMS(state, data) {
            const item = state.recontasksystems.find((c) => c.id === data.id);
            Object.assign(item, data);
        },
        SET_USER_ID(state, user_id) {
            state.user_id = user_id;
        },
        SET_USER_NAME(state, user_name) {
            state.user_name = user_name;
        },

        SET_DELVE_LINK(state, delveLink) {
            state.delveLink = delveLink;
        },

        SET_QUERIOUS_LINK(state, queriousLink) {
            state.queriousLink = queriousLink;
        },

        SET_PERIOD_BASIS_LINK(state, periodbasisLink) {
            state.periodbasisLink = periodbasisLink;
        },

        // SET_EVE_USER_COUNT(state, count) {
        //     state.eveUserCount = count;
        // },

        // UPDATE_EVE_USER_COUNT(state, data) {
        //     state.eveUserCount = data;
        // },
    },

    actions: {
        // async getOperationUsers({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationinfousers",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_OPERATION_INFO_USERS", res.data.users);
        // },

        // async getOperationRecon({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationinforecon",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_OPERATION_INFO_RECON", res.data.recon);
        // },

        // async getOperationInfoMumble({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationinfomumble",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_OPERATION_INFO_MUMBLE", res.data.mumble);
        // },

        // async getOperationInfoJamList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationinfojammerstatus",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_OPERATION_INFO_JAM_LIST", res.data.jam);
        // },

        // async getOperationInfoReconRoles({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationinfofleetreconrole",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit(
        //         "SET_OPERATION_INFO_RECON_FLEET_ROLE_LIST",
        //         res.data.roleList
        //     );
        // },

        // async getOperationInfoDoctrines({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationinfodoctrines",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_OPERATION_INFO_DOCTRINES", res.data.doc);
        // },

        // async getOperationSheetInfo({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationinfosheet",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_OPERATION_INFO", res.data.opinfo);
        // },

        // async getOperationSheetInfoOperationList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationlistinfoop",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_OPERATION_INFO_OPERATION_LIST", res.data.operations);
        // },

        // async getOperationSheetInfoPage({ commit }, id) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationinfopage/" + id,
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_OPERATION_INFO_PAGE", res.data.data);
        // },

        // async getStationList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/stationsheet",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_STATION_LIST", res.data.stations);
        // },
        // async getUserList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/users",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_USER_LIST", res.data.users);
        // },

        // async getOperationInfo({ commit }, id) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationinfo/" + id,
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_OPERATION_PAGE", res.data);
        // },

        // async getWebwayStartSystems({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/getwebwaystartsystems",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_WEBWAY_START_SYSTEMS", res.data.systems);
        // },

        // async getStationRegionLists({ commit }) {z
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/getregionlists",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_STATION_PULL_REGIONS", res.data.pull);
        //     commit("SET_STATION_LIST_FC", res.data.fcs);
        //     commit("SET_STATION_REGION_LIST", res.data.regionlist);
        //     commit("SET_SYSTEMLIST", res.data.systemlist);
        //     commit("SET_WEBWAY_START_SYSTEMS", res.data.webwayStartSystems);
        // },

        async getSoloOperationList({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/solooperationlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_NEW_SOLO_OPERATIONS", res.data.solooplist);
            commit("SET_NEW_SOLO_OPERATIONS_REGIONS", res.data.regionList);
            commit(
                "SET_NEW_SOLO_OPERATIONS_CONSTELLATION",
                res.data.constellationList
            );
        },

        // async getTimerDataAll({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/timers",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_TIMERS", res.data.timers);
        // },

        // async getTimerDataAll({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/timers",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_TIMERS", res.data.timers);
        // },

        // async updateTickList({ commit }, ticker) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/addmissingcorp/" + ticker,
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_TICKLIST", res.data.ticklist);
        //     commit("SET_MISSING_CORP_ID", res.data.corpID);
        //     commit("SET_MISSING_CORP_TICK", res.data.corpTicker);
        // },

        // async getTimerDataAllRegion({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/timersregions",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_TIMERS_REGIONS", res.data.timersregions);
        // },

        // async getSystemList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/systemlist",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_SYSTEMLIST", res.data.systemlist);
        // },

        async getAmmoRequest({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/ammorequestrecords",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_AMMO_REQUEST", res.data.ammorequest);
        },

        async getMoonList({
            commit
        }, system_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/moons/" + system_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_MOONLIST", res.data.moons);
        },

        // async getStructureList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/structurelist",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_STRUCTURELIST", res.data.structurelist);
        // },

        async getTowerList({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/towerlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_TOWERLIST", res.data.towerlist);
        },

        // async getTickList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/ticklist",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_TICKLIST", res.data.ticklist);
        // },

        // async getAllianceTickList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/allianceticklist",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_ALLIANCE_TICKLIST", res.data.allianceticklist);
        // },

        async getNodeJoinByCampaignId({
            commit
        }, campaign_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/nodejoin/" + campaign_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_NODE_JOIN", res.data.nodeJoin);
        },

        // async getTowerData({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/towersrecords",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_TOWERS", res.data.towers);
        // },

        // async getStationData({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/stationrecords",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_STATIONS", res.data.stations);
        // },

        // async getStationDataByUserId({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/stationrecordsbyid",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_STATIONS", res.data.stations);
        // },

        async getCampaignJoinDataByCampaign({
            commit
        }, campid) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/campaignjoinbyid/" + campid,
                data: this.picked,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CAMPAIGN_JOIN", res.data.value);
        },

        async getCampaignJoinData({
            commit
        }, campid) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/campaignjoin",
                data: this.picked,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CAMPAIGN_JOIN", res.data.value);
        },

        async getStartCampaignJoinDataByCampaign({
            commit
        }, campid) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/startcampaignjoinbyid/" + campid,
                data: this.picked,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_START_CAMPAIGN_JOIN", res.data.value);
        },

        // async getStartCampaignJoinData({ commit }, campid) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/startcampaignjoin",
        //         data: this.picked,
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_START_CAMPAIGN_JOIN", res.data.value);
        // },

        async getCampaignMembers({
            commit
        }, campaign_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/campaignsystemusers/" + campaign_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CAMPAIGN_MEMBERS", res.data.users);
        },

        // async getUsersChars({ commit }, user_id) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/campaignusersrecordsbychar/" + user_id,
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_USERS_CHARS", res.data.users);
        // },

        async getCampaignSolaSystems({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/campaignsolasystems",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CAMPAIGN_SOLA_SYSTEMS", res.data.data);
        },

        // async getUsers({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/allusersroles",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_USERS", res.data.usersroles);
        // },

        async getUserKeys({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/alluserskeys",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            // debugger
            commit("SET_USER_KEYS", res.data.userskeys);
            // commit("SET_USER_ROLES", userRoles.map(u => ({id: u.id, name: u.name})));
        },

        async getKeyFleets({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allkeyfleets",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            // debugger
            commit("SET_KEY_FLEETS", res.data.keyfleets);
            // commit("SET_USER_ROLES", userRoles.map(u => ({id: u.id, name: u.name})));
        },

        // async getRoles({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/roles",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_ROLES", res.data.roles);
        //     // commit("SET_USER_ROLES", userRoles.map(u => ({id: u.id, name: u.name})));
        // },

        async getKeys({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/keys",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_KEYS", res.data.keys);
            // commit("SET_USER_ROLES", userRoles.map(u => ({id: u.id, name: u.name})));
        },

        async getCampaigns({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/campaigns",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CAMPAIGNS", res.data.campaigns);
        },

        async getCampaignsRegions({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/campaignsregion",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CAMPAIGNS_REGION", res.data.campaignslistRegion);
        },

        async getRcRegions({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/rcregionlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_RC_REGION", res.data.rcsheetlistRegion);
        },

        async getChillRegions({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/chillregionlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CHILL_REGION", res.data.chillsheetlistRegion);
        },

        async getWelpRegions({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/welpregionlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_WELP_REGION", res.data.welpsheetlistRegion);
        },

        async getRcItems({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/rcTypelist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_RC_TYPE", res.data.rcsheetlistType);
        },

        async getChillItems({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/chillTypelist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CHILL_TYPE", res.data.chillsheetlistType);
        },

        async getWelpItems({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/welpTypelist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_WELP_TYPE", res.data.welpsheetlistType);
        },

        async getRcStatus({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/rcStatuslist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_RC_STATUS", res.data.rcsheetlistStatus);
        },

        async getChillStatus({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/chilltest",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CHILL_STATUS", res.data.chillsheetlistStatus);
        },

        async getWelpStatus({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/welptest",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_WELP_STATUS", res.data.welpsheetlistStatus);
        },

        async getCoordStatus({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/coordStatuslist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_COORD_STATUS", res.data.coordsheetlistStatus);
        },

        async getCoordRegions({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/coordRegionlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_COORD_REGION", res.data.coordsheetlistRegion);
        },

        async getCoordItems({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/coordItemlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_COORD_ITEM", res.data.coordsheetlistType);
        },

        async getMultiCampaigns({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/multicampaigns",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_MULTI_CAMPAIGNS", res.data.campaigns);
        },

        // async getStartCampaigns({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/startcampaigns",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_START_CAMPAIGNS", res.data.campaigns);
        // },

        // async getCampaignsList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/campaignslist",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_CAMPAIGNSLIST", res.data.campaignslist);
        // },

        async getCampaignsList({
            commit
        }, op_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/newoperationlogs/" + op_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_CAMPAIGN_LOGS", res.data.logs);
        },

        // async getNewCampaignsList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/newcampaignslist",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_NEW_CAMPAIGNSLIST", res.data.campaignslist);
        // },

        // async getCustomOperationList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/operationlist",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_NEW_OPERATION_LIST", res.data.operations);
        // },

        async getLoggingCampaign({
            commit
        }, campaign_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/checkcampaign/" + campaign_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_LOGGING_CAMPAIGN", res.data.logs);
        },

        async getLoggingRcSheet({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/rcadminlogs",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_LOGGING_RC_SHEET", res.data.logs);
        },

        async getLoggingStations({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/stationlogs",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_LOGGING_STATIONS", res.data.logs);
        },

        // async getLoggingAdmin({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true,
        //         url: "/api/checkadmin",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_LOGGING_ADMIN", res.data.logs);
        // },

        markOver({
            commit
        }, timer) {
            commit("MARK_TIMER_OVER", timer);
        },

        // removeCharfromOpList({ commit }, id) {
        //     commit("DELETE_OP_CHAR_FROM_CHAR_LIST", id);
        // },

        // deleteOperationSheetInfoPageFleet({ commit }, id) {
        //     commit("DELETE_OPERATION_SHEET_INFO_PAGE_FLEET", id);
        // },

        // removeCharfromOwnList({ commit }, id) {
        //     commit("DELETE_OP_CHAR_FROM_OWN_LIST", id);
        // },

        updateWebwaySelectedStartSystem({
            commit
        }, data) {
            commit("SET_WEBWAY_SELECTED_START_SYSTEM", data);
        },

        // updateNotification({
        //     commit
        // }, data) {
        //     commit("UPDATE_NOTIFICATIONS", data);
        // },

        // updateStationNotification({ commit }, data) {
        //     commit("UPDATE_STATION_NOTIFICATION", data);
        // },

        updateKeyMessage({
            commit
        }, data) {
            commit("UPDATE_KEY_MESSAGE", data);
        },

        // updateCampaignSystemAll({ commit }, data) {
        //     commit("UPDATE_OPERATION_SYSTEMS_ALL", data);
        // },

        updateRcStation({
            commit
        }, data) {
            commit("UPDATE_RC_STATION", data);
        },

        updateChillStation({
            commit
        }, data) {
            commit("UPDATE_CHILL_STATION", data);
        },

        updateWelpStation({
            commit
        }, data) {
            commit("UPDATE_WELP_STATION", data);
        },

        // updateStationList({ commit }, data) {
        //     commit("UPDATE_STATION_LIST", data);
        // },

        updateChillStationCurrent({
            commit
        }, data) {
            commit("UPDATE_CHILL_STATION_CURRENT", data);
        },

        // updateOperationPageInfo({ commit }, data) {
        //     commit("UPDATE_OPERATION_PAGE_INFO", data);
        // },

        // removeOperationPageInfo({ commit }, data) {
        //     commit("REMOVE_OPERATION_PAGE_INFO", data);
        // },

        // clearOperationInfoSolo({ commit }) {
        //     commit("CLEAR_OPERATION_INFO_SOLO");
        // },

        // updateOperationSheetInfoPage({ commit }, data) {
        //     commit("UPDATE_OPERATION_INFO_PAGE", data);
        // },

        updateWelpStationCurrent({
            commit
        }, data) {
            commit("UPDATE_WELP_STATION_CURRENT", data);
        },

        updateRcStationCurrent({
            commit
        }, data) {
            commit("UPDATE_RC_STATION_CURRENT", data);
        },

        // updateSoloOperationList({ commit }, data) {
        //     commit("UPDATE_NEW_SOLO_OPERATIONS", data);
        // },

        updateRcFC({
            commit
        }, data) {
            commit("UPDATE_RC_FC", data);
        },

        // setReadOnly({ commit }, newValue) {
        //     commit("SET_NEW_OPERATION_READ_ONLY", newValue);
        // },

        updateCores({
            commit
        }, data) {
            commit("UPDATE_CORES", data);
        },

        updateCampaigns({
            commit
        }, data) {
            commit("UPDATE_CAMPAIGNS", data);
        },

        // updateTowers({ commit }, data) {
        //     commit("UPDATE_TOWERS", data);
        // },

        updateCampaignSystem({
            commit
        }, data) {
            commit("UPDATE_CAMPAIGN_SYSTEM", data);
        },

        updateStartCampaignSystem({
            commit
        }, data) {
            commit("UPDATE_START_CAMPAIGN_SYSTEM", data);
        },

        // updateOperationInfoSetting({ commit }, data) {
        //     commit("UPDATE_OPERATION_INFO_SETTING", data);
        // },

        updateTooltipToggle({
            commit
        }, data) {
            commit("UPDATE_TOOLTIP_TOGGLE", data);
        },

        // updateNewCampaigns({ commit }, data) {
        //     commit("UPDATE_NEW_CAMPAIGNS", data);
        // },

        updateCampaignSystemByUserID({
            commit
        }, payload) {
            commit("UPDATE_CAMPAIGN_SYSTEM_BY_USER_ID", payload);
        },

        // updateOperationOverLay({ commit }, num) {
        //     commit("SET_NEW_OPERATION_MESSAGE_OVERLAY", num);
        // },

        clearOperationInfoMessageCount({
            commit
        }) {
            commit("CLEAR_OPERATION_MESSAGE_COUNT");
        },

        setOpenOperationAddChar({
            commit
        }, num) {
            commit("NEW_OPEN_OPERATION_ADD_CHAR", num);
        },

        updateCampaignSolaSystem({
            commit
        }, data) {
            commit("UPDATE_CAMPAIGN_SOLA_SYSTEMS", data);
        },

        updateCampaignSystemBar({
            commit
        }, data) {
            commit("UPDATE_CAMPAIGN_SYSTEM_UPDATE", data);
        },

        updateCampaign({
            commit
        }, data) {
            commit("UPDATE_CAMPAIGN", data);
        },

        updateCampaignUsers({
            commit
        }, data) {
            commit("UPDATE_CAMPAIGN_USERS", data);
        },

        updateUsersChars({
            commit
        }, data) {
            commit("UPDATE_USERS_CHARS", data);
        },

        updateAmmoRequest({
            commit
        }, data) {
            commit("UPDATE_AMMO_REQUEST", data);
        },

        // updateOperationUserList({ commit }, data) {
        //     commit("UPDATE_OPERATION_USER_LIST", data);
        // },

        // updateOperationInfoUserList({ commit }, data) {
        //     commit("UPDATE_OPERATION_INFO_USER_LIST", data);
        // },

        updateOperationInfoUpdateAllFleet({
            commit
        }, data) {
            commit("UPDATE_OPERATION_INFO_UPDATE_ALL_FLEETS", data);
        },

        // updateOperationInfoAddDankOp({ commit }, data) {
        //     commit("UPDATE_OPERATION_INFO_ADD_DANK_OP", data);
        // },

        updateNodeJoin({
            commit
        }, data) {
            commit("UPDATE_NODE_JOIN", data);
        },

        updateReconTaskSystems({
            commit
        }, data) {
            commit("UPDATE_RECON_TASK_SYSTEMS", data);
        },

        updateEveUserCount({
            commit
        }, data) {
            commit("UPDATE_EVE_USER_COUNT", data);
        },

        addNodeJoin({
            commit
        }, data) {
            commit("ADD_NODE_JOIN", data);
        },

        addAmmoRequest({
            commit
        }, data) {
            commit("ADD_AMMO_REQUEST", data);
        },

        // updateNewOwnChar({ commit }, data) {
        //     commit("UPDATE_OWN_CHAR", data);
        // },

        // updateOpChar({ commit }, data) {
        //     commit("UPDATE_OP_CHAR", data);
        // },

        // updateOperationInfo({ commit }, data) {
        //     commit("UPDATE_OPERATION_PAGE", data);
        // },

        // updateoperationlist({ commit }, data) {
        //     commit("UPDATE_NEW_OPERATION_LIST", data);
        // },

        // addoperationlist({ commit }, data) {
        //     commit("ADD_NEW_OPERATION_LIST", data);
        // },

        // deleteoperationfromlist({ commit }, num) {
        //     commit("DELETE_OPERATION_FROM_LSIT", num);
        // },

        addStationNotification({
            commit
        }, data) {
            commit("ADD_STATION_NOTIFICATION", data);
        },

        addLoggingCampaign({
            commit
        }, data) {
            commit("ADD_LOGGING_CAMPGIN", data);
        },

        addLoggingRcSheet({
            commit
        }, data) {
            commit("ADD_LOGGING_RC_SHEET", data);
        },

        addLoggingStation({
            commit
        }, data) {
            commit("ADD_LOGGING_STATION", data);
        },

        addCampaignUserNew({
            commit
        }, data) {
            commit("ADD_CAMPAIGN_USERS", data);
        },

        addCampaignSystem({
            commit
        }, data) {
            commit("ADD_CAMPAIGN_SYSTEM", data);
        },

        deleteCampaignUser({
            commit
        }, id) {
            commit("DELETE_CAMPAIGN_USER", id);
        },

        deleteStationNotification({
            commit
        }, id) {
            commit("DELETE_STATION_NOTIFICATION", id);
        },

        // deleteStationSheetNotification({ commit }, id) {
        //     commit("DELETE_STATION_SHEET_NOTIFICATION", id);
        // },

        deleteUsersChars({
            commit
        }, id) {
            commit("DELETE_USER_CHAR", id);
        },

        // removeOperationReconSolo({ commit }, data) {
        //     commit("REMOVED_OPERATION_RECON", data);
        // },

        deleteNodeJoin({
            commit
        }, id) {
            commit("DELETE_NODE_JOIN", id);
        },

        deleteAmmoRequest({
            commit
        }, id) {
            commit("DELETE_AMMO_REQUEST", id);
        },

        // deleteTower({ commit }, id) {
        //     commit("DELETE_TOWERS", id);
        // },

        deleteCampaignSystem({
            commit
        }, id) {
            commit("DELETE_CAMPAIGN_SYSTEM", id);
        },

        // updateNewCampaignSystem({ commit }, data) {
        //     commit("UPDATE_CAMPAIGN_SYSTEMS", data);
        // },

        // updateNewCampaignSystemInfo({ commit }, data) {
        //     commit("UPDATE_CAMPAIGN_SYSTEMS_INFO", data);
        // },

        // updateOperationSheetInfoPageFleet({ commit }, data) {
        //     commit("UPDATE_FLEET_INFO", data);
        // },

        // updateOperationSoloSystems({ commit }, data) {
        //     commit("UPDATE_OPERATION_INFO_SYSTEM", data);
        // },

        // updateOperationReconSolo({ commit }, data) {
        //     commit("UPDATE_OPERATION_RECON_SOLO", data);
        // },

        // updateOperationMessage({ commit }, data) {
        //     commit("UPDATE_OPERATION_MESSAGE", data);
        // },

        // updateOperationStatus({ commit }, data) {
        //     commit("UPDATE_OPERATION_STATUS", data);
        // },

        // updateOperationOperation({ commit }, data) {
        //     commit("UPDATE_OPERATION_OPERATION", data);
        // },

        updateOperationCampaigns({
            commit
        }, data) {
            commit("UPDATE_OPERATION_CAMPAIGNS", data);
        },

        // updateOperationCampaignsSolo({ commit }, data) {
        //     commit("UPDATE_OPERATION_CAMPAIGNS_SOLO", data);
        // },

        // updateOperationSystems({ commit }, data) {
        //     commit("UPDATE_OPERATION_SYSTEMS", data);
        // },

        // updateOperationUsers({ commit }, data) {
        //     commit("SET_OPERATION_INFO_USERS", data);
        // },

        updateOperationRecon({
            commit
        }, data) {
            commit("SET_OPERATION_INFO_RECON", data);
        },

        // async getNotifications({commit}) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true, //you can set what request you want to be
        //         url: "/api/notifications",
        //         // data: {id: varID},
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_NOTIFICATIONS", res.data.notifications);
        // },

        async getdelveLink({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/notifications/10000060",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_DELVE_LINK", res.data.link);
        },

        // async getqueriousLink({commit}) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true, //you can set what request you want to be
        //         url: "/api/notifications/10000050",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_QUERIOUS_LINK", res.data.link);
        // },

        async getperiodbasisLink({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/notifications/10000063",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            commit("SET_PERIOD_BASIS_LINK", res.data.link);
        },

        // async geteveusercount({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true, //you can set what request you want to be
        //         url: "/api/eveusercount",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     commit("SET_EVE_USER_COUNT", res.data.count);
        // },

        setUser_id({
            commit
        }, user_id) {
            commit("SET_USER_ID", user_id);
        },
        setUser_name({
            commit
        }, user_name) {
            commit("SET_USER_NAME", user_name);
        },

        // async getCampaignUsersRecords({ commit }, id) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true, //you can set what request you want to be
        //         url: "/api/campaignusersrecords/" + id,
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });
        //     if (res.data.length != 0) {
        //         commit("SET_CAMPAIGN_USERS", res.data.users);
        //     }
        // },

        async getCampaignSystemsRecords({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/campaignsystemsrecords",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.data.length != 0) {
                commit("SET_CAMPAIGN_SYSTEMS", res.data.systems);
            }
        },

        async getStartCampaignSystemsRecords({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/startcampaignsystemsrecords",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.data.length != 0) {
                commit("SET_START_CAMPAIGN_SYSTEMS", res.data.systems);
            }
        },

        async getReconTaskSystemsRecords({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/recontasksystems",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.data.length != 0) {
                commit("SET_RECON_TASK_SYSTEMS", res.data.systems);
            }
        },

        async getRcStationRecords({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/rcsheet",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.data.length != 0) {
                commit("SET_RC_STATIONS", res.data.stations);
            }
        },

        async getChillStationRecords({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/chillsheet",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.data.length != 0) {
                commit("SET_CHILL_STATIONS", res.data.stations);
            }
        },

        async getWelpStationRecords({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/welpsheet",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.data.length != 0) {
                commit("SET_WELP_STATIONS", res.data.stations);
            }
        },

        async getCoordStationRecords({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/coordsheet",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.data.length != 0) {
                commit("SET_COORD_STATIONS", res.data.stations);
            }
        },

        async getRcFcs({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/rcfc",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.data.length != 0) {
                commit("SET_RC_FCS", res.data.fcs);
            }
        },

        async getFleets({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/fleets",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.data.length != 0) {
                commit("SET_FLEETS", res.data.fleets);
            }
        },

        // async getConstellationList({ commit }) {
        //     let res = await axios({
        //         method: "get",
        //         withCredentials: true, //you can set what request you want to be
        //         url: "/api/constellations",
        //         headers: {
        //             Accept: "application/json",
        //             "Content-Type": "application/json",
        //         },
        //     });

        //     commit("SET_CONSTELLATION_LIST", res.data.constellationlist);
        // },

        async loadCampaignSystemData({
            commit
        }, payload) {
            let request = {
                user_id: payload.user_id,
                campaign_id: payload.campaign_id,
                type: payload.type,
            };

            let res = await axios({
                method: "post", //you can set what request you want to be
                withCredentials: true,
                url: "/api/campaignsystemload",
                data: request,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            if (res.data.length != 0) {
                commit("SET_CAMPAIGN_SOLA_SYSTEMS", res.data.sola);
                commit("SET_NODE_JOIN", res.data.nodejoin);
                commit("SET_CAMPAIGN_USERS", res.data.users);
                commit("SET_CAMPAIGN_SYSTEMS", res.data.systems);
                commit("SET_USERS_CHARS", res.data.usersbyid);
                commit("SET_LOGGING_CAMPAIGN", res.data.logs);
            }
        },

        async loadStationInfo({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/loadstationdata",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            if (res.data.length != 0) {
                commit("SET_CORES", res.data.cores);
                commit("SET_ITEMS", res.data.items);
                commit("SET_STATIONS_FIT", res.data.fit);
            }
        },

        async loadAmmoRequestInfo({
            commit
        }) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/loadammorequestdata",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            if (res.data.length != 0) {
                commit("SET_CORES", res.data.cores);
                commit("SET_ITEMS", res.data.items);
                commit("SET_STATIONS_FIT", res.data.fit);
                commit("SET_AMMO_REQUEST", res.data.ammorequest);
            }
        },
    },

    getters: {
        getCampaignsCount: (state) => {
            return state.campaigns.length;
        },

        getMultiCampaignsCount: (state) => {
            return state.multicampaigns.length;
        },

        getMultiCampaignName: (state) => (campid) => {
            return state.multicampaigns.filter((m) => m.id == campid);
        },

        getSystemReadyToGoCount: (state) => (payload) => {
            return state.campaignusers.filter(
                (u) =>
                    u.campaign_id == payload.campaign_id &&
                    u.system_id == payload.system_id &&
                    u.status_id == 3
            ).length;
        },

        getCampaignSolaSystemFilter: (state) => (payload) => {
            return state.campaignSolaSystems.filter(
                (u) =>
                    u.campaign_id == payload.campaign_id &&
                    u.system_id == payload.system_id
            );
        },

        getSystemOnTheWayCount: (state) => (payload) => {
            return state.campaignusers.filter(
                (u) =>
                    u.campaign_id == payload.campaign_id &&
                    u.system_id == payload.system_id &&
                    u.status_id == 2
            ).length;
        },

        getCampaignById: (state) => (id) => {
            return state.campaigns.find((campaigns) => campaigns.id == id);
        },

        getCampaignByLink: (state) => (id) => {
            return state.campaigns.find((campaigns) => campaigns.link == id);
        },

        getsCampaignById: (state) => (id) => {
            return state.campaignJoin.filter(
                (campaigns) => campaigns.custom_campaign_id == id
            );
        },

        getStartCampaignById: (state) => (id) => {
            return state.startcampaignJoin.filter(
                (campaigns) => campaigns.start_campaign_id == id
            );
        },

        getsActiveCampaignById: (state) => (payload) => {
            return state.campaignJoin.filter(
                (c) =>
                    c.custom_campaign_id == payload.id &&
                    (c.status_id == 2 || c.status_id == 1) &&
                    c.constellation_id == payload.constellation_id &&
                    c.warmup == 1
            );
        },

        //changeback

        getsActiveCampaignByIdDrop: (state) => (payload) => {
            return state.campaignJoin.filter(
                (c) =>
                    c.custom_campaign_id == payload.id &&
                    c.status_id == 2 &&
                    c.constellation_id == payload.constellation_id &&
                    c.warmup == 1
            );
        },

        // getsActiveCampaigfffffnByIdDrop: st  ate => payload => {
        //     return state.campaignJoin.filter(c => c.custom_campaign_id == payload.id && c.constellation_id == payload.constellation_id);
        // },

        getCampaignJoinById: (state) => (id) => {
            return state.campaignJoin.filter((c) => c.custom_campaign_id == id);
        },

        getStartJoinById: (state) => (id) => {
            return state.startcampaignJoin.filter(
                (c) => c.start_campaign_id == id
            );
        },

        getCampaignMembersByCampaign: (state) => (id) => {
            return state.campaignmembers.find((m) => m.campaign_id == id);
        },

        getCampaignUsersReadyToGoAll: (state) => (id) => {
            return state.campaignusers.filter(
                (campaignusers) =>
                    campaignusers.system_id == id &&
                    campaignusers.status_id == 3
            );
        },

        getCampaignUsersOnTheWayAll: (state) => (id) => {
            return state.campaignusers.filter(
                (campaignusers) =>
                    campaignusers.system_id == id &&
                    campaignusers.status_id == 2
            );
        },

        getCampaignUsersByUserId: (state) => (id) => {
            return state.campaignusers.filter(
                (campaignusers) => campaignusers.site_id == id
            );
        },

        getCampaignUsersByUserIdCount: (state) => (id) => {
            return state.campaignusers.filter(
                (campaignusers) => campaignusers.site_id == id
            ).length;
        },

        getCampaignUsersByUserIdEntosis: (state) => (id) => {
            return state.campaignusers.filter(
                (campaignusers) =>
                    campaignusers.site_id == id && campaignusers.role_id == 1
            );
        },

        getCampaignUsersByUserIdEntosisFree: (state) => (id) => {
            return state.campaignusers.filter(
                (campaignusers) =>
                    campaignusers.site_id == id &&
                    campaignusers.role_id == 1 &&
                    campaignusers.node_id == null
            );
        },

        getCampaignUsersByUserIdEntosisFreeCount: (state) => (id) => {
            return state.campaignusers.filter(
                (campaignusers) =>
                    campaignusers.site_id == id &&
                    campaignusers.role_id == 1 &&
                    campaignusers.node_id == null
            ).length;
        },

        getCampaignUsersByUserIdEntosisCount: (state) => (id) => {
            return state.campaignusers.filter(
                (campaignusers) =>
                    campaignusers.site_id == id && campaignusers.role_id == 1
            ).length;
        },

        getActiveCampaigns: (state) => {
            return state.campaigns.find(
                (campaigns) => campaigns.status_id == 2
            );
        },

        getTotalNodeCountByCampaign: (state) => (id) => {
            return state.campaignsystems.filter((sys) => sys.campaign_id == id)
                .length;
        },

        getTotalNodeCountByMultiCampaign: (state) => (id) => {
            return state.campaignsystems.filter(
                (sys) => sys.custom_campaign_id == id && sys.warmup == 1
            ).length;
        },

        getHackingNodeCountByCampaign: (state) => (id) => {
            return state.campaignsystems.filter(
                (sys) =>
                    sys.campaign_id == id &&
                    sys.status_id != 1 &&
                    (sys.status_id == 2 ||
                        sys.status_id == 3 ||
                        sys.status_id == 4 ||
                        sys.status_id == 8)
            ).length;
        },

        getRedHackingNodeCountByCampaign: (state) => (id) => {
            return state.campaignsystems.filter(
                (sys) =>
                    sys.campaign_id == id &&
                    (sys.status_id == 7 || sys.status_id == 5)
            ).length;
        },

        getHackingNodeCountByMultiCampaign: (state) => (id) => {
            return state.campaignsystems.filter(
                (sys) =>
                    sys.custom_campaign_id == id &&
                    sys.warmup == 1 &&
                    sys.status_id != 1 &&
                    (sys.status_id == 2 ||
                        sys.status_id == 3 ||
                        sys.status_id == 4 ||
                        sys.status_id == 8)
            ).length;
        },

        getRedHackingNodeCountByMultiCampaign: (state) => (id) => {
            return state.campaignsystems.filter(
                (sys) =>
                    sys.custom_campaign_id == id &&
                    sys.warmup == 1 &&
                    (sys.status_id == 7 || sys.status_id == 5)
            ).length;
        },

        getShowOnCoordStations: (state) => {
            return state.stations.filter(
                (stations) => stations.show_on_coord == 1
            );
        },

        getTotalNodeCountBySystem: (state) => (payload) => {
            return state.campaignsystems.filter(
                (sys) =>
                    sys.system_id == payload.system_id &&
                    sys.campaign_id == payload.campaign_id
            ).length;
        },

        getTotalNodeCountBySystemByMultiCampaign: (state) => (payload) => {
            return state.campaignsystems.filter(
                (sys) =>
                    sys.system_id == payload.system_id &&
                    sys.warmup == 1 &&
                    sys.custom_campaign_id == payload.campaign_id
            ).length;
        },

        getHackingNodeCountBySystem: (state) => (payload) => {
            return state.campaignsystems.filter(
                (sys) =>
                    sys.system_id == payload.system_id &&
                    sys.status_id != 1 &&
                    sys.campaign_id == payload.campaign_id &&
                    (sys.status_id == 2 ||
                        sys.status_id == 3 ||
                        sys.status_id == 4 ||
                        sys.status_id == 8)
            ).length;
        },

        getHackingNodeCountBySystemByMultiCampaign: (state) => (payload) => {
            return state.campaignsystems.filter(
                (sys) =>
                    sys.system_id == payload.system_id &&
                    sys.warmup == 1 &&
                    sys.custom_campaign_id == payload.campaign_id &&
                    (sys.status_id == 2 ||
                        sys.status_id == 4 ||
                        sys.status_id == 8 ||
                        sys.status_id == 3)
            ).length;
        },

        getRedHackingNodeCountBySystem: (state) => (payload) => {
            return state.campaignsystems.filter(
                (sys) =>
                    sys.system_id == payload.system_id &&
                    sys.campaign_id == payload.campaign_id &&
                    (sys.status_id == 7 || sys.status_id == 5)
            ).length;
        },

        getRedHackingNodeCountBySystemByMultiCampaign: (state) => (payload) => {
            return state.campaignsystems.filter(
                (sys) =>
                    sys.system_id == payload.system_id &&
                    sys.warmup == 1 &&
                    sys.custom_campaign_id == payload.campaign_id &&
                    (sys.status_id == 5 || sys.status_id == 7)
            ).length;
        },

        getNodeValue: (state) => (payload) => {
            let total = state.campaignsystems.filter(
                (sys) => sys.system_id == payload.system_id
            ).length;

            let hack = state.campaignsystems.filter(
                (sys) =>
                    sys.system_id == payload.system_id &&
                    sys.status_id != 1 &&
                    sys.campaign_id == payload.campaign_id &&
                    sys.status_id != 7 &&
                    sys.status_id != 6
            ).length;

            let num = (hack / total) * 100;

            if (num == null) {
                return 0.0;
            } else {
                return num;
            }
        },

        getLoggingCampaignBySola: (state) => (sola_id) => {
            return state.loggingcampaign.filter(
                (log) => log.campaign_sola_system_id == sola_id
            );
        },

        getLoggingCampaignByCampaign: (state) => (campid) => {
            return state.loggingcampaign.filter(
                (log) =>
                    log.campaign_sola_system_id == null &&
                    log.campaign_id == campid
            );
        },

        getActiveRcStations: (state) => {
            return state.rcstations.filter(
                (station) => station.show_on_rc == 1
            );
        },

        getActiveChillStations: (state) => {
            return state.chillstations.filter(
                (station) => station.show_on_chill == 1
            );
        },

        getActiveWelpStations: (state) => {
            return state.welpstations.filter(
                (station) => station.show_on_welp == 1
            );
        },

        getLoggingAdmin: (state) => (campid) => {
            return state.loggingcampaign.filter(
                (log) =>
                    log.campaign_sola_system_id == null &&
                    log.campaign_id == campid
            );
        },

        getNodeJoinByNode: (state) => (sysid) => {
            return state.nodeJoin.filter(
                (node) => node.campaign_system_id == sysid
            );
        },

        getNodeJoinByNodeCount: (state) => (sysid) => {
            return state.nodeJoin.filter(
                (node) => node.campaign_system_id == sysid
            ).length;
        },

        getStartCampaignsById: (state) => (campid) => {
            return state.startcampaigns.filter((c) => c.id == campid);
        },

        getSystemTableExpandable: (state) => (payload) => {
            let count = state.campaignsystems.filter(
                (node) =>
                    node.system_id == payload.system_id &&
                    node.campaign_id == payload.campid &&
                    node.node_join_count > 0
            );
            if (count != null) {
                return count;
            } else {
                return [];
            }
        },

        getUsersOnNodeByID: (state) => (nodeid) => {
            let pull = state.campaignusers.filter(
                (user) => user.campaign_system_id == nodeid
            );
            let count = pull.length;
            if (count != 0) {
                return pull;
            } else {
                return [];
            }
        },

        getCharsOnNodeByID: (state) => (nodeid) => {
            let pull = state.userschars.filter(
                (char) => char.campaign_system_id == nodeid
            );
            let count = pull.length;
            if (count != 0) {
                return pull;
            } else {
                return [];
            }
        },

        getStationLogsByID: (state) => (stationid) => {
            let pull = state.loggingStations.filter(
                (s) => s.station_id == stationid
            );
            let count = pull.length;
            if (count != 0) {
                return pull;
            } else {
                return [];
            }
        },

        getStationSheetLogsByID: (state) => (stationid) => {
            let pull = state.stationList.filter((s) => s.id == stationid);
            let count = pull.length;
            if (count != 0) {
                return pull.logs;
            } else {
                return [];
            }
        },

        getEveCount: (state) => {
            return state.eveUserCount;
        },

        getSystemTableExpandableMulti: (state) => (payload) => {
            let count = state.campaignsystems.filter(
                (node) =>
                    node.system_id == payload.system_id &&
                    node.custom_campaign_id == payload.campid &&
                    node.node_join_count > 0
            );
            if (count != null) {
                return count;
            } else {
                return [];
            }
        },

        getStationItemsByStationID: (state) => (id) => {
            let pull = state.items.filter((item) => item.station_id == id);
            let count = pull.length;
            if (count != 0) {
                return pull;
            } else {
                return [];
            }
        },

        getCoreByStationID: (state) => (id) => {
            let pull = state.cores.filter((core) => core.station_id == id);
            let count = pull.length;
            if (count != 0) {
                return pull;
            } else {
                return [];
            }
        },

        getStationFitByStationID: (state) => (id) => {
            let pull = state.stationFits.filter((fit) => fit.id == id);
            let count = pull.length;
            if (count != 0) {
                return pull;
            } else {
                return "NO";
            }
        },

        getOwnHackingCharOnOp: (state) => (operationid) => {
            let pull = state.ownChars.filter(
                (u) =>
                    u.role_id == 1 &&
                    u.operation_id == operationid &&
                    u.user_status_id != 4
            );
            let count = pull.length;
            if (count != 0) {
                return pull;
            } else {
                return null;
            }
        },

        getOwnHackingCharOnOpAllHackers: (state) => (operationid) => {
            let pull = state.ownChars.filter(
                (u) => u.role_id == 1 && u.operation_id == operationid
            );
            let count = pull.length;
            if (count != 0) {
                return pull;
            } else {
                return null;
            }
        },

        getOpUsersOnTheWayAll: (state) => {
            let pull = state.opUsers.filter(
                (u) => u.role_id == 1 && u.user_status_id == 2
            );
            let count = pull.length;
            if (count != 0) {
                return pull;
            } else {
                return [];
            }
        },

        getOpUsersReadyToGoAll: (state) => {
            let pull = state.opUsers.filter(
                (u) => u.role_id == 1 && u.user_status_id == 3
            );
            let count = pull.length;
            if (count != 0) {
                return pull;
            } else {
                return [];
            }
        },

        // getTotalCampaignNodes: (state) => (campaignID) => {
        //     var total = 0;
        //     state.newCampaignSystems.forEach((c) => {
        //         let count = c.new_nodes.filter(
        //             (n) => n.campaign_id === campaignID
        //         ).length;
        //         total = total + count;
        //     });

        //     return total;
        // },

        getTotalCampaignNodesInfo: (state) => (campaignID) => {
            var total = 0;
            state.operationInfoPage.systems.forEach((c) => {
                let count = c.new_nodes.filter(
                    (n) => n.campaign_id === campaignID
                ).length;
                total = total + count;
            });

            return total;
        },

        // getBlueCampaignNodes: (state) => (campaignID) => {
        //     var blue = 0;

        //     state.newCampaignSystems.forEach((a) => {
        //         let nodes = a.new_nodes;

        //         nodes.forEach((b) => {
        //             if (
        //                 b.prime_node_user.length > 0 &&
        //                 b.campaign_id === campaignID
        //             ) {
        //                 blue = blue + 1;
        //             } else if (
        //                 (b.node_status.id == 8 || b.node_status.id == 4) &&
        //                 b.campaign_id === campaignID
        //             ) {
        //                 blue = blue + 1;
        //             }
        //         });
        //     });

        //     return blue;
        // },

        getBlueCampaignNodesInfo: (state) => (campaignID) => {
            var blue = 0;
            state.operationInfoPage.systems.forEach((a) => {
                let nodes = a.new_nodes;
                nodes.forEach((b) => {
                    if (
                        b.prime_node_user.length > 0 &&
                        b.campaign_id === campaignID
                    ) {
                        blue = blue + 1;
                    } else if (
                        (b.node_status.id == 8 || b.node_status.id == 4) &&
                        b.campaign_id === campaignID
                    ) {
                        blue = blue + 1;
                    }
                });
            });

            return blue;
        },

        // getRedCampaignNodes: (state) => (campaignID) => {
        //     var red = 0;

        //     state.newCampaignSystems.forEach((a) => {
        //         let nodes = a.new_nodes;

        //         nodes.forEach((b) => {
        //             if (
        //                 (b.node_status.id == 7 || b.node_status.id == 5) &&
        //                 b.campaign_id === campaignID
        //             ) {
        //                 red = red + 1;
        //             }
        //         });
        //     });

        //     return red;
        // },

        getRedCampaignNodesInfo: (state) => (campaignID) => {
            var red = 0;

            state.operationInfoPage.systems.forEach((a) => {
                let nodes = a.new_nodes;

                nodes.forEach((b) => {
                    if (
                        (b.node_status.id == 7 || b.node_status.id == 5) &&
                        b.campaign_id === campaignID
                    ) {
                        red = red + 1;
                    }
                });
            });

            return red;
        },

        getFleetInfo: (state) => (fleetID) => {
            var fleets = state.operationInfoPage.fleets;
            var data = fleets.find((f) => f.id == fleetID);
            return data;
        },

        getOperationInfoSystemList: (state) => {
            var ids = [];
            if (state.operationInfoPage.systems) {
                var systems = state.operationInfoPage.systems;

                systems.forEach((s) => {
                    if (s.pivot.new_operation_id == null) {
                        ids.push(s.id);
                    }
                });
            }
            return ids;
        },

        getOperationSystemInfo: (state) => (systemID) => {
            if (state.operationInfoPage.systems) {
                var systems = state.operationInfoPage.systems;
                var data = systems.find((s) => s.id == systemID);
                return data;
            }
            return [];
        },
    },
});
