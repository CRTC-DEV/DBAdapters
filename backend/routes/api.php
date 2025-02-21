<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArrivalMovementViewApi;
use App\Http\Controllers\API\DepartureMovementViewApi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//FlightMovement API
Route::get('/arrivalmovement&selecteddate={date}', [ArrivalMovementViewApi::class, 'getArrivalMovement'])->name('api.arrivalmovement.get');
Route::get('/departuremovement&selecteddate={date}', [DepartureMovementViewApi::class, 'getDepartureMovement'])->name('api.departuremovement.get');

//FlightMovement API for Web
Route::get('/arrivalmovement&bydate={date}', [ArrivalMovementViewApi::class, 'getArrivalWeb'])->name('api.arrivalweb.get');
Route::get('/departuremovement&bydate={date}', [DepartureMovementViewApi::class, 'getDepartureWeb'])->name('api.departureweb.get');

//Filter FlightMovement By Checkin
Route::get('/departuremovementbycheckin&selecteddate={date}&checkin={counter}', [DepartureMovementViewApi::class, 'getDepartureMovementByCheckin'])->name('api.departuremovementbycheckin.get');

//API Led
Route::get('/airlinenameandlogo={date}&checkin={counter}', [DepartureMovementViewApi::class, 'getAirlineNameAndLogo'])->name('api.airlinenameandlogo.get');
//Route::post('/mapitem', [DepartureMovementViewApi::class, 'postMapItem'])->name('api.map-item.post');

