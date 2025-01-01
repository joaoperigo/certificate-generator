<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateImage extends Model
{
    protected $fillable = ['certificate_id', 'page_number', 'path'];

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
}