<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Update status enum to include in_progress
            $table->dropColumn('status');
        });
        
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'])
                ->default('pending')
                ->after('end_time');
        });
        
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('status');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->boolean('review_requested')->default(false)->after('paid_at');
            $table->boolean('review_submitted')->default(false)->after('review_requested');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['completed_at', 'paid_at', 'review_requested', 'review_submitted']);
        });
    }
};
