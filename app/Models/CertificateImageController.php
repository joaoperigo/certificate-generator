<?php

namespace App\Http\Controllers;

use App\Models\CertificateImage;
use Illuminate\Support\Facades\Storage;

class CertificateImageController extends Controller
{
    public function show(CertificateImage $certificateImage)
    {
        // Verifica se o usuário tem acesso ao certificado
        if (auth()->user()->cannot('view', $certificateImage->certificate)) {
            abort(403);
        }

        // Retorna a imagem do storage
        return Storage::response($certificateImage->path);
    }

    public function store($certificate, $pageNumber, $imageData)
    {
        // Remove o header do data URI se existir
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
        $imageData = base64_decode($imageData);

        // Gera um nome único para o arquivo
        $filename = 'certificates/' . $certificate->id . '/page_' . $pageNumber . '_' . uniqid() . '.jpg';
        
        // Salva a imagem
        Storage::put($filename, $imageData);

        // Cria o registro no banco
        return CertificateImage::create([
            'certificate_id' => $certificate->id,
            'page_number' => $pageNumber,
            'path' => $filename
        ]);
    }
}