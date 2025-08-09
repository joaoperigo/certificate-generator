<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateStudent;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class CertificateStudentApiController extends Controller
{
    /**
     * Listar estudantes de um certificado
     */
    public function index(Certificate $certificate)
    {
        try {
            $students = $certificate->certificateStudents()
                ->with('unit')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Students retrieved successfully',
                'certificate_id' => $certificate->id,
                'certificate_title' => $certificate->title,
                'total' => $students->count(),
                'data' => $students
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving students',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Criar novo estudante
     */
    public function store(Request $request, Certificate $certificate)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'cpf' => 'nullable|string|max:20',
                'document' => 'nullable|string|max:50',
                'code' => 'required|string|unique:certificate_students,code',
                'unit' => 'nullable|string|max:255',
                'unit_id' => 'nullable|exists:units,id',
                'course' => 'nullable|string|max:255',
                'quantity_hours' => 'nullable|integer|min:1',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            // Se não tem unit_id mas tem unit nome, tentar encontrar ou criar
            if (!$validated['unit_id'] && !empty($validated['unit'])) {
                $unit = Unit::firstOrCreate(
                    ['name' => $validated['unit']],
                    ['description' => 'Created automatically via API']
                );
                $validated['unit_id'] = $unit->id;
            }

            // Preencher campos padrão se não fornecidos
            $validated['course'] = $validated['course'] ?? $certificate->title;
            $validated['quantity_hours'] = $validated['quantity_hours'] ?? $certificate->quantity_hours;

            // Criar o estudante
            $student = $certificate->certificateStudents()->create($validated);
            $student->load('unit');

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'data' => $student
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Criar múltiplos estudantes (para CSV/lote)
     */
    public function storeBatch(Request $request, Certificate $certificate)
    {
        try {
            $request->validate([
                'students' => 'required|array|min:1|max:100', // Limite de 100 por vez
                'students.*.name' => 'required|string|max:255',
                'students.*.cpf' => 'nullable|string|max:20',
                'students.*.document' => 'nullable|string|max:50',
                'students.*.code' => 'nullable|string', // Será gerado se não fornecido
                'students.*.unit' => 'nullable|string|max:255',
                'students.*.unit_id' => 'nullable|exists:units,id',
                'students.*.course' => 'nullable|string|max:255',
                'students.*.quantity_hours' => 'nullable|integer|min:1',
                'students.*.start_date' => 'required|date',
                'students.*.end_date' => 'required|date',
            ]);

            $studentsData = $request->input('students');
            $created = [];
            $errors = [];

            DB::beginTransaction();

            foreach ($studentsData as $index => $studentData) {
                try {
                    // Gerar código se não fornecido
                    if (empty($studentData['code'])) {
                        $studentData['code'] = $this->generateUniqueCode();
                    }

                    // Verificar código único
                    if (CertificateStudent::where('code', $studentData['code'])->exists()) {
                        $studentData['code'] = $this->generateUniqueCode();
                    }

                    // Processar unidade
                    if (!empty($studentData['unit']) && empty($studentData['unit_id'])) {
                        $unit = Unit::firstOrCreate(
                            ['name' => $studentData['unit']],
                            ['description' => 'Created automatically via batch API']
                        );
                        $studentData['unit_id'] = $unit->id;
                    }

                    // Preencher campos padrão
                    $studentData['course'] = $studentData['course'] ?? $certificate->title;
                    $studentData['quantity_hours'] = $studentData['quantity_hours'] ?? $certificate->quantity_hours;

                    // Criar estudante
                    $student = $certificate->certificateStudents()->create($studentData);
                    $student->load('unit');
                    
                    $created[] = $student;

                } catch (\Exception $e) {
                    $errors[] = [
                        'row' => $index + 1,
                        'name' => $studentData['name'] ?? 'Unknown',
                        'error' => $e->getMessage()
                    ];
                }
            }

            if (count($errors) > 0 && count($created) === 0) {
                // Se todos falharam, rollback
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'All students failed to create',
                    'errors' => $errors
                ], 422);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Batch operation completed',
                'created_count' => count($created),
                'error_count' => count($errors),
                'data' => $created,
                'errors' => $errors
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error in batch operation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gerar código único
     */
    public function generateCode(Certificate $certificate)
    {
        try {
            $code = $this->generateUniqueCode();
            
            return response()->json([
                'success' => true,
                'code' => $code
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to generate unique code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gerar código único (método privado)
     */
    private function generateUniqueCode()
    {
        $attempts = 0;
        $maxAttempts = 100;

        do {
            // Gera código com 8 caracteres: 4 letras + 4 números
            $code = strtoupper(Str::random(4)) . rand(1000, 9999);
            
            // Verifica se já existe
            $exists = CertificateStudent::where('code', $code)->exists();
            $attempts++;
            
        } while ($exists && $attempts < $maxAttempts);

        if ($attempts >= $maxAttempts) {
            throw new \Exception('Unable to generate unique code after ' . $maxAttempts . ' attempts');
        }

        return $code;
    }
}