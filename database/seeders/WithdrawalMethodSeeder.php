<?php

namespace Database\Seeders;

use App\Models\WithdrawMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WithdrawalMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Creating withdrawal methods...\n";
        $withdrawal_methods = [
            ['name' => 'Mandiri', 'category' => 'Bank'],
            ['name' => 'BCA', 'category' => 'Bank'],
            ['name' => 'BNI', 'category' => 'Bank'],
            ['name' => 'BRI', 'category' => 'Bank'],
            ['name' => 'DANA', 'category' => 'E-Wallet'],
            ['name' => 'OVO', 'category' => 'E-Wallet'],
            ['name' => 'Gopay', 'category' => 'E-Wallet'],
            ['name' => 'ShopeePay', 'category' => 'E-Wallet'],
        ];

        foreach ($withdrawal_methods as $method) {
            WithdrawMethod::create($method);
        }
    }
}
