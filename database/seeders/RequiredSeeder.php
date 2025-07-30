<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequiredSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Creating required data...\n";

        echo "Creating admin user...\n";
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('12341234'),
            'phone' => '081234567890',
            'role' => 'super_admin'
        ]);

        echo "Running IndoRegionSeeder...\n";
        $this->call(IndoRegionSeeder::class);

        echo "Running WithdrawalMethodSeeder...\n";
        $this->call(WithdrawalMethodSeeder::class);

        echo "Running FacilitiesSeeder...\n";
        $this->call(FacilitySeeder::class);
    }
}
