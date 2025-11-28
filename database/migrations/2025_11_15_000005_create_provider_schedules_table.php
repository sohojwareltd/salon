<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
            $table->tinyInteger('weekday')->comment('0=Sunday, 1=Monday, ..., 6=Saturday');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_off')->default(false)->comment('Is provider off on this day');
            $table->timestamps();
            
            $table->unique(['provider_id', 'weekday']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_schedules');
    }
};
