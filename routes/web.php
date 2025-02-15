<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CertificateStudentController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CertificateController;

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





// Route::resource('images', ImageController::class);
// Route::post('/images/upload', [ImageController::class, 'upload'])->name('images.upload');
// Route::post('/images/upload', [ImageController::class, 'upload'])->name('images.upload');
// Route::get('/images/list', [ImageController::class, 'indexJson']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('certificates', CertificateController::class);
    Route::put('/certificates/{certificate}/update-texts', [CertificateController::class, 'updateTexts']);

    // Certificate Students routes
    Route::get('/certificates/{certificate}/certificate-students', [CertificateStudentController::class, 'index']);
    Route::post('/certificates/{certificate}/certificate-students', [CertificateStudentController::class, 'store']);
    Route::put('/certificates/{certificate}/certificate-students/{student}', [CertificateStudentController::class, 'update']);
    Route::delete('/certificates/{certificate}/certificate-students/{student}', [CertificateStudentController::class, 'destroy']);

    // Image routes
    Route::post('/api/images', [ImageController::class, 'store']);
    Route::get('/images/{image}', [ImageController::class, 'show'])->name('images.show');
    Route::delete('/api/images/{image}', [ImageController::class, 'destroy']);
});



// Route::get('/test', function () {
//     return '{"file_path": "12354123"}';
// });
