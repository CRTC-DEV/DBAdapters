<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArrivalMovementViewApi;
use App\Http\Controllers\API\DepartureMovementViewApi;
use App\Http\Controllers\API\DeploymentController;
use App\Http\Controllers\API\VeribagsApi;
use App\Http\Controllers\API\TagRecheckApi;
use App\Http\Controllers\API\AirlinesApi;
use App\Http\Controllers\API\AircraftsApi;
use App\Http\Controllers\API\AircraftTypesApi;
use App\Http\Controllers\API\AirportsApi;
use App\Http\Controllers\API\RouteApi;
use App\Http\Controllers\API\GateApi;
use App\Http\Controllers\API\StandApi;
use App\Http\Controllers\API\ChuteApi;
use App\Http\Controllers\API\CarouselApi;

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

Route::post('/webhook/deploy', DeploymentController::class);

//FlightMovement API
Route::get('/arrivalmovement&selecteddate={date}', [ArrivalMovementViewApi::class, 'getArrivalMovement'])->name('api.arrivalmovement.get');
Route::get('/departuremovement&selecteddate={date}', [DepartureMovementViewApi::class, 'getDepartureMovement'])->name('api.departuremovement.get');

//FlightMovement API for Web
Route::get('/arrivalmovement&bydate={date}', [ArrivalMovementViewApi::class, 'getArrivalWeb'])->name('api.arrivalweb.get');
Route::get('/departuremovement&bydate={date}', [DepartureMovementViewApi::class, 'getDepartureWeb'])->name('api.departureweb.get');

//Filter FlightMovement By Checkin
Route::get('/departuremovementbycheckin&selecteddate={date}&checkin={counter}', [DepartureMovementViewApi::class, 'getDepartureMovementByCheckin'])->name('api.departuremovementbycheckin.get');

//Filter FlightMovement By CheckinTime
Route::get('/departuremovementbycheckintime&selecteddate={date}&checkin={counter}', [DepartureMovementViewApi::class, 'getDepartureMovementByCheckinTime'])->name('api.departuremovementbycheckintime.get');

//API Led
Route::get('/airlinenameandlogo={date}&checkin={counter}', [DepartureMovementViewApi::class, 'getAirlineNameAndLogo'])->name('api.airlinenameandlogo.get');

// Veribags API - Barcode Processing
Route::get('/process-barcode', [VeribagsApi::class, 'processBarcodeData'])->name('api.process-barcode.get');
Route::get('/recheck-statistics/{date}', [VeribagsApi::class, 'getRecheckStatistics'])->name('api.recheck-statistics.get');
Route::get('/recheck-by-counter/{date}/{counter}', [VeribagsApi::class, 'getRecheckByCounter'])->name('api.recheck-by-counter.get');

// TagRecheck API
Route::get('/tag-recheck/{date}', [TagRecheckApi::class, 'getTagRecheckByDate'])->name('api.tag-recheck.get');
Route::get('/tag-recheck/{flightId}/{date}', [TagRecheckApi::class, 'getTagRecheckByFlight'])->name('api.tag-recheck-flight.get');
Route::post('/tag-recheck', [TagRecheckApi::class, 'createTagRecheck'])->name('api.tag-recheck.post');
Route::put('/tag-recheck/{id}', [TagRecheckApi::class, 'updateTagRecheck'])->name('api.tag-recheck.put');
Route::delete('/tag-recheck/{id}', [TagRecheckApi::class, 'deleteTagRecheck'])->name('api.tag-recheck.delete');
Route::patch('/tag-recheck/{id}/finish', [TagRecheckApi::class, 'finishTagRecheck'])->name('api.tag-recheck.finish');

// Airlines API
Route::get('/airlines', [AirlinesApi::class, 'getActiveAirlines'])->name('api.airlines.get');
Route::get('/airlines/iata/{iataCode}', [AirlinesApi::class, 'getAirlineByIataCode'])->name('api.airlines.iata.get');
Route::post('/airlines', [AirlinesApi::class, 'createAirline'])->name('api.airlines.post');
Route::put('/airlines/{airlineId}', [AirlinesApi::class, 'updateAirline'])->name('api.airlines.put');

// Aircrafts API
Route::get('/aircrafts', [AircraftsApi::class, 'getActiveAircrafts'])->name('api.aircrafts.get');
Route::get('/aircrafts/registration/{registration}', [AircraftsApi::class, 'getAircraftByRegistration'])->name('api.aircrafts.registration.get');
Route::post('/aircrafts', [AircraftsApi::class, 'createAircraft'])->name('api.aircrafts.post');
Route::put('/aircrafts/{aircraftId}', [AircraftsApi::class, 'updateAircraft'])->name('api.aircrafts.put');

// Aircraft Types API
Route::get('/aircraft-types', [AircraftTypesApi::class, 'getActiveAircraftTypes'])->name('api.aircraft-types.get');
Route::get('/aircraft-types/icao/{icaoCode}', [AircraftTypesApi::class, 'getAircraftTypeByIcaoCode'])->name('api.aircraft-types.icao.get');
Route::post('/aircraft-types', [AircraftTypesApi::class, 'createAircraftType'])->name('api.aircraft-types.post');
Route::put('/aircraft-types/{aircraftTypeId}', [AircraftTypesApi::class, 'updateAircraftType'])->name('api.aircraft-types.put');

// Airports API
Route::get('/airports', [AirportsApi::class, 'getActiveAirports'])->name('api.airports.get');
Route::get('/airports/iata/{iataCode}', [AirportsApi::class, 'getAirportByIataCode'])->name('api.airports.iata.get');
Route::get('/airports/city/{city}', [AirportsApi::class, 'getAirportsByCity'])->name('api.airports.city.get');
Route::post('/airports', [AirportsApi::class, 'createAirport'])->name('api.airports.post');
Route::put('/airports/{airportId}', [AirportsApi::class, 'updateAirport'])->name('api.airports.put');

// Routes API
Route::get('/routes', [RouteApi::class, 'getActiveRoutes'])->name('api.routes.get');
Route::get('/routes/{id}', [RouteApi::class, 'getRouteById'])->name('api.routes.id.get');
Route::post('/routes', [RouteApi::class, 'createRoute'])->name('api.routes.post');
Route::put('/routes/{id}', [RouteApi::class, 'updateRoute'])->name('api.routes.put');
Route::delete('/routes/{id}', [RouteApi::class, 'deleteRoute'])->name('api.routes.delete');

// Gates API
Route::get('/gates', [GateApi::class, 'getActiveGates'])->name('api.gates.get');
Route::post('/gates', [GateApi::class, 'createGate'])->name('api.gates.post');
Route::put('/gates/{id}', [GateApi::class, 'updateGate'])->name('api.gates.put');

// Stands API
Route::get('/stands', [StandApi::class, 'getActiveStands'])->name('api.stands.get');
Route::post('/stands', [StandApi::class, 'createStand'])->name('api.stands.post');
Route::put('/stands/{id}', [StandApi::class, 'updateStand'])->name('api.stands.put');

// Chutes API
Route::get('/chutes', [ChuteApi::class, 'getActiveChutes'])->name('api.chutes.get');
Route::post('/chutes', [ChuteApi::class, 'createChute'])->name('api.chutes.post');
Route::put('/chutes/{id}', [ChuteApi::class, 'updateChute'])->name('api.chutes.put');

// Carousels API
Route::get('/carousels', [CarouselApi::class, 'getActiveCarousels'])->name('api.carousels.get');
Route::post('/carousels', [CarouselApi::class, 'createCarousel'])->name('api.carousels.post');
Route::put('/carousels/{id}', [CarouselApi::class, 'updateCarousel'])->name('api.carousels.put');

