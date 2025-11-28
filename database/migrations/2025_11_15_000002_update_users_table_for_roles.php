<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop old role column if exists
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            
            $table->string('phone')->nullable()->after('email');
            $table->foreignId('role_id')->after('phone')->nullable()->constrained('roles')->onDelete('set null');
            $table->foreignId('provider_id')->after('role_id')->nullable()->constrained('providers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['provider_id']);
            $table->dropColumn(['phone', 'role_id', 'provider_id']);
        });
    }
};
