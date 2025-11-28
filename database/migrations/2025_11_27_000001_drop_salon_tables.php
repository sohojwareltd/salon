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
        // Drop foreign keys first
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'salon_id')) {
                    $table->dropForeign(['salon_id']);
                    $table->dropColumn('salon_id');
                }
            });
        }

        if (Schema::hasTable('providers')) {
            Schema::table('providers', function (Blueprint $table) {
                if (Schema::hasColumn('providers', 'salon_id')) {
                    $table->dropForeign(['salon_id']);
                    $table->dropColumn('salon_id');
                }
            });
        }

        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                if (Schema::hasColumn('appointments', 'salon_id')) {
                    $table->dropForeign(['salon_id']);
                    $table->dropColumn('salon_id');
                }
            });
        }

        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (Schema::hasColumn('reviews', 'salon_id')) {
                    $table->dropForeign(['salon_id']);
                    $table->dropColumn('salon_id');
                }
            });
        }

        // Drop salon-related tables
        Schema::dropIfExists('salon_exceptions');
        Schema::dropIfExists('salons');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal for this cleanup migration
    }
};
