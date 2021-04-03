<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OverviewController;

use App\Http\Controllers\Monitoring\HostsController;
use App\Http\Controllers\Monitoring\BoxsController;
use App\Http\Controllers\Monitoring\ServicesController;
use App\Http\Controllers\Monitoring\EquipementsController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

use App\Http\Controllers\MapController;
use App\Http\Controllers\UsersConfigController;
use App\Http\Controllers\UserProfileController;



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

// Overview : 

Route::get('/overview',[OverviewController::class,'overview'])->name('overview');

// Monitoring Section :

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




// Problems Section : 

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

// Configuration Section: 

Route::prefix('configuration')->group(function () {

    // Users :
    Route::prefix('/users')->group(function () {

        Route::get('/',[UsersConfigController::class,'users'])->name('config.users');
        Route::delete('/{user}',[UsersConfigController::class,'delete'])->name('user.delete');
        Route::post('/upgrade',[UsersConfigController::class,'upgrade'])->name('user.upgrade');

    });

    // Hosts : 
    Route::get('/hosts', [HostsController::class,'index'])->name('configHosts');
    Route::get('/hosts/{host_id}', [HostsController::class,'delete'])->name('deleteHost');
    Route::get('/hosts/edit/{host_object_id}', [HostsController::class,'edit'])->name('editHost');
    Route::get('/hosts/add', [HostsController::class,'add'])->name('addHost');
    
    // Boxs : 
    Route::get('/boxs', [BoxsController::class,'index'])->name('configBoxs');
    Route::get('/boxs/{box_id}', [BoxsController::class,'delete'])->name('deleteBox');
    Route::get('/boxs/{box_id}/edit/', [BoxsController::class,'edit'])->name('editBox');
    Route::get('/boxs/add', [BoxsController::class,'add'])->name('addBox');
    
    // Servcies : 
    Route::get('/services', [ServicesController::class,'index'])->name('configServices');
    Route::get('/services/{service_id}', [ServicesController::class,'delete'])->name('deleteService');
    Route::get('/services/{servcie_id}/edit/', [ServicesController::class,'edit'])->name('editService');
    Route::get('/services/add', [ServicesController::class,'add'])->name('addService');
    
    // Equipements : 
    Route::get('/equipements', [EquipementsController::class,'index'])->name('configEquips');
    Route::get('/equipements/{equip_id}', [EquipementsController::class,'delete'])->name('deleteEquip');
    Route::get('/equipements/{equip_id}/edit/', [EquipementsController::class,'edit'])->name('editEquip');
    Route::get('/equipements/add', [EquipementsController::class,'add'])->name('addEquip');


});



// Statistique Section:
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

// Historique Section:
Route::prefix('historiques')->group(function () {

    /** Hosts */
    Route::get('/hosts',[HostsController::class,'historic'])->name('historic.hosts');
    /** Servcies */
    Route::get('/services',[ServicesController::class,'historic'])->name('historic.services');
    /** Boxs */
    Route::get('/boxs',[BoxsController::class,'historic'])->name('historic.boxs');
    /** Equipements */
    Route::get('/equipements',[EquipementsController::class,'historic'])->name('historic.equipements');

});

// Cartes Section : 
/** Automap */
Route::get('/cartes/automap',[MapController::class,'automap']);
/** Carte */
Route::get('/cartes/carte',[MapController::class,'carte']);


// Download PDF / CVS :

Route::get('/historiques/hosts/download-PDF',[HostsController::class,'download'])->name('hosts.pdf');
Route::get('/historiques/services/download-PDF',[ServicesController::class,'download'])->name('services.pdf');
Route::get('/historiques/equipements/download-PDF',[EquipementsController::class,'download'])->name('equips.pdf');


// Login & Registration :

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