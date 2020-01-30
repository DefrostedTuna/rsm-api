<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('place_id')->nullable();
            $table->string('name')->nullable();
            $table->string('locale')->nullable();
            $table->string('state')->nullable();
            $table->string('interstate')->nullable();
            $table->string('exit')->nullable();
            $table->float('lat')->nullable(); // Required for mapping.
            $table->float('lng')->nullable(); // Required for mapping.
            $table->string('type')->nullable();
            $table->string('direction')->nullable();
            $table->string('status')->nullable();
            $table->string('condition')->nullable();
            $table->boolean('potable_water')->nullable();
            $table->boolean('overnight_parking')->nullable();
            $table->integer('parking_duration')->nullable();
            $table->boolean('restrooms')->nullable();
            $table->boolean('family_restroom')->nullable();
            $table->boolean('dump_station')->nullable();
            $table->boolean('pet_area')->nullable();
            $table->boolean('vending')->nullable();
            $table->boolean('security')->nullable();
            $table->boolean('indoor_area')->nullable();
            $table->json('parking_spaces')->nullable();
            $table->json('cell_service')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
