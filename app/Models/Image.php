<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'name',
        'certificate_id'
    ];

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
}