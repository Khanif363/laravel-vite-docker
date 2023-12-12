<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trouble_ticket_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('source_info');
            $table->string('detail_info');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('number_phone')->nullable();
            $table->string('nik')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_infos');
    }
}
