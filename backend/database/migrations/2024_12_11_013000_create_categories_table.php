<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('mood_type', 50);
            $table->string('image_url')->nullable();
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('name');
            $table->index('mood_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
