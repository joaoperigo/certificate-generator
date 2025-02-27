<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_id',
        'name',
        'code',
        'cpf',
        'document',
        'unit_id',  // Added unit_id
        'unit',     // Keep for backward compatibility
        'course',
        'quantity_hours',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'quantity_hours' => 'integer'
    ];

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }
    
    /**
     * Get the unit that owns the certificate student.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}