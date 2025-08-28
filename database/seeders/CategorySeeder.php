<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Komputer & Laptop',
                'description' => 'Perangkat komputer desktop, laptop, dan aksesorisnya',
                'created_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Printer & Scanner',
                'description' => 'Perangkat cetak dan scan dokumen',
                'created_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Networking',
                'description' => 'Perangkat jaringan seperti router, switch, access point',
                'created_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Furniture Kantor',
                'description' => 'Meja, kursi, lemari, dan furnitur kantor lainnya',
                'created_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Audio Visual',
                'description' => 'Perangkat presentasi, monitor, speaker, microphone',
                'created_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Kendaraan',
                'description' => 'Kendaraan operasional perusahaan',
                'created_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Alat Tulis & ATK',
                'description' => 'Alat tulis kantor dan perlengkapan administrasi',
                'created_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Peralatan Keamanan',
                'description' => 'CCTV, alarm, sistem keamanan lainnya',
                'created_at' => now()->subMonths(6),
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}