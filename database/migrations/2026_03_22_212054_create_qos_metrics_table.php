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
        Schema::create('qos_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name');
            $table->string('metric_type', 100);
            $table->float('value');
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
            
            // Add indexes for analytics queries
            $table->index('provider_name');
            $table->index('metric_type');
            $table->index('recorded_at');
            
            // Composite index for common query patterns
            $table->index(['provider_name', 'metric_type', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qos_metrics');
    }
};