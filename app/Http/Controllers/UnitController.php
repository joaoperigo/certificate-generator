<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UnitController extends Controller
{
    public function index()
    {
        try {
            $units = Unit::all();
            return response()->json($units);
        } catch (\Exception $e) {
            Log::error('Error fetching units: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch units'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            $unit = Unit::create($validated);
            return response()->json($unit, 201);
        } catch (\Exception $e) {
            Log::error('Error creating unit: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create unit'], 500);
        }
    }
    
    public function destroy(Unit $unit)
    {
        try {
            // Check if the unit is being used by any certificate students
            $inUse = $unit->certificateStudents()->exists();
            
            if ($inUse) {
                return response()->json(['error' => 'This unit is in use and cannot be deleted'], 422);
            }
            
            $unit->delete();
            return response()->json(['message' => 'Unit deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting unit: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete unit'], 500);
        }
    }
}