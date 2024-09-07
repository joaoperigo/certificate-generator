<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        // Validação do arquivo de imagem
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Obtenha o arquivo da requisição
        $file = $request->file('image');
        
        // Defina o caminho de destino para o diretório 'public/images'
        $destinationPath = public_path('images'); // Este caminho irá salvar diretamente na pasta 'public/images'

        // Mova o arquivo para a pasta 'public/images' com seu nome original
        $fileName = time() . '_' . $file->getClientOriginalName(); // Nomeia o arquivo com timestamp para evitar conflitos
        $file->move($destinationPath, $fileName);

        // Salva a imagem no banco de dados
        $image = new Image();
        $image->file_path = 'images/' . $fileName; // Caminho relativo para a pasta 'public/images'
        $image->name = $fileName;
        $image->save();

        return redirect()->back()->with('success', 'Image uploaded successfully');
    }
    // public function create()
    // {
    //     $images = Image::all();
    //     return view('certificates.create', compact('images'));
    // }
}
