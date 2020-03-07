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
            $table->string('type');
            $table->string('google_place_id')->nullable();
            $table->string('name')->nullable();
            $table->string('locale')->nullable();
            $table->string('state')->nullable();
            $table->string('interstate')->nullable();
            $table->string('exit')->nullable();
            $table->decimal('lat', 10, 8); // Required for mapping.
            $table->decimal('lng', 11, 8); // Required for mapping.
            $table->string('direction')->nullable();
            $table->string('status')->nullable();
            $table->string('condition')->nullable();
            $table->json('amenities')->nullable();
            $table->integer('parking_duration')->nullable();
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
