<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|max:10240',
                'certificate_id' => 'required|numeric|exists:certificates,id',
                'page_number' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buscar o certificado
            $certificate = Certificate::findOrFail($request->certificate_id);
            
            // Criar nome do arquivo incluindo o ID do certificado
            $sanitizedTitle = Str::slug($certificate->title);
            $filename = sprintf(
                'cert_%d_%s_page_%d_%s.%s',
                $certificate->id,
                $sanitizedTitle,
                $request->page_number + 1,
                Str::random(8),
                $request->file('image')->extension()
            );

            // Exemplo de nome gerado:
            // cert_123_certificado-de-exemplo_page_1_a7b8c9d0.jpg

            // Caminho específico
            $path = 'certificates/backgrounds/' . $filename;

            // Salvar o arquivo
            if (!Storage::disk('private')->putFileAs('certificates/backgrounds', $request->file('image'), $filename)) {
                throw new \Exception('Failed to store file');
            }

            // Criar registro no banco
            $image = Image::create([
                'path' => $path,
                'name' => $filename,
                'certificate_id' => $request->certificate_id
            ]);

            Log::info('Background image uploaded successfully', [
                'path' => $path,
                'certificate_id' => $request->certificate_id,
                'page' => $request->page_number,
                'image_id' => $image->id,
                'filename' => $filename
            ]);

            return response()->json([
                'success' => true,
                'image' => $image,
                'url' => route('images.show', $image->id)
            ]);

        } catch (\Exception $e) {
            Log::error('Background image upload failed', [
                'error' => $e->getMessage(),
                'certificate_id' => $request->certificate_id ?? null,
                'page' => $request->page_number ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Image $image)
    {
        try {
            // Verifica se o arquivo existe
            if (Storage::disk('private')->exists($image->path)) {
                // Deleta o arquivo físico
                Storage::disk('private')->delete($image->path);
            }

            // Deleta o registro do banco
            $image->delete();

            Log::info('Image deleted successfully', [
                'image_id' => $image->id,
                'path' => $image->path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete image', [
                'image_id' => $image->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image'
            ], 500);
        }
    }

    public function show(Image $image)
    {
        try {
            // Verifica se o arquivo existe
            if (!Storage::disk('private')->exists($image->path)) {
                throw new \Exception('Image file not found');
            }

            return response()->stream(
                function () use ($image) {
                    echo Storage::disk('private')->get($image->path);
                },
                200,
                [
                    'Content-Type' => Storage::disk('private')->mimeType($image->path),
                    'Content-Disposition' => 'inline; filename="' . basename($image->path) . '"',
                    'Cache-Control' => 'public, max-age=86400'
                ]
            );

        } catch (\Exception $e) {
            Log::error('Failed to show image', [
                'image_id' => $image->id,
                'path' => $image->path,
                'error' => $e->getMessage()
            ]);

            return abort(404);
        }
    }
}