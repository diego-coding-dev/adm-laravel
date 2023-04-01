<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateTypeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeUsers = [
            'client',
            'employee'
        ];

        foreach ($typeUsers as $typeUser) {
            DB::table('type_users')->insert([
                'type_user' => $typeUser
            ]);
        }
    }
}
