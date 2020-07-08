<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryLedger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_ledgers', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_inventory_id');
            $table->integer('item_id');
            $table->integer('inventory_transfer_id')->nullable();
            $table->integer('facility_id');
            $table->string('message')->nullable();
            $table->string('quantity');
            $table->enum('uom', ['pcs']);
            $table->enum('transaction_type', ['transfer', 'dispense', 'request', 'adjustment']);
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('inventory_ledgers');
    }
}
