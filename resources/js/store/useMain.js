import {
    defineStore
} from "pinia";
//

export const useMainStore = defineStore("main", {
    state: () => ({
        affiliations: [],
        dScan: [],
        dScanIsHistory: false,
        dScanHistory: [],
        dScanLiveLink: null,
        dScanLocalAlliance: [],
        dScanLocalCorp: [],
        dScanLocalAffiliation: [],
        dScanItemCategory: [],
        dScanItemGroup: [],
        dScanItemItem: [],
        dScanMessages: [],
        dScanChatWindowId: null,
        constellationlist: [],
        eveUserCount: 0,
        newSoloOperations: [],
        newSoloOperationsRegionList: [],
        newSoloOperationsConstellationList: [],
        newCampaignsList: [],
        newOperationList: [],
        size: [],
        startcampaigns: [],
        startcampaign: [],
        startcampaignJoin: [],
        stationListPullRegions: [],
        stationWatchListPullRegions: [],
        stationListFCRegions: [],
        stationWatchListFCRegions: [],
        stationListRegionList: [],
        stationWatchListRegionList: [],
        systemlist: [],
        structurelist: [],
        stationList: [],
        stationWatchList: [],
        stations: [],
        stagingSystems: [],
        towers: [],
        timers: [],
        timersRegions: [],
        ticklist: [],
        towerTypes: [],
        towerConstellation: [],
        loggingNewCampaign: [],
        rolesList: [],
        newOperationMessageOverlay: 0,
        newOperationMessageAddChar: false,
        moonList: [],
        towerChatWindowId: null,
        stationChatWindowId: null,
        user_name: null,
        user_id: null,
        users: [],
        roleListForWatchListSetting: [],
        stationWatchListAllianceList: [],
        stationWatchListItemList: [],
        loggingAdmin: [],
        missingCorpID: 0,
        missingCorpTick: "",
        webwayStartSystems: [],
        webwaySelectedStartSystem: {
            value: 30004759,
            text: "1DQ1-A",
        },
        campaignsystems: [],

        operationInfo: [],
        operationInfoPage: [],
        operationInfoUsers: [],
        operationInfoMumble: [],
        operationInfoDoctrines: [],
        operationInfoChatWindow: [],
        operationInfoRecon: [],
        allianceticklist: [],
        operationInfoReconFleetRoleList: [],
        operationInfoOperationList: [],
        operationInfoJamList: [],
        userList: [],

        regiondropdownlist: [],
        systemdropdownlist: [],
        constellationDropDownlist: [],

        operationInfoSetting: {
            showReconTable: true,
            showSystemTable: true,
            showCheckList: true,
            showFleets: true,
            showWatchedSystems: true,
        },

        operationInfoSettingOpetions: [{
            label: "Check List",
            value: "showCheckList",
        },
        {
            label: "Fleet Table",
            value: "showFleets",
        },
        {
            label: "Recon List",
            value: "showReconTable",
        },
        {
            label: "System Table",
            value: "showSystemTable",
        },

        {
            label: "Watched Systems Table",
            value: "showWatchedSystems",
        },
        ],

        newOperationInfo: [],
        newCampaignSystems: [],
        opUsers: [],
        ownChars: {},
        newCampaigns: [],
        operationUserList: [],
        campaignslist: [],
        watchListList: [],
        watchListListForUser: [],
        notifications: [],
    }),

    getters: {
        getStartJoinById: (state) => (id) => {
            let data = state.startcampaignJoin.filter(
                (c) => c.start_campaign_id == id
            );
            return data;
        },

        getStationMessages: (state) => (id) => {
            let station = state.stationList.find((s) => s.id == id);
            let messages = station.notes;
            if (messages) {
                return messages;
            }
            return [];
        },

        getUnreadMessageCount: (state) => (id) => {
            let station = state.stationList.find((s) => s.id == id);
            let messages = station.notes;
            let count = messages.filter(
                (m) =>
                    m.read_by &&
                    m.read_by.user_id &&
                    !m.read_by.user_id.includes(state.user_id)
            ).length;

            if (state.stationChatWindowId == id && count > 0) {
                axios({
                    method: "put",
                    url: "/api/sheetmessage/" + id + "/notes",
                    withCredentials: true,
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                });
            }
            return count;
        },

        getTowerMessages: (state) => (id) => {
            let tower = state.towers.find((s) => s.id == id);
            let messages = tower.notes;
            if (messages) {
                return messages;
            }
            return [];
        },

        getTowerUnreadMessageCount: (state) => (id) => {
            let tower = state.towers.find((s) => s.id == id);
            let messages = tower.notes;
            let count = messages.filter(
                (m) =>
                    m.read_by &&
                    m.read_by.user_id &&
                    !m.read_by.user_id.includes(state.user_id)
            ).length;

            if (state.towerChatWindowId == id && count > 0) {
                axios({
                    method: "put",
                    url: "/api/towermessage/" + id + "/notes",
                    withCredentials: true,
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                });
            }
            return count;
        },



        getDscanUnreadMessageCount: (state) => (id) => {
            let messages = state.dScanMessages;
            let count = messages.filter(
                (m) =>
                    m.read_by &&
                    m.read_by.user_id &&
                    !m.read_by.user_id.includes(state.user_id)
            ).length;

            if (state.dScanChatWindowId == id && count > 0) {
                axios({
                    method: "put",
                    url: "/api/dscanmessage/" + id + "/notes",
                    withCredentials: true,
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                });
            }
            return count;
        },




        getOperationInfoUnreadMessageCount: (state) => {

            let messages = state.operationInfoPage.messages ? state.operationInfoPage.messages : [];
            let count = messages.filter(
                (m) =>
                    m.read_by &&
                    m.read_by.user_id &&
                    !m.read_by.user_id.includes(state.user_id)
            ).length;

            if (state.operationInfoChatWindow == state.operationInfoPage.id && count > 0) {
                axios({
                    method: "put",
                    url: "/api/operationinfopagemessage/" + state.operationInfoPage.id + "/notes",
                    withCredentials: true,
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                });
            }
            return count;
        },

        // {"text":"Yuzier","value":30000007}
        getOperationInfoSystemList: (state) => {
            var ids = [];
            if (state.operationInfoPage.systems) {
                var systems = state.operationInfoPage.systems;

                systems.forEach((s) => {
                    if (s.pivot.new_operation_id == null) {
                        ids.push({
                            text: s.system_name,
                            value: s.id
                        });
                    }
                });
            }
            return ids;
        },

        getOperationInfoWatchedSystemList: (state) => {
            var ids = [];
            if (state.operationInfoPage.systems) {
                var systems = state.operationInfoPage.watch_systems;

                systems.forEach((s) => {
                    if (s.pivot.new_operation_id == null) {
                        ids.push({
                            text: s.system_name,
                            value: s.id
                        });
                    }
                });
            }
            return ids;
        },

        getOperationInfoTableStatus: (state) => {
            let values_array = [];

            if (state.operationInfoPage.check_list) {
                values_array.push("check_list");
            }
            if (state.operationInfoPage.fleet_table) {
                values_array.push("fleet_table");
            }
            if (state.operationInfoPage.recon_table) {
                values_array.push("recon_table");
            }
            if (state.operationInfoPage.system_table) {
                values_array.push("system_table");
            }

            if (state.operationInfoPage.watched_system_table) {
                values_array.push("watched_system_table");
            }

            return values_array;
        },

        getOperationInfoTableSettings: (state) => {
            let values_array = [];

            if (state.operationInfoSetting.showReconTable) {
                values_array.push("showReconTable");
            }
            if (state.operationInfoSetting.showSystemTable) {
                values_array.push("showSystemTable");
            }
            if (state.operationInfoSetting.showCheckList) {
                values_array.push("showCheckList");
            }
            if (state.operationInfoSetting.showFleets) {
                values_array.push("showFleets");
            }

            if (state.operationInfoSetting.showWatchedSystems) {
                values_array.push("showWatchedSystems");
            }

            return values_array;
        },

        getOperationSystemInfo: (state) => (systemID) => {
            if (state.operationInfoPage.systems) {
                var systems = state.operationInfoPage.systems;
                var data = systems.find((s) => s.id == systemID);
                return data;
            }
            return [];
        },

        getTotalCampaignNodes: (state) => (campaignID) => {
            var total = 0;
            state.newCampaignSystems.forEach((c) => {
                let count = c.new_nodes.filter(
                    (n) => n.campaign_id === campaignID
                ).length;
                total = total + count;
            });

            return total;
        },

        getRedCampaignNodes: (state) => (campaignID) => {
            var red = 0;

            state.newCampaignSystems.forEach((a) => {
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

        getBlueCampaignNodes: (state) => (campaignID) => {
            var blue = 0;

            state.newCampaignSystems.forEach((a) => {
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

    },
    actions: {
        async updateTickList(ticker) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/addmissingcorp/" + ticker,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.ticklist = res.data.ticklist;
            this.misingCorpID = res.data.corpID;
            this.missingCorpTicker = res.data.corpTicker;
        },

        async getStructureList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/structurelist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.structurelist = res.data.structurelist;
        },

        async getSystemList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/systemlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.systemlist = res.data.systemlist;
        },

        async getTickList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/ticklist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.ticklist = res.data.ticklist;
        },

        markOver(id) {
            var item = this.timers.find((item) => item.id === id);
            item.status = 1;
        },

        async getLoginInfo() {
            let res = await axios({
                method: "get",
                url: "/api/user/info",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.user_name = res.data.data.username;
            this.user_id = res.data.data.user_id;
        },

        async geteveusercount() {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/eveusercount",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.eveUserCount = res.data.count;
        },

        async getWebwayStartSystems() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getwebwaystartsystems",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.webwayStartSystems = res.data.systems;
        },

        async getSoloOperationList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/solooperationlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.newSoloOperations = res.data.solooplist;
            this.newSoloOperationsRegionList = res.data.regionList;
            this.newSoloOperationsConstellationList =
                res.data.constellationList;
        },

        updateSoloOperationList(data) {
            const item = this.newSoloOperations.find(
                (item) => item.id === data.id
            );
            const count = this.newSoloOperations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.newSoloOperations.push(data);
            }
        },

        updateWebwaySelectedStartSystem(data) {
            this.webwaySelectedStartSystem = data;
        },

        async getTimerDataAll() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/timers",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.timers = res.data.timers;
        },

        async getTimerDataAllRegion() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/timersregions",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.timersRegions = res.data.timersregions;
        },

        async getConstellationList() {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/constellations",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.constellationlist = res.data.constellationlist;
        },

        addoperationlist(data) {
            const check = this.newOperationList.find(
                (station) => station.id == data.id
            );
            if (check != null) {
                Object.assign(check, data);
            } else {
                this.newOperationList.push(data);
            }
        },

        updateoperationlist(data) {
            const item = this.newOperationList.find(
                (item) => item.id === data.id
            );
            const count = this.newOperationList.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            }
        },

        deleteoperationfromlist(num) {
            const count = this.newOperationList.filter(
                (o) => o.id == num
            ).length;
            if (count > 0) {
                this.newOperationList = this.newOperationList.filter(
                    (o) => o.id != num
                );
            }
        },

        async getNewCampaignsList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/newcampaignslist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.newCampaignsList = res.data.campaignslist;
        },

        async getCustomOperationList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.newOperationList = res.data.operations;
        },

        async getStartCampaigns() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/startcampaigns",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.startcampaigns = res.data.campaigns;
        },

        async getStartCampaign(id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/startcampaigns/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.startcampaign = res.data.campaign;
        },

        async getStartCampaignJoinData() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/startcampaignjoin",
                data: this.picked,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.startcampaignJoin = res.data.value;
        },

        async getUsersChars(user_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/campaignusersrecordsbychar/" + user_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.userschars = res.data.users;

        },

        async getCampaignUsersRecords(id) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/campaignusersrecords/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            if (res.data.length != 0) {
                this.campaignusers = res.data.users;
            }


        },

        async getStationRegionLists() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getregionlists",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.webwayStartSystems = res.data.webwayStartSystems;
        },


        async getStationWatchListNeededInfo() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/watchlist/getneededinfo",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.stationWatchListPullRegions = res.data.pull;
            this.stationWatchListFCRegions = res.data.fcs;
            this.stationWatchListRegionList = res.data.regionlist;
            this.systemlist = res.data.systemlist;
            this.stationWatchList = res.data.stationlist;
            this.constellationDropDownlist = res.data.constellationDropDownlist;
            this.webwayStartSystems = res.data.webwayStartSystems;
            this.regiondropdownlist = res.data.regiondropdownlist;
            this.systemdropdownlist = res.data.systemdropdownlist;
            this.roleListForWatchListSetting = res.data.roles;
            this.userList = res.data.userList;
            this.stationWatchListAllianceList = res.data.allianceList;
            this.stationWatchListItemList = res.data.itemList;

        },

        async updateStationWatchListNeededInfo(data) {
            this.stationWatchListPullRegions = data.pull;
            this.stationWatchListFCRegions = data.fcs;
            this.stationWatchListRegionList = data.regionlist;
            this.systemlist = data.systemlist;
            this.stationWatchList = data.stationlist;
            this.constellationDropDownlist = data.constellationDropDownlist;
            this.webwayStartSystems = data.webwayStartSystems;
            this.regiondropdownlist = data.regiondropdownlist;
            this.systemdropdownlist = data.systemdropdownlist;
            this.stationWatchListAllianceList = data.allianceList;
            this.stationWatchListItemList = data.itemList;
        },

        updateStationDropDown(data) {
            const item = this.stationList.find((item) => item.value === data.value);
            const count = this.stationList.filter(
                (item) => item.value === data.value
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.stationList.push(data);
            }
        },


        async getStationList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/stationsheet",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.stationList = res.data.stations;
        },

        updateStationList(data) {
            const item = this.stationList.find((item) => item.id === data.id);
            const count = this.stationList.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.stationList.push(data);
            }
        },

        deleteStationSheetNotification(id) {
            let index = this.stationList.filter((s) => s.id != id);
            this.stationList = index;
        },

        updateStationNotification(data) {
            const item = this.stations.find((item) => item.id === data.id);
            const count = this.stations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.stations.push(data);
            }
        },

        deleteStationNotification(id) {
            let newList = this.stations.filter((s) => s.id != id);
            this.stations = newList;
        },

        async getStationData() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/stationrecords",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.stations = res.data.stations;
        },

        async getStationDataByUserId() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/stationrecordsbyid",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.stations = res.data.stations;
        },

        async getTowerData() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/towersrecords",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.towers = res.data.towers;
        },

        async getTowerFilter() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/towertypelist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.towerTypes = res.data.towerList;
            this.systemlist = res.data.systemList;
        },

        async getMoonList(id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/tower/moonlist/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.moonList = res.data.moonList;
        },

        updateTowers(data) {
            const item = this.towers.find((item) => item.id === data.id);
            const count = this.towers.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.towers.push(data);
            }
        },

        deleteTower(id) {
            const items = this.towers.find((t) => t.id != id);
            this.towers = items;
        },

        async getUsers() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allusersroles",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.users = res.data.usersroles;
        },

        async getRoles() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/roles",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.rolesList = res.data.roles;
        },

        async getLoggingAdmin() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/checkadmin",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.loggingAdmin = res.data.logs;
        },

        async getOperationSheetInfo() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfosheet",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfo = res.data.opinfo;
        },

        updateOperationPageInfo(data) {
            const item = this.operationInfo.find((item) => item.id === data.id);
            const count = this.operationInfo.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.operationInfo.push(data);
            }
        },

        removeOperationPageInfo(id) {
            let newList = this.operationInfo.filter((o) => o.id != id);
            this.operationInfo = newList;
        },

        async getOperationSheetInfoPage(id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfopage/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoPage = res.data.data;
        },

        async getOperationUsers() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfousers",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoUsers = res.data.users;
        },

        async getOperationInfoMumble() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfomumble",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoMumble = res.data.mumble;
        },

        async getOperationInfoDoctrines() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfodoctrines",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoDoctrines = res.data.doc;
        },

        async getOperationRecon() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinforecon",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoRecon = res.data.recon;
        },

        async getAllianceTickList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allianceticklist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.allianceticklist = res.data.allianceticklist;
        },

        async getOperationInfoReconRoles() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfofleetreconrole",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoReconFleetRoleList = res.data.roleList;
        },

        async getOperationSheetInfoOperationList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationlistinfoop",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoOperationList = res.data.operations;
        },

        async getOperationInfoJamList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfojammerstatus",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoJamList = res.data.jam;
        },

        async getUserList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/users",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.userList = res.data.users;
        },

        updateOperationSheetInfoPage(data) {
            const item = this.operationInfoPage;
            Object.assign(item, data);
        },

        updateOperationSheetInfoPageFleet(data) {
            const count = this.operationInfoPage.fleets.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                const item = this.operationInfoPage.fleets.find(
                    (f) => f.id === data.id
                );
                Object.assign(item, data);
            } else {
                this.operationInfoPage.fleets.push(data);
            }
        },

        updateOperationReconSolo(data) {
            const count = this.operationInfoPage.recons.filter(
                (item) => item.id === data.id
            ).length;

            if (count > 0) {
                const item = this.operationInfoPage.recons.find(
                    (f) => f.id === data.id
                );
                Object.assign(item, data);
            } else {
                this.operationInfoPage.recons.push(data);
            }

            const countRecon = this.operationInfoRecon.filter(
                (item) => item.id === data.id
            ).length;

            if (countRecon > 0) {
                const item = this.operationInfoRecon.find(
                    (f) => f.id === data.id
                );
                Object.assign(item, data);
            } else {
                this.operationInfoRecon.push(data);
            }
        },

        deleteOperationSheetInfoPageFleet(id) {
            let check = this.operationInfoPage.fleets.filter(
                (e) => e.id == id
            ).length;
            if (check > 0) {
                this.operationInfoPage.fleets =
                    this.operationInfoPage.fleets.filter((e) => e.id != id);
            }
        },

        updateOperationMessage(data) {
            this.operationInfoPage.messages = data;
            this.operationInfoMessageCount = this.operationInfoMessageCount + 1;
        },

        removeOperationReconSolo(data) {
            var info = this.operationInfoPage.recons.filter(
                (r) => r.id != data.id
            );
            this.operationInfoPage.recons = info;

            const count = this.operationInfoRecon.filter(
                (item) => item.id === data.id
            ).length;

            if (count > 0) {
                const item = this.operationInfoRecon.find(
                    (f) => f.id === data.id
                );
                Object.assign(item, data);
            } else {
                this.operationInfoRecon.push(data);
            }
        },

        updateOperationSoloSystems(data) {
            const count = this.operationInfoPage.systems.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                const item = this.operationInfoPage.systems.find(
                    (f) => f.id === data.id
                );
                Object.assign(item, data);
            } else {
                this.operationInfoPage.systems.push(data);
            }
        },

        clearOperationInfoSolo() {
            this.operationInfoPage = [];
            this.operationInfoUsers = [];
            this.operationInfoMumble = [];
            this.operationInfoDoctrines = [];
            this.operationInfoRecon = [];
            this.operationInfoReconFleetRoleList = [];
            this.operationInfoOperationList = [];
            this.operationInfoJamList = [];
        },

        updateNewCampaignSystemInfo(data) {
            const item = this.operationInfoPage.systems.find(
                (item) => item.id === data.id
            );
            const count = this.operationInfoPage.systems.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.operationInfoPage.systems.push(data);
            }
        },

        updateOperationCampaignsSolo(data) {
            const item = this.operationInfoPage.campaigns.find(
                (item) => item.id === data.id
            );
            const count = this.operationInfoPage.campaigns.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.operationInfoPage.campaigns.push(data);
            }
        },

        setOwnOptions(data) {
            for (const prop in this.operationInfoSetting) {
                // Check if the property name is in the incoming array
                if (data.includes(prop)) {
                    // Set the property value to true
                    this.operationInfoSetting[prop] = true;
                } else {
                    // Set the property value to false
                    this.operationInfoSetting[prop] = false;
                }
            }
        },

        async getOperationInfo(id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfo/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.newOperationInfo = res.data.data;
            this.newCampaignSystems = res.data.systems;
            this.opUsers = res.data.opUsers;
            this.ownChars = res.data.ownChars;
            this.newCampaigns = res.data.data.campaign;
            this.operationUserList = res.data.userList;
        },

        async getCampaignsList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/campaignslist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.campaignslist = res.data.campaignslist;
        },

        async getCampaignsLogs(op_id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/newoperationlogs/" + op_id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.loggingNewCampaign = res.data.logs
        },

        updateNewCampaigns(data) {
            const item = this.newCampaigns.find((item) => item.id === data.id);
            const count = this.newCampaigns.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.newCampaigns.push(data);
            }
        },

        removeCharfromOpList(id) {
            this.opUsers = this.opUsers.filter((e) => e.id != id);
        },

        updateOpChar(data) {
            const item = this.opUsers.find((item) => item.id === data.id);
            const count = this.opUsers.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.opUsers.push(data);
            }
        },

        updateNewCampaignSystem(data) {
            const item = this.newCampaignSystems.find(
                (item) => item.id === data.id
            );
            const count = this.newCampaignSystems.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.newCampaignSystems.push(data);
            }
        },

        updateNewOwnChar(data) {
            const item = this.ownChars.find((item) => item.id === data.id);
            const count = this.ownChars.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.ownChars.push(data);
            }
        },

        removeCharfromOwnList(id) {
            let check = this.ownChars.filter((e) => e.id == id).length;
            if (check > 0) {
                this.ownChars = this.ownChars.filter((e) => e.id != id);
            }
        },

        async getCampaignSystemsRecords() {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/campaignsystemsrecords",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.campaignsystems = res.data.systems;

        },

        async getWatchListList() {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/watchlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.watchListList = res.data.watchList;
        },

        updateWatchListList(data) {
            const item = this.watchListList.find((item) => item.id === data.id);
            const count = this.watchListList.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.watchListList.push(data);
            }
        },

        deleteWatchListList(id) {
            this.watchListList = this.watchListList.filter((e) => e.id != id);
        },


        async getWatchListListForUser() {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/watchlist/byuser",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.watchListListForUser = res.data.watchList;
        },

        async getDscan(id) {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/dscan/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.dScan = res.data.dscan;
            this.dScanLocalCorp = res.data.corpsTotal;
            this.dScanLocalAlliance = res.data.allianceTotal;
            this.dScanLocalAffiliation = res.data.affiliationTotal;
            this.dScanItemCategory = res.data.categoryTotals
            this.dScanItemGroup = res.data.groupTotals
            this.dScanItemItem = res.data.itemTotals
            this.dScanIsHistory = res.data.history;
            this.dScanIsHistory ? this.dScanHistory = res.data.allHistory : this.dScanHistory = res.data.dscan.history;
            this.dScanIsHistory ? this.dScanLiveLink = res.data.liveDscan : null;
            this.dScanMessages = res.data.messages

        },

        updateLocalDscan(data) {
            const item = this.dScan.locals.find((item) => item.id === data.id);
            const count = this.dScan.locals.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.dScan.locals.push(data);
            }
        },

        async getAffilationTable() {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/affiliation",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.affiliations = res.data.affiliations;

        },

        updateAffilation(data) {
            const item = this.affiliations.find((item) => item.id === data.id);
            const count = this.affiliations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.affiliations.push(data);
            }
        },
        async getStagingSystemInfo() {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/staging",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.stagingSystems = res.data;

        },

        updateStagingSystem(data) {
            const item = this.stagingSystems.find((item) => item.id === data.id);
            const count = this.stagingSystems.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.stagingSystems.push(data);
            }
        },

        deleteStagingSystem(id) {
            this.stagingSystems = this.stagingSystems.filter((e) => e.id != id);
        },


        updateNotification(data) {
            const item = this.notifications.find(
                (item) => item.id === data.id
            );
            Object.assign(item, data);
        },

        async getNotifications() {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/notifications",
                // data: {id: varID},
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.notifications = res.data;

        },
    },
});
