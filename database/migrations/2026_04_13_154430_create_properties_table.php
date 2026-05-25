<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('location');
            $table->text('description')->nullable();
            $table->enum('property_type', ['Villa', 'Apartment', 'Cottage', 'House'])->default('House');
            $table->integer('bedrooms')->default(1);
            $table->integer('bathrooms')->default(1);
            $table->decimal('price_per_night', 10, 2);
            $table->text('amenities')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('admin_status', ['active', 'suspended', 'archived'])->default('active');
            $table->json('archived_data')->nullable();
            $table->timestamps();
            
            $table->index('owner_id');
            $table->index('status');
            $table->index('admin_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};