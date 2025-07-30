<?php

namespace Database\Seeders;

use App\Models\Facilities;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Creating default facilities...\n";
        $default_facilities = [
            ['name' => 'Parkir Mobil', 'icon' => '<i class="fa-solid fa-car"></i>'],
            ['name' => 'Parkir Motor', 'icon' => '<i class="fa-solid fa-motorcycle"></i>'],
            ['name' => 'Toilet', 'icon' => '<i class="fa-solid fa-toilet-paper"></i>'],
            ['name' => 'Wi-Fi', 'icon' => '<i class="fa-solid fa-wifi"></i>'],
            ['name' => 'Musholla', 'icon' => '<i class="fa-solid fa-mosque"></i>'],
            ['name' => 'Cafe & Resto', 'icon' => '<i class="fa-solid fa-utensils"></i>'],
            ['name' => 'Shower', 'icon' => '<i class="fa-solid fa-shower"></i>'],
            ['name' => 'Jual Minuman', 'icon' => '<i class="fa-solid fa-mug-saucer"></i>'],
            ['name' => 'Tribun Penonton', 'icon' => '<i class="fa-solid fa-couch"></i>'],
            ['name' => 'Jual Makanan Ringan', 'icon' => '<i class="fa-solid fa-hotdog"></i>']
        ];

        foreach ($default_facilities as $facility) {
            Facilities::create([
                'name' => $facility['name'],
                'icon' => $facility['icon'] ?? null,
            ]);
        }
    }
}
