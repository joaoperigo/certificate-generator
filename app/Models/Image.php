<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    
    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
    protected $fillable = ['name', 'file_path']; // Adicione as colunas que podem ser preenchidas
}
