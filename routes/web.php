<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OverviewController;

use App\Http\Controllers\Monitoring\HostsController;
use App\Http\Controllers\Monitoring\BoxsController;
use App\Http\Controllers\Monitoring\ServicesController;
use App\Http\Controllers\Monitoring\EquipementsController;

use App\Http\Controllers\MapController;



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
Route::get('/',[OverviewController::class,'overview']);

Route::get('/overview',[OverviewController::class,'overview']);

// Monitoring Section : 

Route::get('/monitoring/hosts',[HostsController::class,'show'])->name('monitoring.hosts');

Route::get('/monitoring/services',[ServicesController::class,'show'])->name('monitoring.services');

Route::get('/monitoring/boxs',[BoxsController::class,'show'])->name('monitoring.boxs');

Route::get('/monitoring/equipements',[EquipementsController::class,'show'])->name('monitoring.equipements');


// Problems Section : 

Route::get('/problems/hosts',[HostsController::class,'problems'])->name('problems.hosts');

Route::get('/problems/services',[ServicesController::class,'problems'])->name('problems.services');

Route::get('/problems/boxs',[BoxsController::class,'problems'])->name('problems.boxs');

Route::get('/problems/equipements',[EquipementsController::class,'problems'])->name('problems.equipements');

// Statistique Section:

Route::get('/statistiques/hosts',[HostsController::class,'statistic']);

Route::get('/statistiques/services',[ServicesController::class,'statistic']);

Route::get('/statistiques/equipements',[EquipementsController::class,'statistic']);

Route::get('/statistiques/boxs',[BoxsController::class,'statistic']);

// Historique Section:

Route::get('/historiques/hosts',[HostsController::class,'historic'])->name('historic.hosts');

Route::get('/historiques/services',[ServicesController::class,'historic'])->name('historic.services');

Route::get('/historiques/boxs',[BoxsController::class,'historic'])->name('historic.boxs');

Route::get('/historiques/equipements',[EquipementsController::class,'historic'])->name('historic.equipements');

// Cartes Section : 

Route::get('/cartes/automap',[MapController::class,'automap']);

Route::get('/cartes/carte',[MapController::class,'carte']);


// Download PDF / CVS :

Route::get('/download',[EquipementsController::class,'download']);

