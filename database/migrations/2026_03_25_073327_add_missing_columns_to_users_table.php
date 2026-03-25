<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if columns exist before adding to avoid errors
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            
            if (!Schema::hasColumn('users', 'national_id')) {
                $table->string('national_id')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'organization_name')) {
                $table->string('organization_name')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('citizen');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'national_id',
                'organization_name',
                'phone_number',
                'role'
            ]);
        });
    }
};