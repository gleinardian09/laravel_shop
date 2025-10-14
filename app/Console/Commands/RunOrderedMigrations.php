<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class RunOrderedMigrations extends Command
{
    protected $signature = 'migrate:ordered';
    protected $description = 'Run migrations in proper order to avoid foreign key errors';

    // Define the migration order - parents first, then children
    protected $migrationOrder = [
        // Core Laravel tables
        '0001_01_01_000000_create_users_table.php',
        '0001_01_01_000001_create_cache_table.php', 
        '0001_01_01_000002_create_jobs_table.php',
        '2025_10_12_150304_add_is_admin_to_users_table.php',
        '2025_10_12_150652_create_password_reset_tokens_table.php',
        '2025_10_12_150659_create_personal_access_tokens_table.php',
        
        // Parent tables
        '2025_10_12_145706_create_categories_table.php',
        '2025_10_12_145713_create_products_table.php',
        
        // Child tables that depend on parents
        '2025_10_12_152746_create_cart_items_table.php',
        '2025_10_12_152747_create_orders_table.php',
        '2025_10_12_152747_create_order_items_table.php',
        '2025_10_13_015806_create_reviews_table.php',
        '2025_10_13_015808_create_discounts_table.php',
        '2025_10_14_020405_create_product_images_table.php',
        
        // Telescope (optional)
        '2025_10_13_134407_create_telescope_entries_table.php',
        
        // Index/constraint migrations (should be last)
        '2025_10_14_053231_add_unique_index_to_cart_items.php',
    ];

    public function handle()
    {
        $this->info('Running migrations in proper order...');
        
        // First, run the core Laravel migrations
        $this->info('Running core Laravel migrations...');
        Artisan::call('migrate', [
            '--path' => 'database/migrations/0001_01_01_000000_create_users_table.php'
        ]);
        Artisan::call('migrate', [
            '--path' => 'database/migrations/0001_01_01_000001_create_cache_table.php'
        ]);
        Artisan::call('migrate', [
            '--path' => 'database/migrations/0001_01_01_000002_create_jobs_table.php'
        ]);

        // Run migrations in defined order
        foreach ($this->migrationOrder as $migration) {
            $this->info("Running migration: {$migration}");
            
            try {
                Artisan::call('migrate', [
                    '--path' => "database/migrations/{$migration}"
                ]);
                
                $this->info("✓ {$migration} completed successfully");
            } catch (\Exception $e) {
                $this->error("✗ {$migration} failed: " . $e->getMessage());
                
                // Ask if we should continue
                if (!$this->confirm('Continue with next migration?')) {
                    return;
                }
            }
        }

        $this->info('All migrations completed!');
    }
}