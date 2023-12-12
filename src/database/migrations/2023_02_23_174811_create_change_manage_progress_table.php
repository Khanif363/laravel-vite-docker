<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeManageProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_manage_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('change_manage_id')->nullable();
            $table->unsignedBigInteger('inputer_id')->nullable();
            $table->longText('information')->nullable();
            $table->string('progress_type');
            $table->timestamp('inputed_date');
            $table->timestamp('updated_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('change_manage_progress');
    }
}
