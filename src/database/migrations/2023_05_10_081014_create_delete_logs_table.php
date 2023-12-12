<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeleteLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delete_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deletable_id');
            $table->string('deletable_type');
            $table->unsignedBigInteger('deleter_id');
            $table->longText('reason');
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
        Schema::dropIfExists('delete_logs');
    }
}
