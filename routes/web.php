<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Monitoring\MonitoringHostsController;
use App\Http\Controllers\Monitoring\MonitoringBoxsController;
use App\Http\Controllers\Monitoring\MonitoringServicesController;
use App\Http\Controllers\Monitoring\MonitoringEquipementsController;
use App\Http\Controllers\Monitoring\MonitoringProblemsController;

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

Route::get('/',[MonitoringHostsController::class,'show']);

Route::get('/monitoring/hosts',[MonitoringHostsController::class,'show']);

Route::get('/monitoring/services',[MonitoringServicesController::class,'show']);

Route::get('/monitoring/boxs',[MonitoringBoxsController::class,'show']);

Route::get('/monitoring/equipements',[MonitoringEquipementsController::class,'show']);

Route::get('/monitoring/problems',[MonitoringProblemsController::class,'show']);

