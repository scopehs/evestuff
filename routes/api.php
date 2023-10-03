<?php

use App\Http\Controllers\AffilationController;
use App\Http\Controllers\AllianceController;
use App\Http\Controllers\AmmoRequestController;
use App\Http\Controllers\AmmoRequestRecordsController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignJoinsController;
use App\Http\Controllers\CampaignRecordsController;
use App\Http\Controllers\CampaignSolaSystemsController;
use App\Http\Controllers\CampaignSystemRecordsController;
use App\Http\Controllers\CampaignSystemsController;
use App\Http\Controllers\CampaignSystemUsersController;
use App\Http\Controllers\CampaignUserController;
use App\Http\Controllers\CampaignUserRecordsController;
use App\Http\Controllers\ChillStationController;
use App\Http\Controllers\ConstellationsController;
use App\Http\Controllers\CoordSheetController;
use App\Http\Controllers\CorpController;
use App\Http\Controllers\CustomCampaignsController;
use App\Http\Controllers\DscanController;
use App\Http\Controllers\DscanLocalController;
use App\Http\Controllers\EveController;
use App\Http\Controllers\FeedBackController;
use App\Http\Controllers\FleetTypeController;
use App\Http\Controllers\HotRegionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KeyFleetJoinControllerController;
use App\Http\Controllers\KeyTypeController;
use App\Http\Controllers\LoggingController;
use App\Http\Controllers\MoonController;
use App\Http\Controllers\NewCampaignsController;
use App\Http\Controllers\NewOperationsController;
use App\Http\Controllers\NewSystemNodeController;
use App\Http\Controllers\NewUserNodeController;
use App\Http\Controllers\NodeJoinsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationRecordsController;
use App\Http\Controllers\OperationInfoController;
use App\Http\Controllers\OperationInfoDoctrineController;
use App\Http\Controllers\OperationInfoFleetController;
use App\Http\Controllers\OperationInfoFleetReconRoleController;
use App\Http\Controllers\OperationInfoJammedStatusController;
use App\Http\Controllers\OperationInfoMumbleController;
use App\Http\Controllers\OperationInfoReconController;
use App\Http\Controllers\OperationInfoSheetController;
use App\Http\Controllers\OperationInfoUserController;
use App\Http\Controllers\OperationUserController;
use App\Http\Controllers\RcFcUsersController;
use App\Http\Controllers\RcGsolUsersController;
use App\Http\Controllers\RcReconUsersController;
use App\Http\Controllers\RcSheetController;
use App\Http\Controllers\ReconTaskController;
use App\Http\Controllers\ReconTaskSystemController;
use App\Http\Controllers\ReconTaskSystemRecordsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StartCampaignController;
use App\Http\Controllers\StartCampaignJoinController;
use App\Http\Controllers\StartCampaignSystemController;
use App\Http\Controllers\StartCampaignSystemRecordsController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\StationNotesController;
use App\Http\Controllers\StationRecordsController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\testController;
use App\Http\Controllers\TimerController;
use App\Http\Controllers\TowerController;
use App\Http\Controllers\TowerNotesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserKeyJoinControllerController;
use App\Http\Controllers\UserRolesRecordsController;
use App\Http\Controllers\WebWayController;
use App\Http\Controllers\WebWayStartSystemsContorller;
use App\Http\Controllers\WelpStationController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|ffff
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/timers','TimerController@getTimerData');

// Route::middleware('ffffauth:api')->get('/notifications', function (Request $request) {
//     return $request->notifications();
// });
// Route::get('/notifications','NotificationRecordsController@index');

Route::middleware('auth:sanctum')->group(function () {

    //BROISES FEED//

    Route::controller(WebWayController::class)->group(function () {
        Route::post('/webway', 'getWebway');
    });

    Route::controller(AppController::class)->group(function () {
        Route::post('/url', 'url');
    });

    Route::controller(testController::class)->group(function () {
        Route::post('/brois', 'notifications');
        Route::get('/test', 'key');
        Route::post('/testscoreupdate/{id}', 'testUpdateScore');
        Route::post('/testscorerun', 'testRunScore');
        Route::post('/testclearalldata', 'testClearCampaigns');
        Route::get('/teststationitempull/{id}', 'testStationItemPull');
        Route::post('/testdscan', 'dscanTest');
        Route::get('/testdscan/{id}', 'testDscanPull');
        Route::post('/testdscanlocal', 'testDscanLocal');
    });

    Route::controller(EveController::class)->group(function () {
        Route::get('/eveusercount', 'playerCount');
    });

    Route::controller(AffilationController::class)->group(function () {
        Route::get('/affiliation', 'index');
        Route::get('/affiliation/{id}', 'show');
        Route::post('/affiliation', 'store');
        Route::put('/affiliation/{id}', 'update');
        Route::delete('/affiliation/{id}', 'destroy');
        Route::post('/affiliation/alliance/{id}', 'addAlliance');
        Route::delete('/affiliation/alliance/{id}', 'removeAlliance');
    });

    Route::controller(DscanLocalController::class)->group(function () {
        Route::post('/dscanlocal', 'addNewLocal');

        Route::post('/dscanlocal/{link}', 'update');
        Route::get('/dscanlocal/{link}', 'show');
        Route::get('/dscanlocal', 'dscanPullAll');
    });


    Route::controller(DscanController::class)->group(function () {
        Route::post('/dscan', 'checkInputNew');
        Route::post('/dscan/{link}', 'checkInputUpdate');
        Route::get('/dscan/{link}', 'show');
        Route::get('/dscan', 'dscanPullAll');
        Route::post('/dscan/localupdate/{link}', 'updateLocalNamePull');
        Route::post('/notifications/dscan/{id}', 'checkInputNewNotfication');
    });

    Route::controller(RcSheetController::class)->group(function () {
        Route::post('/rcInput', 'RCInput');
    });

    Route::controller(StagingSystemContoller::class)->group(function () {
        Route::post('/staging', 'store');
        Route::put('/staging/{id}', 'updateStaging');
        Route::delete('/staging/{id}', 'destroy');
        Route::get('/staging', 'index');
        Route::get('/staging/{id}', 'show');
    });

    Route::controller(StationNotesController::class)->group(function () {
        Route::put('/sheetmessage/{id}', 'updateMessage');
        Route::delete('/sheetmessage/{id}', 'destroy');
        Route::put('/sheetmessage/{id}/notes', 'addReadBy');
    });

    Route::controller(TowerNotesController::class)->group(function () {
        Route::put('/towermessage/{id}', 'updateMessage');
        Route::delete('/towermessage/{id}', 'destroy');
        Route::put('/towermessage/{id}/notes', 'addReadBy');
    });

    Route::controller(DscanMessageController::class)->group(function () {
        Route::put('/dscanmessage/{id}', 'updateMessage');
        Route::delete('/dscanmessage/{id}', 'destroy');
        Route::put('/dscanmessage/{id}/notes', 'addReadBy');
    });



    Route::controller(StationController::class)->group(function () {
        Route::get('/reconpullregion', 'reconRegionPull');
        Route::post('/taskrequest', 'taskRequest');
        Route::put('/stationsheet/updatestationnotification/{id}', 'updateStationSheet');
        Route::put('/timer/addTimer/{id}', 'addStationTimer');
        Route::put('/timer/editStation/{id}', 'editStation');
        Route::put('/timer/statusupdate/{id}', 'updateTimerStatus');
        Route::put('/stationname', 'reconPullbyname');
        Route::put('/stationnew', 'store');
        Route::get('/stationsheet', 'stationSheet');
        Route::put('/stationsheetupdatewebway/{id}', 'updateStationSheetWebway');
    });

    Route::controller(NotificationRecordsController::class)->group(function () {
        Route::get('/notifications/{region_id}', 'regionLink');
    });

    Route::controller(NotificationController::class)->group(function () {
        Route::put('/notifications/{id}', 'update');
        Route::get('/notifications', 'getNotifications');
    });

    Route::controller(TimerController::class)->group(function () {
        Route::get('/timers', 'getTimerData');
        Route::get('/timersregions', 'getTimerDataRegions');
    });

    Route::controller(CampaignRecordsController::class)->group(function () {
        Route::get('/campaigns', 'index');
        Route::get('/campaignsregion', 'campaignslistRegion');
        Route::get('/campaignslist', 'campaignslist');
        Route::put('/campaigns/{id}', 'update');
    });

    Route::controller(CampaignUserRecordsController::class)->group(function () {
        Route::get('/campaignusersrecords/{id}', 'show');
        Route::get('/campaignusersrecordsbychar/{id}', 'bychar');
        Route::put('/campaignusersrecords/{id}/{campid}', 'update');
        Route::post('/campaignusersrecords/{id}/{campid}', 'store');
    });

    Route::controller(CampaignUserController::class)->group(function () {
        Route::post('/campaignusers/{campid}', 'store');
        Route::put('/campaignusers/{id}/{campid}', 'update');
        Route::put('/campaignusersadd/{id}/{campid}', 'updateadd');
        Route::put('/campaignusersremove/{id}/{campid}', 'updateremove');
        Route::delete('/campaignusers/{id}/{campid}/{siteid}', 'destroy');
    });

    Route::controller(CampaignSystemRecordsController::class)->group(function () {
        Route::get('/campaignsystemsrecords', 'index');
        Route::get('/campaignsystemsrecords/{id}', 'show');
        Route::put('/campaignsystemsrecords/{id}/{campid}', 'update');
        Route::post('/campaignsystemsrecords/{id}/{campid}', 'store');
        Route::delete('/campaginsystemsrecords/{id}/{campid}', 'destroy');
        Route::post('/campaignpriority/{id}', 'updatePriority');
    });

    Route::controller(CampaignSystemsController::class)->group(function () {
        Route::post('/campaignsystemload', 'load');
        Route::post('/afterextranodeload', 'afterExtraNodeLoad');
        Route::post('/campaignsystems/{campid}', 'store');
        Route::put('/campaignsystems/{id}/{campid}', 'update');
        Route::put('/campaignsystemsnodemessage/{id}', 'updateMessage');
        Route::put('/campaignsystemsattackmessage/{id}', 'updateAttackMessage');
        Route::put('/campaignsystemsupdatetime/{id}/{campid}', 'updatetime');
        Route::delete('/campaignsystems/{id}/{campid}', 'destroy');
        Route::put('/campaignsystemremovechar/{campid}', 'removechar');
        Route::put('/campaignsystemmovechar/{campid}', 'movechar');
        Route::get('/campaignsystemcheckaddchar/{campid}', 'checkAddChar');
        Route::post('/campaignsystemuserskick/{campid}', 'kickUser');
        Route::get('/campaignsystemfinished/{campid}', 'finishCampaign');
        Route::get('/mcampaignsystemfinished/{campid}', 'mfinishCampaign');
        Route::put('/campaignsystemstidi/{sysid}/{campid}', 'tidi');
        Route::put('campaignsystemstidimulti/{sysid}/{campid}', 'tidimulti');
    });

    Route::controller(CustomCampaignsController::class)->group(function () {
        Route::post('/multicampaigns/{campid}/{name}', 'store');
        Route::get('/multicampaigns', 'index');
        Route::delete('/multicampaigns/{id}', 'destroy');
        Route::post('/multicampaignsedit/{campid}/{name}', 'edit');
    });

    Route::controller(SystemController::class)->group(function () {
        Route::get('/systemsinconstellation/{id}', 'systemsinconstellation');
        Route::get('/systemlist', 'index');
        Route::post('/checkedat/{systemID}', 'checkedAt');
        Route::post('/edittidi/{systemID}', 'editTidi');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::get('/users', 'index');
        Route::get('/user/info', 'loginInfo');
    });

    Route::controller(UserRolesRecordsController::class)->group(function () {
        Route::get('/userrolerecord', 'index');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::get('/roles', 'index');
        Route::get('/allusersroles', 'getAllUsersRoles');
        Route::put('/rolesadd', 'addRole');
        Route::put('/rolesremove', 'removeRole');
    });

    Route::controller(CampaignSystemUsersController::class)->group(function () {
        Route::post('/campaignsystemusers/{campid}', 'store');
        Route::get('/campaignsystemusers/{campid}', 'index');
        Route::delete('/campaignsystemusers/{id}/{campid}', 'destroy');
    });

    Route::controller(CampaignJoinsController::class)->group(function () {
        Route::get('/campaignjoin', 'index');
        Route::get('/campaignjoinbyid/{campid}', 'indexByID');
        Route::get('/campaignjoin/{id}', 'show');
        Route::get('/campaignjoinlist/{id}', 'list');
        Route::get('/campaignjoinsystems/{id}', 'campaignSystems');
    });

    Route::controller(CampaignSolaSystemsController::class)->group(function () {
        Route::get('/campaignsolasystems', 'index');
        Route::put('/campaignsolasystems/{solaid}/{campid}', 'update');
    });

    Route::controller(NodeJoinsController::class)->group(function () {
        Route::get('/nodejoin/{campid}', 'tableindex');
        Route::post('/nodejoin/{campid}', 'store');
        Route::put('/nodejoinupdate/{id}/{campid}', 'update');
        Route::put('/removecharfromnode/{id}/{campid}', 'removeCharForNode');
        Route::put('/addchartonodeadmin/{id}/{campid}', 'addCharToNodeAdmin');
        Route::put('/deleteextranode/{id}/{campid}', 'deleteExtraNode');
    });

    Route::controller(StationRecordsController::class)->group(function () {
        Route::get('/stationrecords', 'indexShowOnMove');
        Route::get('/stationrecordsbyid', 'indexByIdShowOnMove');
        Route::put('/stationrecords/{id}', 'update');
    });

    Route::controller(TowerController::class)->group(function () {
        Route::get('/towersrecords', 'index');
        Route::put('/towerrecords/{id}', 'update');
        Route::post('/towerrecords', 'store');
        Route::get('/towertypelist', 'towerTypeList');
        Route::get('/tower/moonlist/{id}', 'getMoons');
        Route::put('/tower/updatestatus/{id}', 'updateStatus');
        Route::put('/tower/updatetext/{id}', 'updateText');
        Route::post('/tower/addfit/{id}', 'addFit');
    });

    Route::controller(FeedBackController::class)->group(function () {
        Route::post('/feedback', 'store');
        Route::get('/feedback', 'index');
        Route::delete('/feedback/{id}', 'destroy');
    });

    Route::controller(LoggingController::class)->group(function () {
        Route::post('/checkaddnode/{campid}', 'NodeAdd');
        Route::post('/checkdeletenode/{campid}', 'NodeDelete');
        Route::get('/checkjoinleavecampaign/{campid}/{charid}/{logtype}', 'joinleaveCampaign');
        Route::get('/mcheckjoinleavecampaign/{campid}/{charid}/{logtype}', 'mjoinleaveCampaign');
        Route::put('/checklastedchecked/{campid}', 'lastchecked');
        Route::put('/checkscout/{campid}', 'systemscout');
        Route::put('/checkaddremovechar/{campid}', 'addremovechar');
        Route::put('/checkroleaddremove', 'addRemoveRoles');
        Route::get('/checkcampaign/{campid}', 'logCampaign');
        Route::get('/checkadmin', 'logAdmin');
        Route::get('/rcadminlogs', 'rcSheetLogging');
        Route::get('/stationlogs', 'stationLogging');
    });

    Route::controller(CorpController::class)->group(function () {
        Route::get('/ticklist', 'index');
        Route::get('/addmissingcorp/{name}', 'addMissingCorp');
    });

    Route::controller(AllianceController::class)->group(function () {
        Route::get('/allianceticklist', 'allianceTickList');
    });

    Route::controller(ItemController::class)->group(function () {
        Route::get('/structurelist', 'index');
        Route::get('/towerlist', 'towerlist');
    });

    Route::controller(MoonController::class)->group(function () {
        Route::get('/moons/{sysid}', 'bySystem');
    });

    Route::controller(AmmoRequestController::class)->group(function () {
        Route::post('/ammorequest', 'store');
        Route::get('/loadammorequestdata', 'loadAmmoRequestData');
        Route::delete('/ammorequestdelete/{id}', 'destroy');
        Route::post('/ammorequestupdate/{id}', 'update');
    });

    Route::controller(AmmoRequestRecordsController::class)->group(function () {
        Route::get('/ammorequestrecords', 'index');
    });

    Route::controller(ReconTaskController::class)->group(function () {
        Route::post('/recontask', 'store');
        Route::get('/recontask', 'index');
        Route::delete('/recontask/{id}', 'destroy');
    });

    Route::controller(ReconTaskSystemRecordsController::class)->group(function () {
        Route::get('/recontasksystems', 'index');
    });

    Route::controller(ReconTaskSystemController::class)->group(function () {
        Route::put('/recontasksystemtimeupdate/{id}', 'update');
    });

    Route::controller(ConstellationsController::class)->group(function () {
        Route::get('/constellations', 'constellationlist');
    });

    Route::controller(StartCampaignController::class)->group(function () {
        Route::post('/startcampaigns/{name}', 'store');
        Route::get('/startcampaigns/{link}', 'show');
        Route::get('/startcampaigns', 'index');
        Route::delete('/startcampaigns/{id}', 'destroy');
    });

    Route::controller(StartCampaignJoinController::class)->group(function () {
        Route::get('/startcampaignjoin', 'index');
        Route::get('/startcampaignjoinbyid/{campid}', 'indexByID');
    });

    Route::controller(StartCampaignSystemRecordsController::class)->group(function () {
        Route::get('/startcampaignsystemsrecords', 'index');
    });

    Route::controller(StartCampaignSystemController::class)->group(function () {
        Route::put('/startcampaignsystemupdate/{id}/{campid}', 'update');
        Route::put('/startcampaignsystemupdatetimer/{id}/{campid}', 'updatetimer');
        Route::delete('/startcampaignsystemremovechar/{id}/{char}/{campid}', 'removeChar');
    });


    Route::controller(RcFcUsersController::class)->group(function () {
        Route::put('/rcfcuseradd/{id}', 'addFCtoStation');
        Route::put('/rcfcuserremove/{id}', 'removeFCtoStation');
        Route::get('/rcfc', 'index');
        Route::put('/rcfcnew', 'newfc');
        Route::delete('/rcfcdelete/{id}', 'removeFC');
        Route::post('/rcfcadd/{id}', 'addFCadd');
    });

    Route::controller(RcReconUsersController::class)->group(function () {
        Route::put('/rcreconuseradd/{id}', 'addRecontoStation');
        Route::put('/rcreconuserremove/{id}', 'removeRecontoStation');
    });

    Route::controller(RcGsolUsersController::class)->group(function () {
        Route::put('/rcgsoluseradd/{id}', 'addGsoltoStation');
        Route::put('/rcgsoluserremove/{id}', 'removeGsoltoStation');
    });

    Route::controller(ChillStationController::class)->group(function () {
        Route::get('/chillsheet', 'index');
        Route::get('/chillregionlist', 'chillSheetListRegion');
        Route::get('/chillTypelist', 'chillSheetListType');
        Route::get('/chillStatuslist', 'chillSheetListStatus');
        Route::get('/chilltest', 'test');
        Route::put('finishrcstationchill/{id}', 'stationdone');
        Route::put('/chillupdatetimerinfo/{id}', 'chillEditUpdate');
        Route::delete('/chilldelete/{id}', 'destroy');
        Route::put('/chillupdatestationnotification/{id}', 'update');
    });

    Route::controller(WelpStationController::class)->group(function () {
        Route::get('/welpsheet', 'index');
        Route::get('/welpregionlist', 'welpSheetListRegion');
        Route::get('/welpTypelist', 'welpSheetListType');
        Route::get('/welpStatuslist', 'welpSheetListStatus');
        Route::get('/welptest', 'test');
        Route::put('finishrcstationwelp/{id}', 'stationdone');
        Route::put('/welpupdatetimerinfo/{id}', 'welpEditUpdate');
        Route::delete('/welpdelete/{id}', 'destroy');
        Route::put('/welpupdatestationnotification/{id}', 'update');
    });

    Route::controller(KeyTypeController::class)->group(function () {
        Route::get('/alluserskeys', 'getAllUsersKeys');
        Route::get('/keys', 'index');
        Route::put('/keysremove', 'removeKey');
        Route::put('/keynew', 'store');
        Route::delete('/keydelete/{id}', 'destroy');
    });

    Route::controller(FleetTypeController::class)->group(function () {
        Route::get('/allkeyfleets', 'getAllKeyFleets');
        Route::get('/fleets', 'index');
        Route::delete('/fleetdelete/{id}', 'destroy');
        Route::put('/fleetnew', 'store');
    });

    Route::controller(UserKeyJoinControllerController::class)->group(function () {
        Route::put('/keysadd', 'store');
    });

    Route::controller(KeyFleetJoinControllerController::class)->group(function () {
        Route::put('/fleetssadd', 'store');
        Route::put('/fleetsremove', 'removefleet');
    });


    Route::controller(CoordSheetController::class)->group(function () {
        Route::get('/coordRegionlist', 'coordSheetListRegion');
        Route::get('/coordItemlist', 'coordSheetListItem');
        Route::get('/coordStatuslist', 'coordSheetListStatus');
        Route::get('/coordsheet', 'index');
    });

    Route::controller(UserController::class)->group(function () {
        Route::post('/userupdate/{id}', 'updateMessage');
    });

    Route::controller(NewOperationsController::class)->group(function () {
        Route::get('/solooperationlist', 'index');
        Route::get('/operationinfo/{id}', 'getInfo');
        Route::post('/newoperation', 'makeNewOperation');
        Route::get('/operationlist', 'getCustomOperationList');
        Route::post('/editoperation', 'edit');
        Route::delete('/newoperation/{id}', 'destroy');
        Route::post('/sendadduseroverlay/{opID}/{type}', 'sendAddCharOverlay');
        Route::post('/setreadonly/{opID}', 'changeReadyOnly');
        Route::post('/newcampaignpriority/{id}', 'updatePriority');
        Route::get('/operationlistinfoop', 'operationlist');
        Route::post('/operation/toggleWindow/{toggle}/{opID}', 'toggleWindow');
    });

    Route::controller(NewCampaignsController::class)->group(function () {
        Route::put('/newcampaigns/{id}', 'update');
        Route::get('/newcampaignslist', 'campaignsList');
        Route::get('/newoperationlogs/{opID}', 'logs');
    });

    Route::controller(HotRegionController::class)->group(function () {
        Route::get('/getregionlists', 'index');
        Route::post('/hotregionedit/{id}', 'update');
        Route::post('/updatesetting', 'updateSetting');
        Route::get('/watchlist/getneededinfo', 'stationWatchListNeededInfo');
        Route::post('/watchlist/getneededinfo', 'stationWatchListRegionUpdate');
    });

    Route::controller(StationWatchListController::class)->group(function () {
        Route::get('/watchlist', 'index');
        Route::get('/watchlist/byuser', 'userWatchLists');
        Route::post('/watchlist', 'store');
        Route::put('/watchlist/{id}', 'update');
        Route::delete('/watchlist/{id}', 'destroy');
    });

    Route::controller(WebWayStartSystemsContorller::class)->group(function () {
        Route::get('/getwebwaystartsystems', 'getSystemList');
        Route::post('/updatewebwaystartsystems', 'update');
    });

    Route::controller(OperationUserController::class)->group(function () {
        Route::put('/newcampaignusersremove/{id}/{opID}/{userid}', 'updateremove');
        Route::put('/newcampaignusersadd/{id}/{opID}/{userid}', 'updateadd');
        Route::post('/newcampaignusers/{opID}/{userid}', 'store');
        Route::put('/newcampaignusers/{userid}/{opID}', 'edit');
        Route::delete('/newcampaignusers/{id}/{opID}/{userid}', 'destroy');
        Route::put('/onthewayreadytogo/{opID}/{opUserID}', 'updateOnTheWayReadyToGO');
    });

    Route::controller(NewSystemNodeController::class)->group(function () {
        Route::post('/addnode', 'store');
        Route::delete('/deletenode/{id}', 'destroy');
        Route::post('/addusertonode', 'addCharToNode');
        Route::post('/updatenodestats/{id}', 'updateStatus');
        Route::post('/addcharadmin', 'addUserToNodeAdmin');
    });

    Route::controller(NewUserNodeController::class)->group(function () {
        Route::put('/addprimetimer/{id}', 'addTimer');
        Route::put('/addtimertonode/{id}', 'addTimertoNode');
        Route::delete('/newdeleteextanode/{id}', 'destroy');
    });

    Route::controller(OperationInfoController::class)->group(function () {
        Route::post('/operationinfosheet', 'store');
        Route::delete('/operationinfosheet/{id}', 'destroy');
        Route::post('/operationinfostart/{id}', 'updateStartTime');
        Route::post('/operationinfosheet/{id}', 'editHackOperation');
        Route::get('/operationinfosheet', 'index');
        Route::post('/operationinfosystemnoteupdate/{id}', 'updateNote');
        Route::post('/operationinfosystemjamupdate/{id}', 'updateJam');
        Route::post('/operationinfosystemreconupdate/{id}', 'updateRecon');
        Route::delete('/operationinfosystemreconremove/{id}', 'deleteRecon');
    });

    Route::controller(OperationInfoSheetController::class)->group(function () {
        Route::get('/operationinfopage/{link}', 'index');
        Route::put('/operationinfopage/{id}', 'update');
        Route::put('/operationinfopagemessage/{id}', 'messageAdd');
        Route::delete('/operationinfopagemessage/{id}', 'messageDelete');
        Route::put('/operationinfopagemessage/{id}/notes', 'addReadBy');
    });

    Route::controller(OperationInfoUserController::class)->group(function () {
        Route::get('/operationinfousers', 'index');
    });

    Route::controller(OperationInfoMumbleController::class)->group(function () {
        Route::get('/operationinfomumble', 'index');
    });

    Route::controller(OperationInfoDoctrineController::class)->group(function () {
        Route::get('/operationinfodoctrines', 'index');
    });

    Route::controller(OperationInfoFleetController::class)->group(function () {
        Route::post('/operationinfofleet/{id}', 'store');
        Route::delete('/operationinfofleet/{id}', 'destroy');
        Route::put('/operationinfofleet/{id}', 'update');
        Route::put('/operationinfofleetname/{id}', 'name');
        Route::post('/operationinfofleetrecon/{id}', 'reconAdd');
        Route::post('/operationinfofleetreconremove/{id}', "reconRemove");
        Route::post('/operationdanklink', "dankLinkAdd");
        Route::delete('/operationdanklink/{id}', "dankLinkRemove");
        Route::put('/operationinfo/fleet/update/{id}', 'updateFleet');
    });

    Route::controller(OperationInfoReconController::class)->group(function () {
        Route::post('/operationinforecon', 'store');
        Route::post('/operationinforeconremove/{id}', 'removeFromOp');
        Route::get('/operationinforecon', 'index');
        Route::post('/operationinforecondead/{id}', 'updateDeadStatus');
        Route::post('/operationinforecononline/{id}', 'updateOnlineStatus');
    });

    Route::controller(OperationInfoFleetReconRoleController::class)->group(function () {
        Route::get('/operationinfofleetreconrole', 'index');
    });

    Route::controller(OperationInfoJammedStatusController::class)->group(function () {
        Route::get('/operationinfojammerstatus', 'index');
    });
});
