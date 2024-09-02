<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dettes')->insert([
            [
                'client_id' => 1,
                'date' => now(),
                'montant' => 20000,
                'montantDu' => 15000,
                'montantRestant' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 1,
                'date' => now(),
                'montant' => 30000,
                'montantDu' => 20000,
                'montantRestant' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 3,
                'date' => now(),
                'montant' => 15000,
                'montantDu' => 10000,
                'montantRestant' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 3,
                'date' => now(),
                'montant' => 25000,
                'montantDu' => 20000,
                'montantRestant' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
