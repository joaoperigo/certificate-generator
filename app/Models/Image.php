<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'file_path'];

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
}
