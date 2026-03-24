<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('license_applications', function (Blueprint $table) {
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->after('reviewed_at');

            $table->text('rejection_reason')
                  ->nullable()
                  ->after('reviewed_by');

            $table->date('validity_start')
                  ->nullable()
                  ->after('rejection_reason');

            $table->date('validity_end')
                  ->nullable()
                  ->after('validity_start');
        });
    }

    public function down(): void
    {
        Schema::table('license_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn(['rejection_reason', 'validity_start', 'validity_end']);
        });
    }
};