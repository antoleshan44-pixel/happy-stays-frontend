<?php
// File: database/migrations/2024_01_01_000004_create_price_alerts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->decimal('target_price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'property_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_alerts');
    }
};