<?php

namespace App\Http\Controllers;

use App\Models\CertificateStudent;
use Illuminate\Http\Request;

class PublicCertificateController extends Controller
{
    public function show()
    {
        $code = request('code');
        $student = null;

        if ($code) {
            $student = CertificateStudent::where('code', $code)->first();
        }

        return view('certificate_students.show', compact('student'));
    }
}