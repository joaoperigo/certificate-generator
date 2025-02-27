<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Image;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['teachers', 'categories'])->get();
        return view('certificates.index', compact('certificates'));
    }

    public function show(Certificate $certificate)
    {
        $certificateData = json_decode($certificate->data, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $certificate->pages = $certificateData['pages'] ?? [];
            $certificate->orientation = $certificateData['orientation'] ?? 'landscape';
            $certificate->dimensions = $certificateData['dimensions'] ?? [
                'width' => 303.02,
                'height' => 215.98
            ];
        } else {
            $certificate->pages = [];
            $certificate->orientation = 'landscape';
            $certificate->dimensions = [
                'width' => 303.02,
                'height' => 215.98
            ];
        }

        return view('certificates.show', compact('certificate'));
    }

    public function updateTexts(Request $request, Certificate $certificate)
    {
        $validatedData = $request->validate([
            'data' => 'required|json',
        ]);

        $certificate->data = $validatedData['data'];
        $certificate->save();

        return response()->json(['message' => 'Certificate texts updated successfully']);
    }
    public function create()
    {
        $images = Image::all();
        // return view('certificates.create', compact('images'));
        return view('certificates.create', compact('images'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'data' => 'required|json',
            'quantity_hours' => 'nullable|integer|min:1'
        ]);
    
        $certificate = Certificate::create($validatedData);
    
        return response()->json($certificate, 201);
    }

    public function edit(Certificate $certificate)
    {
        // Load the relationships
        $certificate->load(['teachers', 'categories']);
    
        $certificateData = json_decode($certificate->data, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $certificate->pages = $certificateData['pages'] ?? [];
            // Also extract orientation if present
            $certificate->orientation = $certificateData['orientation'] ?? 'landscape';
            $certificate->dimensions = $certificateData['dimensions'] ?? [
                'width' => 303.02,
                'height' => 215.98
            ];
        } else {
            $certificate->pages = [['backgroundImage' => null, 'objects' => []]];
            $certificate->orientation = 'landscape';
            $certificate->dimensions = ['width' => 303.02, 'height' => 215.98];
        }
    
        return view('certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'data' => 'required|json',
            'quantity_hours' => 'nullable|integer|min:1'
        ]);
    
        $certificate->update($validatedData);
    
        return response()->json($certificate, 200);
    }

    // public function destroy(Certificate $certificate)
    // {
    //     $certificate->delete();

    //     return redirect()->route('certificates.index')
    //         ->with('success', 'Certificate deleted successfully.');
    // }
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return response()->json(['message' => 'Certificate deleted successfully']);
    }

    // Category and teacher relations
    public function updateCategories(Request $request, Certificate $certificate)
{
    try {
        $validated = $request->validate([
            'categories' => 'present|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $certificate->categories()->sync($validated['categories']);
        return response()->json(['message' => 'Categories updated successfully']);
    } catch (\Exception $e) {
        \Log::error('Error updating certificate categories: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to update categories'], 500);
    }
}

public function updateTeachers(Request $request, Certificate $certificate)
{
    try {
        $validated = $request->validate([
            'teachers' => 'present|array',
            'teachers.*' => 'exists:teachers,id'
        ]);

        $certificate->teachers()->sync($validated['teachers']);
        return response()->json(['message' => 'Teachers updated successfully']);
    } catch (\Exception $e) {
        \Log::error('Error updating certificate teachers: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to update teachers'], 500);
    }
}
}