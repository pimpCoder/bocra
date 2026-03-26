<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // Only add if they don't already exist
            if (!Schema::hasColumn('complaints', 'name')) {
                $table->string('name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('complaints', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('complaints', 'phone_number')) {
                $table->string('phone_number', 20)->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'phone_number']);
        });
    }
};