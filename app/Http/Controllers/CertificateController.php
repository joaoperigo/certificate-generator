<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::all();
        return view('certificates.index', compact('certificates'));
    }

    public function show($id)
    {
        $certificate = Certificate::findOrFail($id);
        $title = $certificate->title;
        $certificateData = json_decode($certificate->data, true); // Decodifica o JSON para um array associativo
    
        return view('certificates.show', compact('title', 'certificateData'));
    }

    public function create()
    {
        return view('certificates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'data' => 'required|json',
        ]);

        Certificate::create($request->only('title', 'data'));

        return redirect()->route('certificates.index')
            ->with('success', 'Certificate created successfully.');
    }

    public function edit(Certificate $certificate)
    {
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