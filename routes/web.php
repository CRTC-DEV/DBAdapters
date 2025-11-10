<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Deploy\DeploymentController;
use App\Http\Controllers\SwaggerController;

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
