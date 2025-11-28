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
        Schema::table('appointments', function (Blueprint $table) {
            // Add customer_id as alias for user_id (for clarity)
            $table->unsignedBigInteger('customer_id')->nullable()->after('user_id');
            
            // Add total_amount for multiple services
            $table->decimal('total_amount', 10, 2)->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['customer_id', 'total_amount']);
        });
    }
};
