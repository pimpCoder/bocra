<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
 
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contents')->truncate();
 
        $admin = User::where('email', 'admin@bocra.bw')->firstOrFail();
        $staff = User::where('email', 'staff1@bocra.bw')->firstOrFail();
 
        $contents = [
            [
                'title'        => 'Telecommunications Licensing Guidelines 2025',
                'body'         => 'All telecommunications operators are required to hold a valid license issued by BOCRA. License renewals must be submitted at least 90 days before expiry. Failure to renew may result in suspension of operating rights.',
                'category'     => 'regulations',
                'tags'         => json_encode(['telecoms', 'licensing', '2025', 'renewal']),
                'status'       => 'published',
                'published_at' => now()->subDays(30),
                'created_by'   => $admin->id,
                'created_at'   => now()->subDays(31),
                'updated_at'   => now()->subDays(30),
            ],
            [
                'title'        => 'Consumer Protection Framework for Internet Services',
                'body'         => 'BOCRA has established a consumer protection framework requiring all ISPs to clearly disclose data speeds, fair use policies, and billing practices. Providers must resolve complaints within 14 working days.',
                'category'     => 'regulations',
                'tags'         => json_encode(['internet', 'consumer', 'protection', 'ISP']),
                'status'       => 'published',
                'published_at' => now()->subDays(14),
                'created_by'   => $staff->id,
                'created_at'   => now()->subDays(15),
                'updated_at'   => now()->subDays(14),
            ],
            [
                'title'        => 'Cybersecurity Advisory: SIM Swap Fraud Alert',
                'body'         => 'BOCRA warns the public about an increase in SIM swap fraud targeting mobile banking users. Criminals use personal information to fraudulently transfer mobile numbers to new SIM cards, gaining access to banking OTPs.',
                'category'     => 'cybersecurity',
                'tags'         => json_encode(['fraud', 'SIM swap', 'mobile banking', 'alert']),
                'status'       => 'published',
                'published_at' => now()->subDays(7),
                'created_by'   => $admin->id,
                'created_at'   => now()->subDays(8),
                'updated_at'   => now()->subDays(7),
            ],
            [
                'title'        => 'Public Notice: .bw Domain Registration Policy Review',
                'body'         => 'BOCRA invites public comments on proposed updates to the .bw domain registration policy. The review aims to streamline registration and enhance security of the .bw namespace. Submissions close 31 March 2025.',
                'category'     => 'notices',
                'tags'         => json_encode(['domain', '.bw', 'policy', 'public comment']),
                'status'       => 'published',
                'published_at' => now()->subDays(3),
                'created_by'   => $admin->id,
                'created_at'   => now()->subDays(4),
                'updated_at'   => now()->subDays(3),
            ],
            [
                'title'        => 'Broadcasting Licence Application Requirements',
                'body'         => 'Organizations wishing to obtain a broadcasting licence must submit: completed application form, proof of Botswana incorporation, programming schedule, and technical specifications of broadcasting equipment.',
                'category'     => 'licensing',
                'tags'         => json_encode(['broadcasting', 'licence', 'requirements']),
                'status'       => 'published',
                'published_at' => now()->subDays(60),
                'created_by'   => $staff->id,
                'created_at'   => now()->subDays(61),
                'updated_at'   => now()->subDays(60),
            ],
            [
                'title'        => 'Draft: Quality of Service Minimum Standards',
                'body'         => 'This draft sets minimum QoS standards that all mobile network operators must maintain. Standards cover voice call quality, data speeds, network availability, and complaint resolution times.',
                'category'     => 'regulations',
                'tags'         => json_encode(['QoS', 'standards', 'mobile', 'draft']),
                'status'       => 'draft',
                'published_at' => null,
                'created_by'   => $staff->id,
                'created_at'   => now()->subDays(2),
                'updated_at'   => now()->subDays(2),
            ],
        ];
 
        DB::table('contents')->insert($contents);
 
        echo "  ✅  CMS content seeded (" . count($contents) . " articles)\n";
    }
}