<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Sesuaikan koordinat & radius dengan lokasi sekolah yang sebenarnya
        Location::updateOrCreate(
            ['name' => 'Gedung Utama Sekolah'],
            [
                'address'       => 'Universitas BSI Kampus Margonda',
                'latitude'      => -6.394678337916196,  // ← ganti dengan koordinat GPS sekolah
                'longitude'     => 106.87116289815354,  // ← ganti dengan koordinat GPS sekolah
                'radius_meters' => 200,        // radius valid 200 meter
                'is_active'     => true,
            ]
        );
    }
}

