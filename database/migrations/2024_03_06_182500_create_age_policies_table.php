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
        Schema::create('age_policies', function (Blueprint $table) {
            $table->id();
            $table->integer('age_from')->unsigned();
            $table->integer('age_to')->unsigned();
            // Assuming it will always be additional to a base cost.
            $table->decimal('additional_cost', 8, 2, true)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('age_policies');
    }
};
