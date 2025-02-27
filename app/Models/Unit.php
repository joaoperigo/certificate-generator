<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Get the certificate students for the unit.
     */
    public function certificateStudents(): HasMany
    {
        return $this->hasMany(CertificateStudent::class);
    }
}