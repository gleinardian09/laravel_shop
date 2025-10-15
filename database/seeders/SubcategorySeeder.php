<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategory;
use App\Models\Category;

class SubcategorySeeder extends Seeder
{
    public function run()
    {
        $subcategories = [
            // Bicycles
            'Road Bikes' => 'Bicycles',
            'Mountain Bikes' => 'Bicycles',
            'Hybrid / Commuter Bikes' => 'Bicycles',
            'Gravel Bikes' => 'Bicycles',
            'Electric Bikes (E-Bikes)' => 'Bicycles',
            'Folding Bikes' => 'Bicycles',
            'BMX Bikes' => 'Bicycles',
            'Track / Fixed Gear Bikes' => 'Bicycles',

            // Components
            'Frames' => 'Components',
            'Forks & Suspension' => 'Components',
            'Wheels & Rims' => 'Components',
            'Hubs & Bearings' => 'Components',
            'Handlebars & Stems' => 'Components',
            'Seatposts & Saddles' => 'Components',
            'Chains & Cassettes' => 'Components',
            'Pedals' => 'Components',
            'Brakes & Shifters' => 'Components',

            // Clothing & Apparel
            'Jerseys & Tops' => 'Clothing & Apparel',
            'Shorts & Bibs' => 'Clothing & Apparel',
            'Jackets & Vests' => 'Clothing & Apparel',
            'Gloves' => 'Clothing & Apparel',
            'Socks & Footwear' => 'Clothing & Apparel',
            'Base Layers & Thermal Wear' => 'Clothing & Apparel',
            'Rain & Wind Gear' => 'Clothing & Apparel',

            // Tires & Tubes
            'Road Tires' => 'Tires & Tubes',
            'Mountain Bike Tires' => 'Tires & Tubes',
            'Hybrid Tires' => 'Tires & Tubes',
            'Tubeless Tires' => 'Tires & Tubes',
            'Inner Tubes' => 'Tires & Tubes',
            'Tire Accessories (Sealant, Patches, Pumps)' => 'Tires & Tubes',

            // Tools & Maintenance
            'Repair Tools (Wrenches, Screwdrivers)' => 'Tools & Maintenance',
            'Multi-tools' => 'Tools & Maintenance',
            'Cleaning Supplies' => 'Tools & Maintenance',
            'Lubricants & Oils' => 'Tools & Maintenance',
            'Bike Stands & Workbenches' => 'Tools & Maintenance',
            'Maintenance Kits' => 'Tools & Maintenance',

            // Safety & Protection
            'Helmets' => 'Safety & Protection',
            'Gloves' => 'Safety & Protection',
            'Pads & Guards' => 'Safety & Protection',
            'Reflective Gear & Lights' => 'Safety & Protection',
            'Mirrors & Bells' => 'Safety & Protection',

            // Bags & Storage
            'Backpacks & Hydration Packs' => 'Bags & Storage',
            'Saddle Bags' => 'Bags & Storage',
            'Handlebar Bags' => 'Bags & Storage',
            'Panniers & Racks' => 'Bags & Storage',
            'Storage Solutions (Hooks, Stands)' => 'Bags & Storage',

            // Electronics & Accessories
            'Bike Computers & GPS' => 'Electronics & Accessories',
            'Lights & Batteries' => 'Electronics & Accessories',
            'Sensors & Trackers' => 'Electronics & Accessories',
            'Phone Mounts' => 'Electronics & Accessories',
            'Power Meters' => 'Electronics & Accessories',

            // Nutrition & Hydration
            'Energy Gels & Bars' => 'Nutrition & Hydration',
            'Electrolytes & Drinks' => 'Nutrition & Hydration',
            'Bottles & Cages' => 'Nutrition & Hydration',
            'Supplements & Recovery Products' => 'Nutrition & Hydration',

            // Parts & Upgrades
            'Gear Systems & Derailleurs' => 'Parts & Upgrades',
            'Brake Systems & Rotors' => 'Parts & Upgrades',
            'Suspension Upgrades' => 'Parts & Upgrades',
            'Wheels & Tires' => 'Parts & Upgrades',
            'Saddles & Handlebars' => 'Parts & Upgrades',

            // Security
            'Locks & Chains' => 'Security',
            'Alarms & Tracking Devices' => 'Security',
            'Security Accessories' => 'Security',

            // Indoor Training
            'Trainers & Rollers' => 'Indoor Training',
            'Smart Trainers' => 'Indoor Training',
            'Accessories (Mat, Fan)' => 'Indoor Training',
            'Virtual Training Subscriptions' => 'Indoor Training',

            // Kids & Family
            'Kids Bikes' => 'Kids & Family',
            'Balance Bikes' => 'Kids & Family',
            'Child Seats & Trailers' => 'Kids & Family',
            'Family Safety Gear' => 'Kids & Family',
            'Kids Helmets & Apparel' => 'Kids & Family',

            // Gifts & Novelties
            'Gift Cards' => 'Gifts & Novelties',
            'Memorabilia & Collectibles' => 'Gifts & Novelties',
            'Bike-themed Accessories' => 'Gifts & Novelties',
            'Lifestyle Items (Water Bottles, Caps, etc.)' => 'Gifts & Novelties',

            // Helmets
            'Road Helmets' => 'Helmets',
            'Mountain Helmets' => 'Helmets',
            'BMX / Skate Style Helmets' => 'Helmets',
            'Kids Helmets' => 'Helmets',
            'Aero / Time Trial Helmets' => 'Helmets',

            // Accessories
            'Bottle Cages & Holders' => 'Accessories',
            'Bells & Horns' => 'Accessories',
            'Mirrors & Fenders' => 'Accessories',
            'Pumps & Tools' => 'Accessories',
            'Kickstands' => 'Accessories',

            // Peripherals
            'Handlebar Grips & Tape' => 'Peripherals',
            'Saddles & Seatposts' => 'Peripherals',
            'Pedals & Cleats' => 'Peripherals',
            'Chains & Cables' => 'Peripherals',
            'Wheel Accessories' => 'Peripherals',
        ];

        foreach ($subcategories as $name => $categoryName) {
            $category = Category::where('name', $categoryName)->first();
            if ($category) {
                Subcategory::updateOrCreate(
                    ['slug' => \Str::slug($name)],
                    [
                        'name' => $name,
                        'category_id' => $category->id,
                        'description' => 'Subcategory for ' . $name,
                        'is_active' => 1,
                    ]
                );
            }
        }
    }
}
