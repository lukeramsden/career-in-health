<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name', 100)->nullable();
            $table->string('address_line_1', 60)->nullable();
            $table->string('address_line_2', 60)->nullable();
            $table->string('address_line_3', 60)->nullable();
            $table->integer('town')->nullable();
            $table->string('county', 40)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('disable_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
