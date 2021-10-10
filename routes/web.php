<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OverviewController;

use App\Http\Controllers\Monitoring\HostsController;
use App\Http\Controllers\Monitoring\BoxsController;
use App\Http\Controllers\Monitoring\ServicesController;
use App\Http\Controllers\Monitoring\EquipementsController;

use App\Http\Controllers\Config\Hosts;
use App\Http\Controllers\Config\Boxs;

use App\Http\Controllers\Config\Groups\HostGroups;
use App\Http\Controllers\Config\Groups\ServiceGroups;
use App\Http\Controllers\Config\Groups\EquipGroups;

use App\Http\Controllers\Config\Edit\EditHost;
use App\Http\Controllers\Config\Edit\EditBox;
use App\Http\Controllers\Config\Edit\EditService;
use App\Http\Controllers\Config\Edit\EditEquip;

use App\Http\Controllers\Config\Add\AddEquip;
use App\Http\Controllers\Config\Add\AddService;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

use App\Http\Controllers\AutoMap\MapController;

use App\Http\Controllers\UsersConfigController;
use App\Http\Controllers\UserProfileController;

use App\Http\Controllers\Config\Notifications\Notifications;

use App\Mail\Notif;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\SMScontroller;
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
Route::view('/test','test');



/************************************************************ START Overview : ****************************************************************/
Route::get('/overview',[OverviewController::class,'overview'])->name('overview');
/************************************************************ END Overview : ******************************************************************/


/********************************************************* START Monitoring Section : **********************************************************/
Route::prefix('monitoring')->group(function () {

    /** Hosts */
    Route::get('/hosts',[HostsController::class,'show'])->name('monitoring.hosts');
    /** Services */
    Route::get('/services',[ServicesController::class,'show'])->name('monitoring.services');
    /** Boxs */
    Route::get('/boxs',[BoxsController::class,'show'])->name('monitoring.boxs');
    /** Equpements */
    Route::get('/equipements',[EquipementsController::class,'show'])->name('monitoring.equipements');

    // Monitoring Hosts/Boxs/Services/Equip. details:
    /** hosts */
    Route::get('/hosts/{id}',[HostsController::class,'details']);
    /** Services */
    Route::get('/services/{id}',[ServicesController::class,'details']);
    /** Boxs */
    Route::get('/boxs/{id}',[BoxsController::class,'details']);
    /** Equipemnets */
    Route::get('/equipements/{id}',[EquipementsController::class,'details']);
    
});
/********************************************************* END Monitoring Section : ***********************************************************/



/****************************************************** START Problems Section : **************************************************************/
Route::prefix('problems')->group(function () {
    
    /** Hosts */
    Route::get('/hosts',[HostsController::class,'problems'])->name('problems.hosts');
    /** Services */
    Route::get('/services',[ServicesController::class,'problems'])->name('problems.services');
    /** Boxs */
    Route::get('/boxs',[BoxsController::class,'problems'])->name('problems.boxs');
    /** Equipements */
    Route::get('/equipements',[EquipementsController::class,'problems'])->name('problems.equipements');

    // Problems Hosts/Boxs/Services/Equip. details:
    /** Hosts */
    Route::get('/hosts/{id}',[HostsController::class,'details']);
    /** Services */
    Route::get('/services/{id}',[ServicesController::class,'details']);
    /** Boxs */
    Route::get('/boxs/{id}',[BoxsController::class,'details']);
    /** Equipements */
    Route::get('/equipements/{id}',[EquipementsController::class,'details']);
    
});
/****************************************************** END Problems Section : ****************************************************************/



/*********************************************************** START Configuration Section : ****************************************************/ 
Route::prefix('configuration')->group(function () {

    // Users :
    Route::prefix('/users')->group(function () {

        Route::get('/',[UsersConfigController::class,'users'])->name('config.users');
        Route::delete('/{user}',[UsersConfigController::class,'delete'])->name('user.delete');
        Route::post('/upgrade',[UsersConfigController::class,'upgrade'])->name('user.upgrade');

    });

    // Hosts : 
    Route::get('/hosts', [Hosts::class,'index'])->name('configHosts');
    Route::get('/hosts/types', [Hosts::class,'types'])->name('hostType');
    Route::get('/hosts/add/manage/{type}',[Hosts::class,'manage'])->name('manageHost');
    Route::get('/hosts/add/{type}', [Hosts::class,'add'])->name('addHost');
    
    // Boxs : 
    Route::get('/boxs', [Boxs::class,'index'])->name('configBoxs');
    Route::get('/boxs/add/manage/new', [Boxs::class,'add'])->name('addBox');
    Route::get('/boxs/add/manage',[Boxs::class,'manage'])->name('manageBox');
    
    
    // Services : 
    Route::get('/services', [ServicesController::class,'index'])->name('configServices');
    Route::get('/services/select-host', [AddService::class,'manage'])->name('selectHost');
    Route::get('/services/add', [AddService::class,'add'])->name('addService');
    
    // Equipements : 
    Route::get('/equipements', [EquipementsController::class,'index'])->name('configEquips');
    Route::get('/equipements/select-box', [AddEquip::class,'index']);
    Route::get('/equipements/select-box/{id}', [AddEquip::class,'manage'])->name('selectBox');
    Route::get('/equipements/add/{id}', [AddEquip::class,'addEquip'])->name('addEquip');


    // Edit Host/Box/Service/Equip
    /** Edit Host */
    Route::prefix('hosts')->group(function () {
        
        Route::get('/edit/{id}', [EditHost::class,'index'])->name('hostDetails');
        Route::get('/edit/{id}/modify', [EditHost::class,'editHost'])->name('editHost');
        Route::get('/delete/{id}', [EditHost::class,'deleteHost'])->name('deleteHost');

    });
    
    /** Edit Host */
    Route::prefix('boxs')->group(function () {

        Route::get('/edit/{id}', [EditBox::class,'index'])->name('boxDetails');
        Route::get('/edit/{id}/modify', [EditBox::class,'editBox'])->name('editBox');
        Route::get('/delete/{id}', [EditBox::class,'deleteBox'])->name('deleteBox');

    });
    
    /** Edit Service */
    Route::prefix('services')->group(function () {

        Route::get('/edit/{id}', [EditService::class,'index'])->name('serviceDetails');
        Route::get('/edit/{id}/modify', [EditService::class,'editService'])->name('editService');
        Route::get('/delete/{id}', [EditService::class,'deleteService'])->name('deleteService');

    });

    
    /** Edit Equipement */
    Route::prefix('equipements')->group(function () {

        Route::get('/edit/{id}', [EditEquip::class,'index'])->name('equipDetails');
        Route::get('/edit/{id}/modify', [EditEquip::class,'editEquip'])->name('editEquip');
        Route::get('/delete/{id}', [EditEquip::class,'deleteEquip'])->name('deleteEquip');

    });

    // Notifications :
    Route::get('/notifications', [Notifications::class,'index']);

    // HostGroups
    Route::prefix('/hostgroups')->group(function () {

        Route::get('/',[HostGroups::class,'hostgroups']);
        Route::get('/add-new', [HostGroups::class,'addHG'])->name('addHG');
        Route::get('/add-new/create', [HostGroups::class,'createHG'])->name('createHG');
        Route::get('/{id}',[HostGroups::class,'HGdetails'])->name('HGdetails');
        Route::get('/delete/{id}',[HostGroups::class,'deleteHG'])->name('deleteHG');
        Route::get('/manage/{id}',[HostGroups::class,'manageHG'])->name('manageHG');
        Route::get('/edit/{id}',[HostGroups::class,'editHG'])->name('editHG');

    });
    
    
    // ServiceGroups
    Route::prefix('/servicegroups')->group(function () {

        Route::get('/',[ServiceGroups::class,'servicegroups']);
        Route::get('/add-new', [ServiceGroups::class,'addSG'])->name('addSG');
        Route::get('/{id}',[ServiceGroups::class,'SGdetails'])->name('SGdetails');
        Route::get('/add-new/create', [ServiceGroups::class,'createSG'])->name('createSG');
        Route::get('/delete/{id}',[ServiceGroups::class,'deleteSG'])->name('deleteSG');
        Route::get('/manage/{id}',[ServiceGroups::class,'manageSG'])->name('manageSG');
        Route::get('/edit/{id}',[ServiceGroups::class,'editSG'])->name('editSG');

    });
    
    // EquipGroups
    Route::prefix('/equipgroups')->group(function () {

        Route::get('/',[EquipGroups::class,'equipgroups']);
        Route::get('/add-new', [EquipGroups::class,'addEG'])->name('addEG');
        Route::get('/{id}',[EquipGroups::class,'EGdetails'])->name('EGdetails');
        Route::get('/add-new/create', [EquipGroups::class,'createEG'])->name('createEG');
        Route::get('/delete/{id}',[EquipGroups::class,'deleteEG'])->name('deleteEG');
        Route::get('/manage/{id}',[EquipGroups::class,'manageEG'])->name('manageEG');
        Route::get('/edit/{id}',[EquipGroups::class,'editEG'])->name('editEG');

    });

    // Sites
    Route::view('/sites','config.sites');

    Route::get('/sites/{site}', function($site){

        return view('config.site', compact('site'));

    })->name('site');

    Route::view('/sites/{site}/hosts','config.test.hosts');
    Route::view('/sites/{site}/boxs','config.test.boxs');
    Route::view('/sites/{site}/services','config.test.services');
    Route::view('/sites/{site}/equip','config.test.equip');

});
/*********************************************************** END Configuration Section : ******************************************************/ 


/******************************************************* START Statistique Section : **********************************************************/ 
Route::prefix('statistiques')->group(function () {

    /** Hosts */
    Route::get('/hosts',[HostsController::class,'statistic'])->name('statistic.hosts');
    /** Services */
    Route::get('/services',[ServicesController::class,'statistic'])->name('statistic.services');
    /** Equipements */
    Route::get('/equipements',[EquipementsController::class,'statistic'])->name('statistic.equips');
    /** Boxs */
    // Route::get('/boxs',[BoxsController::class,'statistic']);

});
/******************************************************* END Statistique Section : ************************************************************/ 



/******************************************************* START Historique Section : ***********************************************************/ 
Route::prefix('historiques')->group(function () {

    /** Hosts */
    Route::get('/hosts',[HostsController::class,'historic'])->name('historic.hosts');
    /** Servcies */
    Route::get('/services',[ServicesController::class,'historic'])->name('historic.services');
    /** Boxs */
    Route::get('/boxs',[BoxsController::class,'historic'])->name('historic.boxs');
    /** Equipements */
    Route::get('/equipements',[EquipementsController::class,'historic'])->name('historic.equipements');


    // Download PDF / CVS :
    /** PDF */
    Route::get('/hosts/download-PDF/{name}/{status}/{from}/{to}',[HostsController::class,'download'])->name('hosts.pdf');
    Route::get('/services/download-PDF/{name}/{status}/{from}/{to}',[ServicesController::class,'download'])->name('services.pdf');
    Route::get('/equipements/download-PDF/{name}/{status}/{from}/{to}',[EquipementsController::class,'download'])->name('equips.pdf');
    /** CSV */
    Route::get('/hosts/CSV',[HostsController::class,'csv'])->name('hosts.csv');
    Route::get('/services/CSV',[ServicesController::class,'csv'])->name('services.csv');
    Route::get('/equipements/CSV',[EquipementsController::class,'csv'])->name('equips.csv');

});
/******************************************************* END Historique Section : *************************************************************/ 


/******************************************************* START Cartes Section : ***************************************************************/ 
/** Automap */
Route::get('/cartes/automap',[MapController::class,'automap']);
/** Carte */
Route::get('/cartes/carte',[MapController::class,'carte']);
/******************************************************* END Cartes Section : *****************************************************************/ 


/****************************************************** START Login & Registration : **********************************************************/ 
/** Login : **/
Route::get('/login',[LoginController::class,'index'])->name('login');
Route::get('/',[LoginController::class,'index']);
Route::post('/login',[LoginController::class,'store']);
/** Register : **/
Route::get('/register',[RegisterController::class,'index'])->name('register');
Route::post('/register',[RegisterController::class,'store']);

/** Logout : **/
Route::post('/logout',[LogoutController::class,'logout'])->name('logout');

// User Properties: 

Route::prefix('user')->group(function () {

/** User Profile : **/
Route::get('/profile', [UserProfileController::class,'userProfile'])->name('userProfile');
/** Delete his Account : **/
Route::delete('/{user}',[UserProfileController::class,'deleteMyAccount'])->name('deleteMyAccount');


/** Edit his Info : **/
// Change Password
Route::get('/edit-password', [UserProfileController::class,'indexPass'])->name('edit-password');
Route::put('/change-password',[UserProfileController::class,'changePassword'])->name('changePassword');

// Change Username/Email 
Route::get('/edit-info', [UserProfileController::class,'indexInfo'])->name('edit-info');
Route::put('/change-info',[UserProfileController::class,'changeNameEmail'])->name('changeInfo');

});
/****************************************************** END Login & Registration : **********************************************************/ 


Route::get('/email',[TestController::class,'index']);

Route::get('/sms', [SMScontroller::class,'index']);