<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeProduct;

class CreateTypeProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeProducts = [
            'bebidas',
            'entradas',
            'sobremesas'
        ];

        foreach ($typeProducts as $typeProduct) {
            TypeProduct::create([
                'description' => $typeProduct
            ]);
        }
    }
}
