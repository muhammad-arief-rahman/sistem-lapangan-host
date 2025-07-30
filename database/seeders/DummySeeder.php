<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Creating dummy data...\n";

        // Create 12 communities
        echo "Creating communities...\n";
        for ($i = 1; $i <= 1; $i++) {
            User::create([
                'name' => "Community #$i",
                'email' => "community$i@email.com",
                'password' => bcrypt('12341234'),
                'phone' => "08130001$i",
            ]);
        }

        // Create 4 field managers
        echo "Creating field managers...\n";
        $field_managers = [];

        for ($i = 1; $i <= 2; $i++) {
            $field_managers[] = User::create([
                'name' => "Field Manager #$i",
                'email' => "field_manager$i@email.com",
                'password' => bcrypt('12341234'),
                'phone' => "08130002$i",
                'role' => 'field_manager'
            ]);
        }

        // Create 5 photographers
        echo "Creating photographers...\n";
        $photographers = [];

        for ($i = 1; $i <= 2; $i++) {
            $photographers[] = User::create([
                'name' => "Photographer #$i",
                'email' => "photo$i@email.com",
                'password' => bcrypt('12341234'),
                'phone' => '081234567890',
                'role' => 'photographer'
            ])
                ->service()
                ->create([
                    'price_per_hour' => $i * 10000,
                    'description' => "Deskripsi Fotografer #$i",
                    'portfolio' => 'https://www.youtube.com/watch?v=3ZeHmdJnny4',
                ]);
        }

        // Create 6 referees
        echo "Creating referees...\n";
        $referees = [];

        for ($i = 1; $i <= 2; $i++) {
            $referees[] = User::create([
                'name' => "Referee #$i",
                'email' => "referee$i@email.com",
                'password' => bcrypt('12341234'),
                'phone' => '081234567890',
                'role' => 'referee'
            ])
                ->service()
                ->create([
                    'price_per_hour' => $i * 15000,
                    'description' => "Deskripsi Wasit #$i",
                    'portfolio' => 'https://www.youtube.com/watch?v=3ZeHmdJnny4',
                ]);
        }


        // Create 8 Fields and randomly assign field managers
        echo "Creating fields...\n";
        $fields = [];

        $villaged_ids = Village::whereHas('district.regency', function ($query) {
            $query->where('id', PEKANBARU_REGENCY_ID); // Assuming you want to filter by a specific province ID
        })->pluck('id');

        for ($i = 1; $i <= 22; $i++) {
            $fields[] = Field::create([
                'name' => "Lapangan Sepak Bola " . chr(64 + $i), // A, B, C, D, ...
                'location' => "Jl. Contoh No. $i, Jakarta",
                'price_per_hour' => $i * 15000,
                'description' => "Deskripsi Lapangan Sepak Bola #$i",
                'manager_id' => $field_managers[array_rand($field_managers)]->id,
                'village_id' => $villaged_ids->random(),
            ]);
        }
    }
}
