<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nom' => 'Diagne',
                'prenom' => 'Papa Faly',
                'email' => 'john.doe@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'client_id' => null,

            ],
            [
                'nom' => 'Léa',
                'prenom' => 'Coco',
                'email' => 'jane.doe@example.com',
                'password' => bcrypt('password'),
                'role' => 'boutiquier',
                'created_at' => now(),
                'updated_at' => now(),
                'client_id' => null,
            ],
            [
                'nom' => 'Bamba',
                'prenom' => 'Mbow',
                'email' => 'bamba.doe@example.com',
                'password' => bcrypt('password'),
                'role' => 'client', // Définir le rôle directement ici
                'created_at' => now(),
                'updated_at' => now(),
                'client_id' => 1,
            ],
        ]);
    }
}
