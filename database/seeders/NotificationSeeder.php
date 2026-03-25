<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
 
class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifications')->truncate();
 
        $citizen1 = User::where('email', 'john@gmail.com')->firstOrFail();
        $citizen2 = User::where('email', 'mary@gmail.com')->firstOrFail();
        $biz1     = User::where('email', 'rep@mascom.bw')->firstOrFail();
        $staff    = User::where('email', 'staff1@bocra.bw')->firstOrFail();
 
        $notifications = [
            // Citizen 1 — complaint journey
            [
                'user_id'    => $citizen1->id,
                'message'    => 'Your complaint #1 (Poor Network Coverage) has been received and is pending review.',
                'type'       => 'complaint',
                'is_read'    => true,
                'created_at' => now()->subDays(7),
            ],
            [
                'user_id'    => $citizen1->id,
                'message'    => 'Your complaint #1 status has been updated to: under_review.',
                'type'       => 'complaint',
                'is_read'    => false,
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id'    => $citizen1->id,
                'message'    => 'New regulations published: Telecommunications Licensing Guidelines 2025.',
                'type'       => 'alert',
                'is_read'    => false,
                'created_at' => now()->subDays(1),
            ],
            // Citizen 2
            [
                'user_id'    => $citizen2->id,
                'message'    => 'Your complaint #2 (Billing Dispute) has been received and is pending review.',
                'type'       => 'complaint',
                'is_read'    => false,
                'created_at' => now()->subDays(4),
            ],
            // Business 1
            [
                'user_id'    => $biz1->id,
                'message'    => 'Your license application #1 for Mascom Wireless Botswana has been approved.',
                'type'       => 'license',
                'is_read'    => true,
                'created_at' => now()->subMonths(5),
            ],
            [
                'user_id'    => $biz1->id,
                'message'    => 'Cybersecurity Advisory: SIM Swap Fraud Alert — please review the latest advisory.',
                'type'       => 'alert',
                'is_read'    => false,
                'created_at' => now()->subDays(7),
            ],
            // Staff
            [
                'user_id'    => $staff->id,
                'message'    => 'New complaint #1 assigned to you for investigation.',
                'type'       => 'complaint',
                'is_read'    => true,
                'created_at' => now()->subDays(5),
            ],
        ];
 
        DB::table('notifications')->insert($notifications);
 
        echo "  ✅  Notifications seeded (" . count($notifications) . " records)\n";
    }
}
