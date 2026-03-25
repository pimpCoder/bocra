<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LicenseApplication;
use App\Models\User;
 
class LicenseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('license_applications')->truncate();
 
        $biz1  = User::where('email', 'rep@mascom.bw')->firstOrFail();
        $biz2  = User::where('email', 'rep@orange.bw')->firstOrFail();
        $biz3  = User::where('email', 'director@technet.bw')->firstOrFail();
        $staff = User::where('email', 'staff1@bocra.bw')->firstOrFail();
        $admin = User::where('email', 'admin@bocra.bw')->firstOrFail();
 
        $applications = [
            [
                'user_id'          => $biz1->id,
                'license_type'     => 'Mobile Network Operator',
                'business_name'    => 'Mascom Wireless Botswana',
                'documents'        => json_encode([]),
                'status'           => 'approved',
                'submitted_at'     => now()->subMonths(6),
                'reviewed_at'      => now()->subMonths(5),
                'reviewed_by'      => $admin->id,
                'rejection_reason' => null,
                'validity_start'   => now()->subMonths(5)->toDateString(),
                'validity_end'     => now()->addMonths(7)->toDateString(),
                'created_at'       => now()->subMonths(6),
                'updated_at'       => now()->subMonths(5),
            ],
            [
                'user_id'          => $biz2->id,
                'license_type'     => 'Mobile Network Operator',
                'business_name'    => 'Orange Botswana (Pty) Ltd',
                'documents'        => json_encode([]),
                'status'           => 'approved',
                'submitted_at'     => now()->subMonths(4),
                'reviewed_at'      => now()->subMonths(3),
                'reviewed_by'      => $staff->id,
                'rejection_reason' => null,
                'validity_start'   => now()->subMonths(3)->toDateString(),
                'validity_end'     => now()->addMonths(9)->toDateString(),
                'created_at'       => now()->subMonths(4),
                'updated_at'       => now()->subMonths(3),
            ],
            [
                'user_id'          => $biz3->id,
                'license_type'     => 'Internet Service Provider',
                'business_name'    => 'TechNet Botswana (Pty) Ltd',
                'documents'        => json_encode([]),
                'status'           => 'under_review',
                'submitted_at'     => now()->subDays(10),
                'reviewed_at'      => null,
                'reviewed_by'      => null,
                'rejection_reason' => null,
                'validity_start'   => null,
                'validity_end'     => null,
                'created_at'       => now()->subDays(10),
                'updated_at'       => now()->subDays(10),
            ],
            [
                'user_id'          => $biz3->id,
                'license_type'     => 'Value Added Service Provider',
                'business_name'    => 'TechNet Botswana (Pty) Ltd',
                'documents'        => json_encode([]),
                'status'           => 'submitted',
                'submitted_at'     => now()->subDays(3),
                'reviewed_at'      => null,
                'reviewed_by'      => null,
                'rejection_reason' => null,
                'validity_start'   => null,
                'validity_end'     => null,
                'created_at'       => now()->subDays(3),
                'updated_at'       => now()->subDays(3),
            ],
        ];
 
        DB::table('license_applications')->insert($applications);
 
        echo "  ✅  License applications seeded (" . count($applications) . " records)\n";
    }
}
