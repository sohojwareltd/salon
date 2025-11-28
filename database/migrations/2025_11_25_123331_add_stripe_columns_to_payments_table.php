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
        Schema::table('payments', function (Blueprint $table) {
            // Add amount column if it doesn't exist
            if (!Schema::hasColumn('payments', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('appointment_id');
            }
            
            // Add stripe_checkout_session_id if it doesn't exist
            if (!Schema::hasColumn('payments', 'stripe_checkout_session_id')) {
                $table->string('stripe_checkout_session_id')->nullable()->after('total_amount');
            }
            
            // Add currency if it doesn't exist
            if (!Schema::hasColumn('payments', 'currency')) {
                $table->string('currency', 3)->default('usd')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['amount', 'stripe_checkout_session_id', 'currency']);
        });
    }
};
