<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Image;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::all();
        return view('certificates.index', compact('certificates'));
    }

    public function show(Certificate $certificate)
    {
        $certificateData = json_decode($certificate->data, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $certificate->pages = $certificateData['pages'] ?? [];
        } else {
            $certificate->pages = [];
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
        ]);

        $certificate = Certificate::create([
            'title' => $validatedData['title'],
            'data' => $validatedData['data'],
        ]);

        return response()->json($certificate, 201);
    }

    public function edit(Certificate $certificate)
    {
        $certificateData = json_decode($certificate->data, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $certificate->pages = $certificateData['pages'] ?? [];
        } else {
            $certificate->pages = [
                [
                    'backgroundImage' => null,
                    'objects' => []
                ]
            ];
        }
    
        return view('certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'data' => 'required|json',
        ]);

        $certificate->update($request->only('title', 'data'));

        return redirect()->route('certificates.index')
            ->with('success', 'Certificate updated successfully.');
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('certificates.index')
            ->with('success', 'Certificate deleted successfully.');
    }
}