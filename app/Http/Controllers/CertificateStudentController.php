<?php

namespace App\Http\Controllers;

use App\Models\CertificateStudent;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CertificateStudentController extends Controller
{
    public function index(Certificate $certificate)
    {
        try {
            $students = $certificate->certificateStudents()->get();
            return response()->json($students);
        } catch (\Exception $e) {
            Log::error('Error fetching certificate students: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch certificate students'], 500);
        }
    }

    public function store(Request $request, Certificate $certificate)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',  // trocar para nullable depois
                'cpf' => 'nullable|string|max:14',
                'document' => 'nullable|string|max:255',
                'code' => 'required|string|unique:certificate_students,code|not_in:null',
                'unit' => 'nullable|string|max:255',
                'curso' => 'nullable|string|max:255',
                'quantity_hours' => 'nullable|integer|min:1',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            $student = $certificate->certificateStudents()->create($validated);
            return response()->json($student, 201);
        } catch (\Exception $e) {
            Log::error('Error creating certificate student: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create certificate student'], 500);
        }
    }

    public function update(Request $request, Certificate $certificate, CertificateStudent $certificateStudent)
    {
        try {
            Log::info('Updating certificate student', [
                'certificate_id' => $certificate->id,
                'student_id' => $certificateStudent->id,
                'data' => $request->all()
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255', // trocar para nullable depois
                'cpf' => 'nullable|string|max:14',
                'document' => 'nullable|string|max:255',
                'code' => [
                    'required',
                    'string',
                    'not_in:null',
                    Rule::unique('certificate_students', 'code')->ignore($certificateStudent->id)
                ],
                'unit' => 'nullable|string|max:255',
                'curso' => 'nullable|string|max:255',
                'quantity_hours' => 'nullable|integer|min:1',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            $certificateStudent->update($validated);
            return response()->json($certificateStudent);
        } catch (\Exception $e) {
            Log::error('Error updating certificate student: ' . $e->getMessage(), [
                'certificate_id' => $certificate->id,
                'student_id' => $certificateStudent->id,
                'data' => $request->all(),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to update certificate student: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Certificate $certificate, CertificateStudent $certificateStudent)
    {
        try {
            Log::info('Deleting certificate student', [
                'certificate_id' => $certificate->id,
                'student_id' => $certificateStudent->id
            ]);

            $certificateStudent->delete();
            return response()->json(['message' => 'Student removed successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting certificate student: ' . $e->getMessage(), [
                'certificate_id' => $certificate->id,
                'student_id' => $certificateStudent->id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to delete certificate student: ' . $e->getMessage()], 500);
        }
    }

    public function generateUniqueCode()
{
    $maxAttempts = 3;
    $attempt = 0;

    while ($attempt < $maxAttempts) {
        // Generate a random 6-character hexadecimal code
        $code = strtoupper(substr(md5(uniqid()), 0, 6));
        
        // Check if the code exists
        $exists = CertificateStudent::where('code', $code)->exists();
        
        if (!$exists) {
            return response()->json(['code' => $code]);
        }
        
        $attempt++;
    }
    
    return response()->json(['error' => 'Unable to generate unique code'], 500);
}

}