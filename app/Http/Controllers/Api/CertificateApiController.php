<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateApiController extends Controller
{
    /**
     * Listar todos os certificados para API
     */
    public function index(Request $request)
    {
        try {
            // Buscar certificados com relacionamentos
            $certificates = Certificate::with(['categories', 'teachers'])
                ->select('id', 'title', 'quantity_hours', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc')
                ->get();

            // Transformar os dados para uma resposta mais limpa
            $formattedCertificates = $certificates->map(function ($certificate) {
                return [
                    'id' => $certificate->id,
                    'title' => $certificate->title,
                    'quantity_hours' => $certificate->quantity_hours,
                    'categories' => $certificate->categories->pluck('name')->toArray(),
                    'teachers' => $certificate->teachers->pluck('name')->toArray(),
                    'created_at' => $certificate->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $certificate->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Certificates retrieved successfully',
                'total' => $formattedCertificates->count(),
                'data' => $formattedCertificates
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving certificates',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}