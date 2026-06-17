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
        'saw_score',
        'month',
        'year',
    ];

    protected $casts = [
        'absensi' => 'decimal:2',
        'disiplin' => 'decimal:2',
        'keterampilan' => 'decimal:2',
        'produktivitas' => 'decimal:2',
        'saw_score' => 'decimal:4',
        'total' => 'decimal:2',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function calculateSaw(int $month, int $year): void
{
    $items = self::where('month', $month)
        ->where('year', $year)
        ->get();

    if ($items->isEmpty()) {
        return;
    }

    $maxAbsensi = $items->max('absensi');
    $maxDisiplin = $items->max('disiplin');
    $maxProduktivitas = $items->max('produktivitas');
    $maxKeterampilan = $items->max('keterampilan');

    foreach ($items as $item) {

        $score =
            (($item->absensi / max($maxAbsensi, 1)) * 0.20)
            +
            (($item->disiplin / max($maxDisiplin, 1)) * 0.25)
            +
            (($item->produktivitas / max($maxProduktivitas, 1)) * 0.35)
            +
            (($item->keterampilan / max($maxKeterampilan, 1)) * 0.20);

        $item->update([
            'saw_score' => round($score, 4),
        ]);
    }
}
}
