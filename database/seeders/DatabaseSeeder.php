<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE notifications RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE complaint_status_histories RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE complaints RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE domain_registrations RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE license_applications RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE contents RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE personal_access_tokens RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE users RESTART IDENTITY CASCADE');

        \->call([
            UserSeeder::class,
            ComplaintSeeder::class,
            LicenseSeeder::class,
            DomainSeeder::class,
            ContentSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
