<?php

namespace Database\Seeders;

use App\Models\PawnTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PawnTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PawnTransaction::create([
            'no_sbg' => '250604SMG000001',
            'customer_id' => 1, // Assuming customer exists
            'item_name' => 'iPhone 13 Pro',
            'item_description' => 'Smartphone Apple iPhone 13 Pro 128GB',
            'item_category' => 'Elektronik',
            'item_condition' => 'Sangat Baik',
            'item_completeness' => 'Lengkap',
            'officer_appraisal_min' => 3000000,
            'officer_appraisal_max' => 3500000,
            'loan_amount' => 3200000,
            'final_appraisal' => 3300000,
            'status' => 'approved',
            'branch_id' => 1, // Assuming branch exists
            'officer_id' => 2, // Assuming officer exists
            'admin_id' => 3, // Assuming admin exists
            'transaction_date' => now()->subDays(5),
            'approval_date' => now()->subDays(4),
        ]);

        PawnTransaction::create([
            'no_sbg' => '250604MGL000001',
            'customer_id' => 2,
            'item_name' => 'Laptop Asus',
            'item_description' => 'Laptop Asus ROG Gaming',
            'item_category' => 'Elektronik',
            'item_condition' => 'Baik',
            'item_completeness' => 'Lengkap',
            'officer_appraisal_min' => 2500000,
            'officer_appraisal_max' => 3000000,
            'loan_amount' => 2800000,
            'final_appraisal' => null,
            'status' => 'pending',
            'branch_id' => 2,
            'officer_id' => 4,
            'admin_id' => null,
            'transaction_date' => now()->subDays(2),
            'approval_date' => null,
        ]);

        PawnTransaction::create([
            'no_sbg' => '250604KRM000001',
            'customer_id' => 3,
            'item_name' => 'Samsung A54',
            'item_description' => 'Smartphone Samsung Galaxy A54',
            'item_category' => 'Elektronik',
            'item_condition' => 'Sangat Baik',
            'item_completeness' => 'Lengkap',
            'officer_appraisal_min' => 1000000,
            'officer_appraisal_max' => 1300000,
            'loan_amount' => 1200000,
            'final_appraisal' => 1250000,
            'status' => 'approved',
            'branch_id' => 3,
            'officer_id' => 5,
            'admin_id' => 3,
            'transaction_date' => now()->subDays(7),
            'approval_date' => now()->subDays(6),
        ]);
    }
}
