<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CertificateApiController;
use App\Http\Controllers\Api\CertificateStudentApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/certificates', [CertificateApiController::class, 'index']);

// Rotas para CertificateStudent
Route::prefix('certificates/{certificate}')->group(function () {
    // Criar estudante
    Route::post('students', [CertificateStudentApiController::class, 'store']);
    
    // Listar estudantes
    Route::get('students', [CertificateStudentApiController::class, 'index']);
    
    // Gerar código único
    Route::get('generate-code', [CertificateStudentApiController::class, 'generateCode']);
    
    // Criar múltiplos estudantes (para CSV/lote)
    Route::post('students/batch', [CertificateStudentApiController::class, 'storeBatch']);
});