<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_wallet_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->decimal('service_amount', 10, 2)->default(0);
            $table->decimal('salon_amount', 10, 2)->default(0);
            $table->decimal('provider_amount', 10, 2)->default(0);
            $table->decimal('tips_amount', 10, 2)->default(0);
            $table->decimal('total_provider_amount', 10, 2)->default(0)->comment('provider_amount + tips');
            $table->enum('type', ['earning', 'withdrawal', 'adjustment'])->default('earning');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['provider_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_wallet_entries');
    }
};
