<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    public function index()
    {
        try {
            $teachers = Teacher::all();
            return response()->json($teachers);
        } catch (\Exception $e) {
            Log::error('Error fetching teachers: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch teachers'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $teacher = Teacher::create($validated);
            return response()->json($teacher, 201);
        } catch (\Exception $e) {
            Log::error('Error creating teacher: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create teacher'], 500);
        }
    }
}