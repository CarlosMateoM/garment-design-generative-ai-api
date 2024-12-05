<?php

namespace Database\Seeders;

use App\Models\KeywordsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeywordsCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'colores',
            'estilos',
            'ocasiones',
            'patrones',
            'genero',
            'edades',
            'tipo de prenda',
        ];

        foreach ($categories as $category) {
            KeywordsCategory::create([
                'name' => $category,
            ]);
        }
    }
}
