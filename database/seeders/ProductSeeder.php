<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Subcategory;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Bicycles - Road Bikes
            [
                'name' => 'Trek Domane SL 6 Road Bike',
                'subcategory' => 'Road Bikes',
                'price' => 3499.99,
                'description' => 'Endurance road bike with carbon frame and Shimano 105 groupset. Perfect for long rides and comfortable cycling.',
                'stock' => 15,
                'is_active' => 1,
            ],
            [
                'name' => 'Specialized Allez Sport Road Bike',
                'subcategory' => 'Road Bikes',
                'price' => 1200.00,
                'description' => 'Entry-level road bike with aluminum frame and reliable components for beginners.',
                'stock' => 25,
                'is_active' => 1,
            ],

            // Bicycles - Mountain Bikes
            [
                'name' => 'Giant Trance X 29er Mountain Bike',
                'subcategory' => 'Mountain Bikes',
                'price' => 3750.00,
                'description' => 'Full suspension mountain bike with 29-inch wheels for technical trails.',
                'stock' => 12,
                'is_active' => 1,
            ],
            [
                'name' => 'Santa Cruz Hightower Carbon C Mountain Bike',
                'subcategory' => 'Mountain Bikes',
                'price' => 5299.99,
                'description' => 'High-performance carbon mountain bike for aggressive trail riding.',
                'stock' => 8,
                'is_active' => 1,
            ],

            // Bicycles - Electric Bikes
            [
                'name' => 'Rad Power Bikes RadRover 6 Plus',
                'subcategory' => 'Electric Bikes (E-Bikes)',
                'price' => 1799.00,
                'description' => 'Fat tire electric bike with 750W motor and 45+ mile range.',
                'stock' => 20,
                'is_active' => 1,
            ],

            // Components - Wheels & Rims
            [
                'name' => 'Zipp 404 Firecrest Carbon Clincher Wheelset',
                'subcategory' => 'Wheels & Rims',
                'price' => 2450.00,
                'description' => 'Aero carbon wheelset for road cycling and triathlon.',
                'stock' => 10,
                'is_active' => 1,
            ],
            [
                'name' => 'DT Swiss XM 1700 Spline Wheelset',
                'subcategory' => 'Wheels & Rims',
                'price' => 850.00,
                'description' => 'Durable alloy wheels for mountain biking and trail riding.',
                'stock' => 18,
                'is_active' => 1,
            ],

            // Components - Chains & Cassettes
            [
                'name' => 'Shimano Ultegra R8000 11-Speed Chain',
                'subcategory' => 'Chains & Cassettes',
                'price' => 45.99,
                'description' => 'High-performance 11-speed chain with Sil-Tec treatment.',
                'stock' => 50,
                'is_active' => 1,
            ],
            [
                'name' => 'SRAM XG-1275 Eagle 12-Speed Cassette',
                'subcategory' => 'Chains & Cassettes',
                'price' => 375.00,
                'description' => '12-speed mountain bike cassette with 10-52 tooth range.',
                'stock' => 25,
                'is_active' => 1,
            ],

            // Clothing & Apparel - Jerseys & Tops
            [
                'name' => 'Rapha Pro Team Training Jersey',
                'subcategory' => 'Jerseys & Tops',
                'price' => 125.00,
                'description' => 'Breathable cycling jersey with three rear pockets.',
                'stock' => 40,
                'is_active' => 1,
            ],
            [
                'name' => 'Pearl Izumi Attack Short Sleeve Jersey',
                'subcategory' => 'Jerseys & Tops',
                'price' => 79.99,
                'description' => 'Performance cycling jersey with UPF 50+ sun protection.',
                'stock' => 35,
                'is_active' => 1,
            ],

            // Clothing & Apparel - Shorts & Bibs
            [
                'name' => 'Castelli Rosso Corsa Bib Shorts',
                'subcategory' => 'Shorts & Bibs',
                'price' => 220.00,
                'description' => 'High-end bib shorts with Progetto X2 Air seamless seat pad.',
                'stock' => 30,
                'is_active' => 1,
            ],

            // Tires & Tubes - Road Tires
            [
                'name' => 'Continental Grand Prix 5000 Road Tire',
                'subcategory' => 'Road Tires',
                'price' => 69.99,
                'description' => 'Premium road tire with excellent puncture protection and low rolling resistance.',
                'stock' => 60,
                'is_active' => 1,
            ],
            [
                'name' => 'Vittoria Corsa Control G2.0 Tire',
                'subcategory' => 'Road Tires',
                'price' => 74.99,
                'description' => 'Training and endurance road tire with graphene compound.',
                'stock' => 45,
                'is_active' => 1,
            ],

            // Tires & Tubes - Mountain Bike Tires
            [
                'name' => 'Maxxis Minion DHF Mountain Bike Tire',
                'subcategory' => 'Mountain Bike Tires',
                'price' => 89.99,
                'description' => 'Aggressive trail and enduro tire with excellent grip.',
                'stock' => 55,
                'is_active' => 1,
            ],
            [
                'name' => 'Schwalbe Magic Mary Super Trail Tire',
                'subcategory' => 'Mountain Bike Tires',
                'price' => 79.99,
                'description' => 'All-round mountain bike tire for varied trail conditions.',
                'stock' => 40,
                'is_active' => 1,
            ],

            // Tools & Maintenance - Multi-tools
            [
                'name' => 'Crank Brothers M19 Multi-Tool',
                'subcategory' => 'Multi-tools',
                'price' => 34.99,
                'description' => '19-function bike multi-tool with chain tool and torx bits.',
                'stock' => 75,
                'is_active' => 1,
            ],
            [
                'name' => 'Topeak Alien II 26-Function Tool',
                'subcategory' => 'Multi-tools',
                'price' => 49.99,
                'description' => 'Comprehensive multi-tool for trailside repairs.',
                'stock' => 60,
                'is_active' => 1,
            ],

            // Safety & Protection - Helmets
            [
                'name' => 'Giro Aether MIPS Road Helmet',
                'subcategory' => 'Helmets',
                'price' => 299.99,
                'description' => 'Premium road helmet with MIPS protection and excellent ventilation.',
                'stock' => 25,
                'is_active' => 1,
            ],
            [
                'name' => 'Bell Super Air R MIPS Mountain Bike Helmet',
                'subcategory' => 'Helmets',
                'price' => 199.99,
                'description' => 'Trail helmet with removable chin bar for enduro riding.',
                'stock' => 20,
                'is_active' => 1,
            ],

            // Safety & Protection - Lights
            [
                'name' => 'Cygolite Metro Pro 1100 Lumens Bike Light',
                'subcategory' => 'Reflective Gear & Lights',
                'price' => 89.99,
                'description' => 'High-power USB rechargeable bike light with multiple modes.',
                'stock' => 40,
                'is_active' => 1,
            ],

            // Bags & Storage - Backpacks & Hydration Packs
            [
                'name' => 'Osprey Raptor 14 Mountain Bike Pack',
                'subcategory' => 'Backpacks & Hydration Packs',
                'price' => 140.00,
                'description' => 'Hydration pack with 3-liter reservoir and tool organization.',
                'stock' => 30,
                'is_active' => 1,
            ],

            // Electronics & Accessories - Bike Computers
            [
                'name' => 'Garmin Edge 1030 Plus Bike Computer',
                'subcategory' => 'Bike Computers & GPS',
                'price' => 599.99,
                'description' => 'Premium bike computer with mapping, performance metrics, and connectivity.',
                'stock' => 15,
                'is_active' => 1,
            ],
            [
                'name' => 'Wahoo ELEMNT ROAM GPS Bike Computer',
                'subcategory' => 'Bike Computers & GPS',
                'price' => 379.99,
                'description' => 'Easy-to-use GPS bike computer with color display and navigation.',
                'stock' => 20,
                'is_active' => 1,
            ],

            // Nutrition & Hydration - Bottles & Cages
            [
                'name' => 'CamelBak Podium Chill Insulated Bottle',
                'subcategory' => 'Bottles & Cages',
                'price' => 14.99,
                'description' => 'Insulated water bottle that keeps drinks cold for hours.',
                'stock' => 100,
                'is_active' => 1,
            ],
            [
                'name' => 'Elite Custom Race Plus Bottle Cage',
                'subcategory' => 'Bottles & Cages',
                'price' => 24.99,
                'description' => 'Lightweight carbon-composite bottle cage with secure grip.',
                'stock' => 80,
                'is_active' => 1,
            ],

            // Parts & Upgrades - Brake Systems
            [
                'name' => 'Shimano XTR M9100 Disc Brake Set',
                'subcategory' => 'Brake Systems & Rotors',
                'price' => 450.00,
                'description' => 'Top-tier mountain bike disc brakes with excellent modulation.',
                'stock' => 18,
                'is_active' => 1,
            ],

            // Security - Locks & Chains
            [
                'name' => 'Kryptonite New York Fahgettaboudit Lock',
                'subcategory' => 'Locks & Chains',
                'price' => 119.99,
                'description' => 'Heavy-duty bike lock with anti-theft protection.',
                'stock' => 35,
                'is_active' => 1,
            ],

            // Indoor Training - Smart Trainers
            [
                'name' => 'Wahoo KICKR Smart Trainer',
                'subcategory' => 'Smart Trainers',
                'price' => 1299.99,
                'description' => 'Direct drive smart trainer with accurate power measurement.',
                'stock' => 12,
                'is_active' => 1,
            ],

            // Kids & Family - Kids Bikes
            [
                'name' => 'Guardian Ethos Kids Bike 20"',
                'subcategory' => 'Kids Bikes',
                'price' => 349.99,
                'description' => 'Kids bike with SureStop technology for safer braking.',
                'stock' => 22,
                'is_active' => 1,
            ],

            // Gifts & Novelties - Gift Cards
            [
                'name' => '$100 Cycling Store Gift Card',
                'subcategory' => 'Gift Cards',
                'price' => 100.00,
                'description' => 'Redeemable for any products in our store.',
                'stock' => 999,
                'is_active' => 1,
            ],

            // Accessories - Bottle Cages & Holders
            [
                'name' => 'Arundel Mandible Carbon Bottle Cage',
                'subcategory' => 'Bottle Cages & Holders',
                'price' => 49.99,
                'description' => 'Ultra-light carbon fiber bottle cage with positive retention.',
                'stock' => 45,
                'is_active' => 1,
            ],

            // Peripherals - Handlebar Grips & Tape
            [
                'name' => 'Supacaz Super Sticky Kush Bar Tape',
                'subcategory' => 'Handlebar Grips & Tape',
                'price' => 44.99,
                'description' => 'Premium bar tape with excellent grip and comfort.',
                'stock' => 60,
                'is_active' => 1,
            ],
            [
                'name' => 'ESI Chunky MTB Grips',
                'subcategory' => 'Handlebar Grips & Tape',
                'price' => 29.99,
                'description' => 'Comfortable silicone grips for mountain biking.',
                'stock' => 50,
                'is_active' => 1,
            ],

            // Additional products to cover more categories
            [
                'name' => 'Park Tool Home Mechanic Repair Stand',
                'subcategory' => 'Bike Stands & Workbenches',
                'price' => 249.99,
                'description' => 'Professional-grade bike repair stand for home mechanics.',
                'stock' => 20,
                'is_active' => 1,
            ],
            [
                'name' => 'Finish Line Dry Teflon Bicycle Lube',
                'subcategory' => 'Lubricants & Oils',
                'price' => 11.99,
                'description' => 'Dry condition bicycle chain lubricant.',
                'stock' => 85,
                'is_active' => 1,
            ],
            [
                'name' => 'Giro Merit MIPS Women\'s Road Helmet',
                'subcategory' => 'Road Helmets',
                'price' => 89.99,
                'description' => 'Women\'s specific road helmet with MIPS technology.',
                'stock' => 30,
                'is_active' => 1,
            ],
        ];

        foreach ($products as $data) {
            $subcategory = Subcategory::where('name', $data['subcategory'])->first();

            if ($subcategory) {
                Product::updateOrCreate(
                    ['slug' => \Str::slug($data['name'])],
                    [
                        'name' => $data['name'],
                        'slug' => \Str::slug($data['name']),
                        'subcategory_id' => $subcategory->id,
                        'category_id' => $subcategory->category_id,
                        'price' => $data['price'],
                        'description' => $data['description'],
                        'stock' => $data['stock'],
                        'is_active' => $data['is_active'],
                    ]
                );
            } else {
                $this->command->info("Subcategory not found: {$data['subcategory']}");
            }
        }

        $this->command->info('Products seeded successfully!');
    }
}