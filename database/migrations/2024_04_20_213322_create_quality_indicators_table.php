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
        Schema::create('quality_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garment_design_id')->constrained('garment_designs');
            $table->integer('creativity');
            $table->integer('originality');
            $table->integer('texture');
            $table->integer('stylistics');
            $table->integer('functionality');
            $table->integer('feasibility');
            $table->text('feedback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_indicators');
    }
};
