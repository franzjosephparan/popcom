<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('receiving_facility_id');
            $table->integer('supplying_facility_id');
            $table->string('message');
            $table->enum('status', ['pending', 'accepted', 'declined', 'in transit']);
            $table->integer('status_by')->nullable();
            $table->timestamp('status_at')->nullable();
            $table->integer('created_by');
            $table->timestamp('updated_by')->nullable();
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
        Schema::dropIfExists('inventory_requests');
    }
}
