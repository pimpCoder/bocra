<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
 
class DomainSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('domain_registrations')->truncate();
 
        $biz1    = User::where('email', 'rep@mascom.bw')->firstOrFail();
        $biz2    = User::where('email', 'rep@orange.bw')->firstOrFail();
        $biz3    = User::where('email', 'director@technet.bw')->firstOrFail();
        $citizen = User::where('email', 'john@gmail.com')->firstOrFail();
        $admin   = User::where('email', 'admin@bocra.bw')->firstOrFail();
 
        $domains = [
            [
                'user_id'           => $biz1->id,
                'domain_name'       => 'mascom',
                'domain_type'       => '.co.bw',
                'status'            => 'active',
                'registration_date' => now()->subYear()->toDateString(),
                'expiry_date'       => now()->addYear()->toDateString(),
                'reviewed_by'       => $admin->id,
                'reviewed_at'       => now()->subYear(),
                'rejection_reason'  => null,
                'created_at'        => now()->subYear(),
                'updated_at'        => now()->subYear(),
            ],
            [
                'user_id'           => $biz2->id,
                'domain_name'       => 'orange',
                'domain_type'       => '.co.bw',
                'status'            => 'active',
                'registration_date' => now()->subMonths(8)->toDateString(),
                'expiry_date'       => now()->addMonths(4)->toDateString(),
                'reviewed_by'       => $admin->id,
                'reviewed_at'       => now()->subMonths(8),
                'rejection_reason'  => null,
                'created_at'        => now()->subMonths(8),
                'updated_at'        => now()->subMonths(8),
            ],
            [
                'user_id'           => $biz3->id,
                'domain_name'       => 'technet',
                'domain_type'       => '.co.bw',
                'status'            => 'pending',
                'registration_date' => null,
                'expiry_date'       => null,
                'reviewed_by'       => null,
                'reviewed_at'       => null,
                'rejection_reason'  => null,
                'created_at'        => now()->subDays(3),
                'updated_at'        => now()->subDays(3),
            ],
            [
                'user_id'           => $citizen->id,
                'domain_name'       => 'johncitizen',
                'domain_type'       => '.org.bw',
                'status'            => 'pending',
                'registration_date' => null,
                'expiry_date'       => null,
                'reviewed_by'       => null,
                'reviewed_at'       => null,
                'rejection_reason'  => null,
                'created_at'        => now()->subDays(1),
                'updated_at'        => now()->subDays(1),
            ],
            [
                'user_id'           => $biz3->id,
                'domain_name'       => 'technetservices',
                'domain_type'       => '.net.bw',
                'status'            => 'rejected',
                'registration_date' => null,
                'expiry_date'       => null,
                'reviewed_by'       => $admin->id,
                'reviewed_at'       => now()->subDays(5),
                'rejection_reason'  => 'Domain name too similar to existing registered domain.',
                'created_at'        => now()->subDays(7),
                'updated_at'        => now()->subDays(5),
            ],
        ];
 
        DB::table('domain_registrations')->insert($domains);
 
        echo "  ✅  Domain registrations seeded (" . count($domains) . " records)\n";
    }
}