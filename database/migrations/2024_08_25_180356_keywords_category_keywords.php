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
        Schema::create('keywords_categories_keywords', function (Blueprint $table) {

            $table->foreignId('keyword_id')->constrained()->onDelete('cascade');
            $table->foreignId('keywords_category_id')->constrained()->onDelete('cascade');


            $table->primary(['keyword_id', 'keywords_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
