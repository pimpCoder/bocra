<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domain_registrations', function (Blueprint $table) {
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->after('expiry_date');

            $table->text('rejection_reason')
                  ->nullable()
                  ->after('reviewed_by');

            $table->timestamp('reviewed_at')
                  ->nullable()
                  ->after('rejection_reason');
        });
    }

    public function down(): void
    {
        Schema::table('domain_registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn(['rejection_reason', 'reviewed_at']);
        });
    }
};