<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->integer('bomID');
            $table->integer('partID');
            $table->string('semi_part_bom_version')->nullable();
            $table->integer('quantity');
            $table->double('loss_rate');
            $table->string('parentID');
            $table->integer('bom_version');
            $table->timestamps();
            $table->foreign('parentID')->references('partID')->on('boms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts');
    }
}
