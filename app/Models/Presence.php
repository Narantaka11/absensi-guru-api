<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    protected $fillable = [
        'user_id',
        'location_id',
        'presence_date',

        // CHECK IN
        'check_in_time',
        'check_in_photo',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_distance_meters',
        'check_in_is_within_radius',
        'check_in_server_time',
        'check_in_device_info',

        // CHECK OUT
        'check_out_time',
        'check_out_photo',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_distance_meters',
        'check_out_is_within_radius',
        'check_out_server_time',
        'check_out_device_info',

        // STATUS
        'status',
        'notes',
        'attachment',
        'late_minutes',
    ];

    protected $casts = [
        'presence_date'              => 'date',
        'check_in_time'              => 'datetime:H:i:s',
        'check_out_time'             => 'datetime:H:i:s',
        'check_in_server_time'       => 'datetime',
        'check_out_server_time'      => 'datetime',
        'check_in_is_within_radius'  => 'boolean',
        'check_out_is_within_radius' => 'boolean',
    ];

    // RELATIONSHIPS

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    // HELPERS

    public function isLate(): bool
    {
        if (!$this->check_in_time) {
            return false;
        }

        $checkIn = Carbon::parse($this->check_in_time);
        $deadline = Carbon::createFromTimeString('07:00:00');

        return $checkIn->isAfter($deadline);
    }

    public function getWorkHours(): ?float
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return null;
        }

        $checkIn = Carbon::parse($this->check_in_time);
        $checkOut = Carbon::parse($this->check_out_time);

        return round(
            $checkIn->diffInMinutes($checkOut) / 60,
            2
        );
    }

    public function isWithinSchoolRadius(
        float $schoolLat = -6.2088,
        float $schoolLng = 106.8456,
        float $radiusMeters = 200
    ): bool {
        if (!is_null($this->check_in_is_within_radius)) {
            return $this->check_in_is_within_radius;
        }

        if (
            !$this->check_in_latitude ||
            !$this->check_in_longitude
        ) {
            return false;
        }

        $distance = $this->calculateDistanceMeters(
            $this->check_in_latitude,
            $this->check_in_longitude,
            $schoolLat,
            $schoolLng
        );

        return $distance <= $radiusMeters;
    }

    public function hasCheckedOut(): bool
    {
        return !is_null($this->check_out_time);
    }

    public function isPresent(): bool
    {
        return in_array($this->status, [
            'hadir',
            'terlambat'
        ]);
    }

    public function isPermission(): bool
    {
        return $this->status === 'izin';
    }

    public function isSick(): bool
    {
        return $this->status === 'sakit';
    }

    public function isAbsent(): bool
    {
        return $this->status === 'alpa';
    }

    // PRIVATE HELPERS

    private function calculateDistanceMeters(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a =
            sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLng / 2) ** 2;

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earthRadius * $c;
    }
}
