<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks untuk truncate tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = [
            // Kategori 1: Beras & Tepung (1-6)
            [
                'category_id' => 1,
                'name' => 'Beras Putih Premium 5kg',
                'slug' => 'beras-putih-premium-5kg',
                'description' => 'Beras putih bulir panjang, bersih, dan pulen. Kemasan karung 5kg.',
                'image' => 'products/1.jpg',
                'price' => 70000.00,
                'buy_price' => 55000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 1,
                'name' => 'Beras Merah Organik 1kg',
                'slug' => 'beras-merah-organik-1kg',
                'description' => 'Beras merah kaya serat, cocok untuk diet sehat.',
                'image' => 'products/2.jpg',
                'price' => 25000.00,
                'buy_price' => 18000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 1,
                'name' => 'Tepung Terigu Serbaguna 1kg',
                'slug' => 'tepung-terigu-serbaguna-1kg',
                'description' => 'Tepung gandum putih halus untuk berbagai keperluan baking.',
                'image' => 'products/3.jpg',
                'price' => 12000.00,
                'buy_price' => 9000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 1,
                'name' => 'Tepung Maizena (Pati Jagung) 250g',
                'slug' => 'tepung-maizena-pati-jagung-250g',
                'description' => 'Tepung pati jagung halus untuk pengental masakan.',
                'image' => 'products/4.jpg',
                'price' => 8500.00,
                'buy_price' => 6500.00,
                'stock' => 100,
            ],
            [
                'category_id' => 1,
                'name' => 'Oatmeal Instan 500g',
                'slug' => 'oatmeal-instan-500g',
                'description' => 'Gandum oat utuh yang cepat saji untuk sarapan sehat.',
                'image' => 'products/5.jpg',
                'price' => 30000.00,
                'buy_price' => 23000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 1,
                'name' => 'Kacang Hijau Kupas 500g',
                'slug' => 'kacang-hijau-kupas-500g',
                'description' => 'Kacang hijau kupas bersih, cocok untuk bubur atau isian kue.',
                'image' => 'products/6.jpg',
                'price' => 18000.00,
                'buy_price' => 14000.00,
                'stock' => 100,
            ],

            // Kategori 2: Minyak, Gula & Bumbu (7-12)
            [
                'category_id' => 2,
                'name' => 'Minyak Goreng Nabati 1L',
                'slug' => 'minyak-goreng-nabati-1l',
                'description' => 'Minyak goreng kelapa sawit jernih dalam kemasan botol.',
                'image' => 'products/7.jpg',
                'price' => 19000.00,
                'buy_price' => 15000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 2,
                'name' => 'Gula Pasir Putih 1kg',
                'slug' => 'gula-pasir-putih-1kg',
                'description' => 'Gula tebu kristal putih murni, manis alami.',
                'image' => 'products/8.jpg',
                'price' => 16000.00,
                'buy_price' => 12500.00,
                'stock' => 100,
            ],
            [
                'category_id' => 2,
                'name' => 'Garam Laut Halus 500g',
                'slug' => 'garam-laut-halus-500g',
                'description' => 'Garam laut alami beryodium dengan tekstur halus.',
                'image' => 'products/9.jpg',
                'price' => 5000.00,
                'buy_price' => 3500.00,
                'stock' => 100,
            ],
            [
                'category_id' => 2,
                'name' => 'Lada Hitam Bubuk Murni 50g',
                'slug' => 'lada-hitam-bubuk-murni-50g',
                'description' => 'Bubuk lada hitam asli dengan aroma kuat dan pedas.',
                'image' => 'products/10.jpg',
                'price' => 15000.00,
                'buy_price' => 11000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 2,
                'name' => 'Minyak Zaitun Extra Virgin 250ml',
                'slug' => 'minyak-zaitun-extra-virgin-250ml',
                'description' => 'Minyak zaitun murni untuk dressing salad atau menumis.',
                'image' => 'products/11.jpg',
                'price' => 45000.00,
                'buy_price' => 35000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 2,
                'name' => 'Madu Murni Asli 350g',
                'slug' => 'madu-murni-asli-350g',
                'description' => 'Madu hutan asli dalam kemasan toples kaca.',
                'image' => 'products/12.jpg',
                'price' => 55000.00,
                'buy_price' => 42000.00,
                'stock' => 100,
            ],

            // Kategori 3: Mie & Makanan Instan (13-18)
            [
                'category_id' => 3,
                'name' => 'Mie Telur Kering 200g',
                'slug' => 'mie-telur-kering-200g',
                'description' => 'Mie telur kering keriting, cocok untuk mie goreng atau rebus.',
                'image' => 'products/13.jpg',
                'price' => 6000.00,
                'buy_price' => 4500.00,
                'stock' => 100,
            ],
            [
                'category_id' => 3,
                'name' => 'Pasta Spaghetti 500g',
                'slug' => 'pasta-spaghetti-500g',
                'description' => 'Pasta Italia jenis spaghetti dari gandum durum.',
                'image' => 'products/14.jpg',
                'price' => 18000.00,
                'buy_price' => 14000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 3,
                'name' => 'Sarden Kaleng Saus Tomat 425g',
                'slug' => 'sarden-kaleng-saus-tomat-425g',
                'description' => 'Ikan sarden besar dalam saus tomat kental, siap santap.',
                'image' => 'products/15.jpg',
                'price' => 22000.00,
                'buy_price' => 17000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 3,
                'name' => 'Telur Ayam Segar 1 Tray (30 Butir)',
                'slug' => 'telur-ayam-segar-1-tray-30-butir',
                'description' => 'Telur ayam negeri segar dalam kemasan tray karton.',
                'image' => 'products/16.jpg',
                'price' => 55000.00,
                'buy_price' => 45000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 3,
                'name' => 'Roti Tawar Gandum Utuh',
                'slug' => 'roti-tawar-gandum-utuh',
                'description' => 'Roti tawar sehat dari biji gandum utuh kaya serat.',
                'image' => 'products/17.jpg',
                'price' => 20000.00,
                'buy_price' => 15000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 3,
                'name' => 'Keripik Kentang Rasa Original 100g',
                'slug' => 'keripik-kentang-rasa-original-100g',
                'description' => 'Camilan keripik kentang tipis dan renyah dengan garam laut.',
                'image' => 'products/18.jpg',
                'price' => 12000.00,
                'buy_price' => 9000.00,
                'stock' => 100,
            ],

            // Kategori 4: Minuman & Susu (19-24)
            [
                'category_id' => 4,
                'name' => 'Kopi Arabika Bubuk 200g',
                'slug' => 'kopi-arabika-bubuk-200g',
                'description' => 'Kopi bubuk arabika murni dengan aroma yang kaya.',
                'image' => 'products/19.jpg',
                'price' => 35000.00,
                'buy_price' => 27000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 4,
                'name' => 'Teh Hitam Celup (Box isi 50)',
                'slug' => 'teh-hitam-celup-box-isi-50',
                'description' => 'Teh hitam celup klasik, nikmat disajikan panas atau dingin.',
                'image' => 'products/20.jpg',
                'price' => 15000.00,
                'buy_price' => 11000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 4,
                'name' => 'Susu Segar Full Cream 1L',
                'slug' => 'susu-segar-full-cream-1l',
                'description' => 'Susu sapi segar pasteurisasi, kaya kalsium.',
                'image' => 'products/21.jpg',
                'price' => 22000.00,
                'buy_price' => 17000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 4,
                'name' => 'Air Mineral Botol 600ml (Pack isi 12)',
                'slug' => 'air-mineral-botol-600ml-pack-isi-12',
                'description' => 'Air mineral pegunungan alami dalam kemasan praktis.',
                'image' => 'products/22.jpg',
                'price' => 30000.00,
                'buy_price' => 24000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 4,
                'name' => 'Jus Jeruk Asli 1L',
                'slug' => 'jus-jeruk-asli-1l',
                'description' => 'Jus buah jeruk asli tanpa tambahan gula.',
                'image' => 'products/23.jpg',
                'price' => 28000.00,
                'buy_price' => 22000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 4,
                'name' => 'Susu Almond Unsweetened 1L',
                'slug' => 'susu-almond-unsweetened-1l',
                'description' => 'Susu nabati dari kacang almond, tanpa pemanis.',
                'image' => 'products/24.jpg',
                'price' => 45000.00,
                'buy_price' => 35000.00,
                'stock' => 100,
            ],

            // Kategori 5: Perlengkapan Rumah (25-30)
            [
                'category_id' => 5,
                'name' => 'Sabun Batang Putih Alami 3x90g',
                'slug' => 'sabun-batang-putih-alami-3x90g',
                'description' => 'Paket sabun mandi batang, lembut di kulit dengan wangi netral.',
                'image' => 'products/25.jpg',
                'price' => 15000.00,
                'buy_price' => 11000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 5,
                'name' => 'Deterjen Cair Pakaian 1L',
                'slug' => 'deterjen-cair-pakaian-1l',
                'description' => 'Sabun cuci pakaian cair konsentrat, ampuh bersihkan noda.',
                'image' => 'products/26.jpg',
                'price' => 25000.00,
                'buy_price' => 19000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 5,
                'name' => 'Cairan Pencuci Piring Jeruk Nipis 750ml',
                'slug' => 'cairan-pencuci-piring-jeruk-nipis-750ml',
                'description' => 'Sabun cuci piring dengan ekstrak jeruk nipis penghilang lemak.',
                'image' => 'products/27.jpg',
                'price' => 15000.00,
                'buy_price' => 11000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 5,
                'name' => 'Shampoo & Kondisioner 2-in-1 300ml',
                'slug' => 'shampoo-kondisioner-2-in-1-300ml',
                'description' => 'Shampoo perawatan rambut praktis untuk sehari-hari.',
                'image' => 'products/28.jpg',
                'price' => 30000.00,
                'buy_price' => 23000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 5,
                'name' => 'Sikat Gigi Bambu (Pack isi 2)',
                'slug' => 'sikat-gigi-bambu-pack-isi-2',
                'description' => 'Sikat gigi ramah lingkungan dengan gagang bambu.',
                'image' => 'products/29.jpg',
                'price' => 20000.00,
                'buy_price' => 15000.00,
                'stock' => 100,
            ],
            [
                'category_id' => 5,
                'name' => 'Gulungan Tisu Toilet (Pack isi 6)',
                'slug' => 'gulungan-tisu-toilet-pack-isi-6',
                'description' => 'Tisu toilet 3 lapis yang lembut dan kuat.',
                'image' => 'products/30.jpg',
                'price' => 25000.00,
                'buy_price' => 19000.00,
                'stock' => 100,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
