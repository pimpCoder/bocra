<?php

// ═══════════════════════════════════════════════════════════════
// FILE: database/seeders/ComplaintSeeder.php
// ═══════════════════════════════════════════════════════════════

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Models\User;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        // FK checks disabled by DatabaseSeeder — safe to truncate in dependency order
        DB::table('complaint_status_histories')->truncate();
        DB::table('complaints')->truncate();

        $citizen1 = User::where('email', 'john@gmail.com')->firstOrFail();
        $citizen2 = User::where('email', 'mary@gmail.com')->firstOrFail();
        $staff    = User::where('email', 'staff1@bocra.bw')->firstOrFail();

        $complaints = [
            [
                'data' => [
                    'user_id'      => $citizen1->id,
                    'name'         => $citizen1->name,
                    'email'        => $citizen1->email,
                    'phone_number' => $citizen1->phone_number,
                    'title'        => 'Poor Network Coverage in Gaborone CBD',
                    'description'  => 'My Mascom mobile network has been experiencing constant drops in the CBD area. Calls drop mid-conversation and data is unusable.',
                    'category'     => 'network',
                    'status'       => 'under_review',
                    'priority'     => 'high',
                    'assigned_to'  => $staff->id,
                    'created_at'   => now()->subDays(7),
                    'updated_at'   => now()->subDays(2),
                ],
                'history' => [
                    ['status' => 'pending',      'comments' => 'Complaint submitted.',                                'days_ago' => 7],
                    ['status' => 'under_review', 'comments' => 'Complaint reviewed and assigned for investigation.', 'days_ago' => 2],
                ],
            ],
            [
                'data' => [
                    'user_id'      => $citizen2->id,
                    'name'         => $citizen2->name,
                    'email'        => $citizen2->email,
                    'phone_number' => $citizen2->phone_number,
                    'title'        => 'Billing Dispute — Double Charged',
                    'description'  => 'Charged twice for monthly Orange data bundle in October. P85 deducted twice on the 1st and 3rd.',
                    'category'     => 'billing',
                    'status'       => 'pending',
                    'priority'     => 'medium',
                    'assigned_to'  => null,
                    'created_at'   => now()->subDays(4),
                    'updated_at'   => now()->subDays(4),
                ],
                'history' => [
                    ['status' => 'pending', 'comments' => 'Complaint submitted.', 'days_ago' => 4],
                ],
            ],
            [
                'data' => [
                    'user_id'      => null,
                    'name'         => 'Anonymous Complainant',
                    'email'        => 'anon@protonmail.com',
                    'phone_number' => '+26774000999',
                    'title'        => 'Suspected Internet Fraud via SMS',
                    'description'  => 'Receiving SMS messages from unknown numbers claiming to offer free airtime in exchange for banking details.',
                    'category'     => 'fraud',
                    'status'       => 'resolved',
                    'priority'     => 'high',
                    'assigned_to'  => $staff->id,
                    'created_at'   => now()->subDays(14),
                    'updated_at'   => now()->subDays(1),
                ],
                'history' => [
                    ['status' => 'pending',      'comments' => 'Complaint submitted.',                            'days_ago' => 14],
                    ['status' => 'under_review', 'comments' => 'Assigned for fraud investigation.',               'days_ago' => 10],
                    ['status' => 'resolved',     'comments' => 'Confirmed fraud. Operator notified and blocked.', 'days_ago' => 1],
                ],
            ],
            [
                'data' => [
                    'user_id'      => $citizen1->id,
                    'name'         => $citizen1->name,
                    'email'        => $citizen1->email,
                    'phone_number' => $citizen1->phone_number,
                    'title'        => 'Internet Service Outage — 48 Hours',
                    'description'  => 'ADSL connection through BoFiNet has been down for 48 hours. No response from support line.',
                    'category'     => 'service_outage',
                    'status'       => 'pending',
                    'priority'     => 'medium',
                    'assigned_to'  => null,
                    'created_at'   => now()->subDays(2),
                    'updated_at'   => now()->subDays(2),
                ],
                'history' => [
                    ['status' => 'pending', 'comments' => 'Complaint submitted.', 'days_ago' => 2],
                ],
            ],
            [
                'data' => [
                    'user_id'      => $citizen2->id,
                    'name'         => $citizen2->name,
                    'email'        => $citizen2->email,
                    'phone_number' => $citizen2->phone_number,
                    'title'        => 'Misleading Promotional Offer',
                    'description'  => 'Mascom advertised unlimited data for P99 but throttled connection after 2GB. This is misleading advertising.',
                    'category'     => 'billing',
                    'status'       => 'rejected',
                    'priority'     => 'low',
                    'assigned_to'  => $staff->id,
                    'created_at'   => now()->subDays(10),
                    'updated_at'   => now()->subDays(5),
                ],
                'history' => [
                    ['status' => 'pending',  'comments' => 'Complaint submitted.',                                      'days_ago' => 10],
                    ['status' => 'rejected', 'comments' => 'Promotional terms were clearly disclosed in the fine print.', 'days_ago' => 5],
                ],
            ],
        ];

        foreach ($complaints as $entry) {
            $complaint = Complaint::create($entry['data']);

            foreach ($entry['history'] as $h) {
                ComplaintStatusHistory::create([
                    'complaint_id' => $complaint->id,
                    'status'       => $h['status'],
                    'updated_by'   => $h['status'] === 'pending' ? $complaint->user_id : $staff->id,
                    'comments'     => $h['comments'],
                    'created_at'   => now()->subDays($h['days_ago']),
                ]);
            }
        }

        echo "  ✅  Complaints seeded (" . count($complaints) . " complaints with history)\n";
    }
}