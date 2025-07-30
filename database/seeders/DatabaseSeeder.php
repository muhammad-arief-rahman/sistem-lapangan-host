<?php

namespace Database\Seeders;

use App\Models\Facilities;
use App\Models\Field;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Village;
use App\Models\WithdrawMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "Starting database seeding...\n";

        echo "Warning: This will require a clean database.\n";
        $proceed = readline("Do you want to proceed? (y/n): ");
        if (strtolower($proceed) !== 'y') {
            echo "Seeding cancelled.\n";
            return;
        }

        // Clean the database by doing a fresh migration
        echo "Running fresh migration...\n";
        $this->command->call('migrate:fresh', ['--force' => true]);
        DB::beginTransaction();

        // Run the RequiredSeeder to create essential data
        echo "Running RequiredSeeder...\n";
        $this->call(RequiredSeeder::class);

        $proceedDummy = readline("Do you want to proceed with dummy data seeding? (y/n): ");
        if (strtolower($proceedDummy) === 'y') {
            // Run the DummySeeder to create dummy data
            echo "Running DummySeeder...\n";
            $this->call(DummySeeder::class);
        } else {
            echo "Dummy data seeding skipped.\n";
        }

        DB::commit();
        echo "Database seeding completed successfully.\n";
    }
}
