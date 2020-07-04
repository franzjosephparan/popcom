<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_sku')->unique();
            $table->string('item_name')->unique();
            $table->string('item_description');
            $table->enum('category', [
                'male condom',
                'female condom',
                'oral contraception',
                'hormonal ring',
                'uid',
                'injection',
                'surgical steralization',
                'implant',
                'coitus interruption',
                'calendar rhythm method',
                'vaginal douche',
                'contraceptive patch',
                'cap'
            ]);
            $table->string('image')->nullable();
            $table->string('status');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('items');
    }
}
