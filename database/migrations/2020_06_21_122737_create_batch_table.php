<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('batch_name');
            $table->integer('facility_id');
            $table->integer('item_id');
            $table->integer('quantity');
            $table->enum('uom', [
                'pcs'
            ]);
            $table->timestamp('expiration_date')->nullable();
            $table->boolean('status');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('batch');
    }
}
