<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ESITokensController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobTestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\testController;
use App\Http\Controllers\TowerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::get('/jobtest', 'JobTestController@standingJob');

Route::controller(JobTestController::class)->group(function () {
    Route::get('/jobtest', 'standingJob');

    Route::get('/testGetAliianceJob/{id}', 'jobAllianceTest');
    Route::get('/testGetCorpJob/{id}', 'jobCorpTest');
});

Route::controller(TowerController::class)->group(function () {
    Route::get('/test/tower', 'towerFilters');
});

Route::controller(testController::class)->group(function () {
    Route::get('/testpusher', 'testPusher');
    Route::get('/testRunScore', 'testRunScore');
    Route::get('/teststatus', 'testEveStatus');
    Route::get('/stationrecordtest/{id}', 'testStationRecords');
    Route::get('/c1c192de123140555a8d4a31ce48b82a163', 'testAthing');
    Route::get('/testGetAliiance/{id}', 'testGetAlliance');
    Route::get('/testCorpWithNoAlliance', 'getCorpWithNoAlliance');
    Route::get('/testCampagin', 'testPull');
    Route::get('/populatenewcampaignsystem', 'popualteCampaignSystemTable');
    Route::get('/removefc', 'removeFC');
    Route::get('/hitherealso', 'horizon');
    Route::get('/hithere', 'prequal');
    Route::get('/hithereagain', 'logreader');
    Route::get('/testsolooperstions', 'getSoloOperations');
    Route::get('/campaignlisttest', 'campaginListTest');
    Route::get('/campaigntest', 'campaginTest');
    Route::get('/testUsers', 'key');
    Route::get('/testpull', 'testUpdateScore');
    Route::get('/nametoid/{name}', 'nameToID');
    Route::get('/adashd', 'adashDScan');
    Route::get('/danktest', 'dankDoc');
    Route::get('/testlog', 'testLogs');
    Route::get('testNotes', 'testNotes');
    Route::get('/testDankFleet', 'testDankFleet');
    Route::get('/test/testremove', 'removeOps');
    Route::get('/test/teststation', 'testStation');
    Route::get('/test/testwatch', 'testWatchListPull');
    Route::get('/test/reconpull/{id}', 'testStationPull');
    Route::get('/test/fitpull/{id}', 'testStationItemPull');
    Route::get('/test/pullitem', 'testItemPull');
    Route::get('/test/dscan/{id}', 'testDscanPull');
    Route::get('/test/descanlocal', 'testDscanLocal');
    Route::get('/test/testdscanhistory/{link}', 'testDscanHistory');
    Route::get('/test/towerfit', 'testTowerItems');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/73cbd63ecd4d2d9267ae4ad7bf25c704/5a1f48be9e4df773064f33590be892ff', 'admin');
    Route::get('/73cbd63ecd4d704/5a1f48be9e4df773064f33590be892ff', 'borisToken');
    Route::get('/73cbd63ecd4d2f33590be892ff', 'webwayUser');
    Route::get('/7fegrghrthtrhtr2d9267ae4ad7bf25c704/5a1f48be9e4df773064f33590be892ff', 'martyn');
    Route::get('/scopehIhaveNoIdeaWhatIamDoing', 'testPusher');
    Route::get('/login', 'login')->name('login');
    Route::get('/oauth/login', 'redirectToProvider');
    Route::get('/oauth/callback', 'handleProviderCallback');
    Route::get('/logout', 'logout');
    Route::get('/monty', 'monty');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index');
});

Route::controller(NotificationController::class)->group(function () {
    Route::get('/updateNotifications', 'getNotifications');
    Route::get('/blablabla/{id}', 'test');
});

Route::controller(ESITokensController::class)->group(function () {
    Route::get('esi/callback', 'handleProviderCallback');
});

Route::get('esi/add', [
    'as' => 'esi.add',
    'uses' => 'ESITokensController@redirectToProvider',
]);

//  NOTHING BELOW THIS LINEfffff
//'ESITokensController@redirectToProvider',
// Route::get('/{any}', 'AppController@index')->where('any', '.*');
Route::get('/{any}', [AppController::class, 'index'])->where('any', '.*');
