<?php
 
// ═══════════════════════════════════════════════════════════════
// FILE: database/seeders/DatabaseSeeder.php
// Run with: php artisan db:seed
// ═══════════════════════════════════════════════════════════════
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks for the entire seed operation
        // This lets us truncate tables that reference each other
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
 
        $this->call([
            UserSeeder::class,
            ComplaintSeeder::class,
            LicenseSeeder::class,
            DomainSeeder::class,
            ContentSeeder::class,
            NotificationSeeder::class,
        ]);
 
        // Re-enable FK checks after all seeders finish
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}