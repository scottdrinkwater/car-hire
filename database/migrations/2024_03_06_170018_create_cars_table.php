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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->unsignedDecimal('cost');
            $table->string('registration');
            // Given more time would have added a link to hire locations.

            $table->timestamps();
            
            // Soft deletes to maintain referential integrity.
            $table->softDeletes();

            // Two likely columns we will be searching on.
            $table->index('cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
