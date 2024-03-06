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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            /** 
             * Not got time to store the authenticated user for a booking, manually passing in payload doesn't demonstrate anything.
             * This would have also stored the users age, so we don't just have an inflated cost without explanation.
             */
            // $table->unsignedBigInteger('user_id')->comment('The user that has booked the car.');
            $table->dateTime('from_date')->comment('Stored in UTC.');
            $table->dateTime('to_date')->comment('Stored in UTC.');
            $table->unsignedDecimal('cost')->comment('Base cost of car, plus additional charges.');
            $table->timestamps();

            $table->foreign('car_id')->references('id')->on('cars');
            
            // Use a compound index for the date range as assuming both will be provided.
            $table->index(['from_date', 'to_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
