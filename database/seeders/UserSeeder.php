<?php

// ═══════════════════════════════════════════════════════════════
// FILE: database/seeders/UserSeeder.php
// ═══════════════════════════════════════════════════════════════

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // FK checks already disabled by DatabaseSeeder — safe to truncate
        DB::table('users')->truncate();

        $users = [
            // ── Admin ────────────────────────────────────────────
            [
                'name'              => 'System Admin',
                'email'             => 'admin@bocra.bw',
                'password'          => Hash::make('Admin@1234'),
                'role'              => 'admin',
                'phone_number'      => '+26771000001',
                'national_id'       => 'ADMIN001',
                'organization_name' => 'BOCRA',
                'is_active'         => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // ── Staff ─────────────────────────────────────────────
            [
                'name'              => 'Staff Officer One',
                'email'             => 'staff1@bocra.bw',
                'password'          => Hash::make('Staff@1234'),
                'role'              => 'staff',
                'phone_number'      => '+26771000002',
                'national_id'       => 'STAFF001',
                'organization_name' => 'BOCRA Operations',
                'is_active'         => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Staff Officer Two',
                'email'             => 'staff2@bocra.bw',
                'password'          => Hash::make('Staff@1234'),
                'role'              => 'staff',
                'phone_number'      => '+26771000003',
                'national_id'       => 'STAFF002',
                'organization_name' => 'BOCRA Licensing',
                'is_active'         => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // ── Business ──────────────────────────────────────────
            [
                'name'              => 'Mascom Representative',
                'email'             => 'rep@mascom.bw',
                'password'          => Hash::make('Business@1234'),
                'role'              => 'business',
                'phone_number'      => '+26771000004',
                'national_id'       => 'BIZ001',
                'organization_name' => 'Mascom Wireless Botswana',
                'is_active'         => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Orange BW Rep',
                'email'             => 'rep@orange.bw',
                'password'          => Hash::make('Business@1234'),
                'role'              => 'business',
                'phone_number'      => '+26771000005',
                'national_id'       => 'BIZ002',
                'organization_name' => 'Orange Botswana (Pty) Ltd',
                'is_active'         => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'TechNet Director',
                'email'             => 'director@technet.bw',
                'password'          => Hash::make('Business@1234'),
                'role'              => 'business',
                'phone_number'      => '+26771000006',
                'national_id'       => 'BIZ003',
                'organization_name' => 'TechNet Botswana (Pty) Ltd',
                'is_active'         => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // ── Citizens ──────────────────────────────────────────
            [
                'name'              => 'John Citizen',
                'email'             => 'john@gmail.com',
                'password'          => Hash::make('Citizen@1234'),
                'role'              => 'citizen',
                'phone_number'      => '+26773001001',
                'national_id'       => 'CIT001',
                'organization_name' => null,
                'is_active'         => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Mary Molebatsi',
                'email'             => 'mary@gmail.com',
                'password'          => Hash::make('Citizen@1234'),
                'role'              => 'citizen',
                'phone_number'      => '+26773001002',
                'national_id'       => 'CIT002',
                'organization_name' => null,
                'is_active'         => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Kabo Tshekedi',
                'email'             => 'kabo@gmail.com',
                'password'          => Hash::make('Citizen@1234'),
                'role'              => 'citizen',
                'phone_number'      => '+26773001003',
                'national_id'       => 'CIT003',
                'organization_name' => null,
                'is_active'         => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // ── Inactive (for testing deactivation flow) ──────────
            [
                'name'              => 'Inactive User',
                'email'             => 'inactive@test.bw',
                'password'          => Hash::make('Test@1234'),
                'role'              => 'citizen',
                'phone_number'      => null,
                'national_id'       => null,
                'organization_name' => null,
                'is_active'         => false,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        DB::table('users')->insert($users);

        echo "  ✅  Users seeded (" . count($users) . " users)\n";
    }
}