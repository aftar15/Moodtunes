<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            // Rename cover_image to cover_path
            $table->renameColumn('cover_image', 'cover_path');
            
            // Add mood column
            $table->string('mood')->default('Unspecified');
            
            // Remove duration column as it's not needed
            $table->dropColumn('duration');
        });
    }

    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->renameColumn('cover_path', 'cover_image');
            $table->dropColumn('mood');
            $table->integer('duration')->nullable();
        });
    }
};
