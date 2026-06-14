<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessment extends Model
{
    protected $fillable = [
        'user_id',
        'absensi',
        'disiplin',
        'keterampilan',
        'produktivitas',
        'total',
        'month',
        'year',
    ];

    protected $casts = [
        'absensi' => 'decimal:2',
        'disiplin' => 'decimal:2',
        'keterampilan' => 'decimal:2',
        'produktivitas' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
