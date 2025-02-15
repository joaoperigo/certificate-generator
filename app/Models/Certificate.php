<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'data'];

    protected $casts = [
        'data' => 'json',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function certificateStudents()
    {
        return $this->hasMany(CertificateStudent::class);
    }
}