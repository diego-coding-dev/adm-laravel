<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = [
            'type_user_id' => 2,
            'name' => 'funcionario 1',
            'email' => 'funcionario_1@mail.com',
            'password' => Hash::make('funcionario1'),
            'is_active' => true
        ];

        DB::table('employees')->insert($employee);
    }
}
