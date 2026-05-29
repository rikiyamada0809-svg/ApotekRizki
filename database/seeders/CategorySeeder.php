<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name_category' => 'Obat Bebas'],
            ['name_category' => 'Obat Bebas Terbatas'],
            ['name_category' => 'Obat Keras'],
            ['name_category' => 'Suplemen & Vitamin'],
            ['name_category' => 'Alat Kesehatan'],
            ['name_category' => 'Herbal & Jamu'],
            ['name_category' => 'Salep & Kosmetik'],
        ];

        foreach ($categories as $category) {
            Categories::firstOrCreate($category);
        }
    }
}
