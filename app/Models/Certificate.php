<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; 

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'data', 'quantity_hours'];

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

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}