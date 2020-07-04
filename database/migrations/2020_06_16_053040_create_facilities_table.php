<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('facility_name');
            $table->string('address');
            $table->string('region');
            $table->string('province');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->enum('facility_type', ['RHU', 'MHC', 'CHO']);
            $table->boolean('status');
            $table->tinyInteger('created_by');
            $table->tinyInteger('updated_by')->nullable();
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
        Schema::dropIfExists('facilities');
    }
}
