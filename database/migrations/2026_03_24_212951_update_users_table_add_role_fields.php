<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Update role ENUM to include all four roles
            $table->enum('role', ['citizen', 'business', 'staff', 'admin'])
                  ->default('citizen')
                  ->change();

            // Extra profile fields
            $table->string('phone_number', 20)->nullable()->after('role');
            $table->string('national_id/company_no', 50)->nullable()->after('phone_number');
            $table->string('organization_name')->nullable()->after('national_id');
            $table->boolean('is_active')->default(true)->after('organization_name');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'national_id',
                'organization_name',
                'is_active',
                'last_login_at',
            ]);
        });
    }
};