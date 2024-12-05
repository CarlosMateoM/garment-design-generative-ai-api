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
        Schema::create('garment_designs_images', function (Blueprint $table) {
            //$table->id();
            
            $table->foreignId('garment_design_id')
            ->constrained('garment_designs')
            ->onDelete('cascade');

            $table->foreignId('image_id')
            ->constrained('images')
            ->onDelete('cascade');

            $table->timestamps();

            $table->primary(['garment_design_id', 'image_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garment_designs_images');
    }
};
