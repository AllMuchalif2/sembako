<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks untuk truncate tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            [
                'id' => 1,
                'name' => 'Beras & Tepung',
                'slug' => 'beras-tepung',
            ],
            [
                'id' => 2,
                'name' => 'Minyak, Gula & Bumbu',
                'slug' => 'minyak-gula-bumbu',
            ],
            [
                'id' => 3,
                'name' => 'Mie & Makanan Instan',
                'slug' => 'mie-makanan-instan',
            ],
            [
                'id' => 4,
                'name' => 'Minuman & Susu',
                'slug' => 'minuman-susu',
            ],
            [
                'id' => 5,
                'name' => 'Perlengkapan Rumah',
                'slug' => 'perlengkapan-rumah',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
