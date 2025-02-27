<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CertificateStudentController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\PublicCertificateController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;

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

// Rota pública para verificação de certificados
Route::get('/verificar-certificado', [PublicCertificateController::class, 'show'])->name('certificate_students.search');





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
    Route::put('/certificates/{certificate}/certificate-students/{certificateStudent}', [CertificateStudentController::class, 'update']);
    Route::delete('/certificates/{certificate}/certificate-students/{certificateStudent}', [CertificateStudentController::class, 'destroy']);
    Route::get('/certificates/{certificate}/generate-code', [CertificateStudentController::class, 'generateUniqueCode']);

    // Image routes
    Route::post('/api/images', [ImageController::class, 'store']);
    Route::get('/images/{image}', [ImageController::class, 'show'])->name('images.show');
    Route::delete('/api/images/{image}', [ImageController::class, 'destroy']);

    // API routes for categories and teachers
    Route::get('/api/categories', [CategoryController::class, 'index']);
    Route::post('/api/categories', [CategoryController::class, 'store']);
    Route::get('/api/teachers', [TeacherController::class, 'index']);
    Route::post('/api/teachers', [TeacherController::class, 'store']);

    // Update certificate relationships
    Route::post('/certificates/{certificate}/categories', [CertificateController::class, 'updateCategories']);
    Route::post('/certificates/{certificate}/teachers', [CertificateController::class, 'updateTeachers']);

    // Units
    Route::get('/api/units', [UnitController::class, 'index']);
    Route::post('/api/units', [UnitController::class, 'store']);
    Route::delete('/api/units/{unit}', [UnitController::class, 'destroy']);
});



// Route::get('/test', function () {
//     return '{"file_path": "12354123"}';
// });
