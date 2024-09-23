<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // public function index()
    // {
    //     // Retorna todas as imagens, com o caminho do arquivo
    //     $images = Image::all();
    //     return response()->json($images);
    // }

    // public function store(Request $request)
    // {
    //     // Valida o request para garantir que um arquivo de imagem é enviado
    //     $request->validate([
    //         'image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Ajuste conforme necessário
    //     ]);

    //     // Processa o upload da imagem
    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $path = $file->store('public/images'); // Armazena a imagem na pasta public/images
    //         $filePath = str_replace('public/', '', $path); // Remove a parte 'public/' para o armazenamento no banco

    //         // Cria um novo registro no banco de dados
    //         $image = new Image();
    //         $image->file_path = $filePath;
    //         $image->save();

    //         return response()->json(['message' => 'Image uploaded successfully', 'image' => $image]);
    //     }

    //     return response()->json(['message' => 'No image uploaded'], 400);
    // }
     // Método para lidar com o upload de imagens
     public function upload(Request $request)
     {
         // Valida o arquivo de imagem enviado
         $request->validate([
             'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
         ]);
     
         // Armazena o arquivo no diretório de uploads
         $file = $request->file('image');
         $path = $file->storeAs('public/images', $file->getClientOriginalName());
     
         // Cria um registro da imagem no banco de dados
         $image = Image::create([
             'file_path' => str_replace('public/', '', $path), // Corrige o caminho do arquivo para armazenar no banco
         ]);
     
         // Retorna a resposta com a lista de imagens
         return response()->json([
             'message' => 'Image uploaded successfully',
             'images' => Image::all(), // Retorna todas as imagens
         ], 201);
     }
 
     // Método para listar todas as imagens
     public function indexJson()
     {
         $images = Image::all();
         return response()->json($images);
     }
}
