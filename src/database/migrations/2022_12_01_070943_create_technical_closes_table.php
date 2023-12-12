<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnicalClosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technical_closes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trouble_ticket_progress_id');
            $table->dateTime('close_datetime');
            $table->longText('evaluation')->nullable();
            $table->longText('rfo')->nullable();
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
        Schema::dropIfExists('technical_closes');
    }
}
