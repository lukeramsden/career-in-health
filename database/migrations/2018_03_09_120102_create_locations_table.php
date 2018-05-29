<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name', 100);
            $table->string('county', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('grid_reference', 20)->nullable();
            $table->integer('easting')->nullable();
            $table->integer('northing')->nullable();
            $table->float('latitude', 16,8)->nullable();
            $table->float('longitude', 16,8)->nullable();
            $table->integer('elevation')->nullable();
            $table->string('postcode_sector', 10)->nullable();
            $table->string('local_government_area', 100)->nullable();
            $table->string('nuts_region', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->timestamps();
        });

        App\Location::loadCsv();
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
