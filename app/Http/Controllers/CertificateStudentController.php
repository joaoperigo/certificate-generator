<?php

namespace App\Http\Controllers;

use App\Models\CertificateStudent;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                'name' => 'required|string|max:255',
                'cpf' => 'nullable|string|max:14',
                'document' => 'nullable|string|max:255',
                'code' => 'nullable|string|max:255',
                'unit' => 'nullable|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date'
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
                'name' => 'required|string|max:255',
                'cpf' => 'nullable|string|max:14',
                'document' => 'nullable|string|max:255',
                'code' => 'nullable|string|max:255',
                'unit' => 'nullable|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date'
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
}