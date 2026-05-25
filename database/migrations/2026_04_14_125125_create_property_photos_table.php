<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('property_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('photo_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index('property_id');
            $table->index('is_primary');
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_photos');
    }
};