<?php

namespace Database\Seeders;

use App\Models\Obat;
use App\Models\Categories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID Kategori berdasarkan nama untuk keamanan relasi
        $catBebas = Categories::where('name_category', 'Obat Bebas')->first()->id ?? 1;
        $catBebasTerbatas = Categories::where('name_category', 'Obat Bebas Terbatas')->first()->id ?? 2;
        $catKeras = Categories::where('name_category', 'Obat Keras')->first()->id ?? 3;
        $catSuplemen = Categories::where('name_category', 'Suplemen & Vitamin')->first()->id ?? 4;
        $catAlkes = Categories::where('name_category', 'Alat Kesehatan')->first()->id ?? 5;
        $catHerbal = Categories::where('name_category', 'Herbal & Jamu')->first()->id ?? 6;
        $catSalep = Categories::where('name_category', 'Salep & Kosmetik')->first()->id ?? 7;

        $obats = [
            [
                'barcode_sku' => '8999901101234',
                'nama_obat' => 'Amoxicillin 500mg',
                'nama_generik' => 'Amoxicillin Trihydrate',
                'categories_id' => $catKeras,
                'satuan_kemasan' => 'Strip',
                'harga_jual_umum' => 18000,
                'harga_jual_medis' => 15000,
                'stok' => 450,
                'batas_stok_minimum' => 50,
            ],
            [
                'barcode_sku' => '8999902102345',
                'nama_obat' => 'Paracetamol 500mg',
                'nama_generik' => 'Acetaminophen',
                'categories_id' => $catBebas,
                'satuan_kemasan' => 'Strip',
                'harga_jual_umum' => 3000,
                'harga_jual_medis' => 2500,
                'stok' => 800,
                'batas_stok_minimum' => 100,
            ],
            [
                'barcode_sku' => '8999903103456',
                'nama_obat' => 'Lisinopril 10mg',
                'nama_generik' => 'Lisinopril',
                'categories_id' => $catKeras,
                'satuan_kemasan' => 'Tablet',
                'harga_jual_umum' => 12750,
                'harga_jual_medis' => 11000,
                'stok' => 120,
                'batas_stok_minimum' => 20,
            ],
            [
                'barcode_sku' => '8999904104567',
                'nama_obat' => 'Sangobion Kapsul',
                'nama_generik' => 'Fe Gluconate, Vitamin & Minerals',
                'categories_id' => $catSuplemen,
                'satuan_kemasan' => 'Strip',
                'harga_jual_umum' => 19500,
                'harga_jual_medis' => 17000,
                'stok' => 200,
                'batas_stok_minimum' => 30,
            ],
            [
                'barcode_sku' => '8999905105678',
                'nama_obat' => 'Decolgen Tablet',
                'nama_generik' => 'Paracetamol, Phenylpropanolamine HCl, Chlorpheniramine Maleate',
                'categories_id' => $catBebasTerbatas,
                'satuan_kemasan' => 'Strip',
                'harga_jual_umum' => 6500,
                'harga_jual_medis' => 5500,
                'stok' => 350,
                'batas_stok_minimum' => 50,
            ],
            [
                'barcode_sku' => '8999906106789',
                'nama_obat' => 'Betadine Solution 15ml',
                'nama_generik' => 'Povidone Iodine 10%',
                'categories_id' => $catAlkes,
                'satuan_kemasan' => 'Botol',
                'harga_jual_umum' => 22000,
                'harga_jual_medis' => 19000,
                'stok' => 80,
                'batas_stok_minimum' => 15,
            ],
            [
                'barcode_sku' => '8999907107890',
                'nama_obat' => 'Tolak Angin Cair',
                'nama_generik' => 'Herbal Extract',
                'categories_id' => $catHerbal,
                'satuan_kemasan' => 'Sachet',
                'harga_jual_umum' => 4000,
                'harga_jual_medis' => 3500,
                'stok' => 600,
                'batas_stok_minimum' => 100,
            ],
            [
                'barcode_sku' => '8999908108901',
                'nama_obat' => 'Bioplacenton Gel 15g',
                'nama_generik' => 'Placenta Extract, Neomycin Sulfate',
                'categories_id' => $catSalep,
                'satuan_kemasan' => 'Tube',
                'harga_jual_umum' => 28500,
                'harga_jual_medis' => 25000,
                'stok' => 95,
                'batas_stok_minimum' => 10,
            ],
            [
                'barcode_sku' => '8999909109012',
                'nama_obat' => 'Neurobion Forte',
                'nama_generik' => 'Vitamin B1, B6, B12',
                'categories_id' => $catSuplemen,
                'satuan_kemasan' => 'Strip',
                'harga_jual_umum' => 45000,
                'harga_jual_medis' => 40000,
                'stok' => 150,
                'batas_stok_minimum' => 25,
            ],
            [
                'barcode_sku' => '8999910110123',
                'nama_obat' => 'Cataflam 50mg',
                'nama_generik' => 'Diclofenac Potassium',
                'categories_id' => $catKeras,
                'satuan_kemasan' => 'Strip',
                'harga_jual_umum' => 84000,
                'harga_jual_medis' => 76000,
                'stok' => 75,
                'batas_stok_minimum' => 15,
            ],
            [
                'barcode_sku' => '8993498210230',
                'nama_obat' => 'Antimo Tablet',
                'nama_generik' => 'Dimenhydrinate',
                'categories_id' => $catBebasTerbatas,
                'satuan_kemasan' => 'Tablet',
                'harga_jual_umum' => 5500,
                'harga_jual_medis' => 4500,
                'stok' => 150,
                'batas_stok_minimum' => 20,
            ],
        ];

        foreach ($obats as $obat) {
            Obat::firstOrCreate(['barcode_sku' => $obat['barcode_sku']], $obat);
        }
    }
}
