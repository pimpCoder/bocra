<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('domain_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('domain_name');
            $table->string('domain_type', 50);
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');
            $table->date('registration_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            
            // Add unique constraint for domain names
            $table->unique('domain_name');
            $table->index('status');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_registrations');
    }
};