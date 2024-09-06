<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ImageController;

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



Route::resource('certificates', CertificateController::class);

Route::resource('images', ImageController::class);
Route::post('/images/upload', [ImageController::class, 'upload'])->name('images.upload');

