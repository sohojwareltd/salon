<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained('users')->onDelete('cascade');
            $table->decimal('commission_percentage', 5, 2)->default(70.00)->after('is_active')->comment('Provider commission %');
            $table->decimal('wallet_balance', 10, 2)->default(0.00)->after('commission_percentage');
        });
    }

    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'commission_percentage', 'wallet_balance']);
        });
    }
};
