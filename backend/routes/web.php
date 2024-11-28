<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Livewire\FlightScheduleLive;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/flight-schedule', FlightScheduleLive::class)->name('flight-schedule');






//For auto read xml files
Route::get('/run-process-xml', function () {
    // Chạy lệnh Artisan xml:process
    Artisan::call('xml:process-directory');

    // Lấy kết quả trả về từ Artisan Command
    $output = Artisan::output();

    // Trả về kết quả cho người dùng
    return nl2br($output);
});

Route::get('/run-clear-all', function () {
    // Chạy lệnh Artisan xml:process
    Artisan::call('xml:cleanup-all');

    // Lấy kết quả trả về từ Artisan Command
    $output = Artisan::output();

    // Trả về kết quả cho người dùng
    return nl2br($output);
});
