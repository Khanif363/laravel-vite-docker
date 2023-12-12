<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemManageProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_manage_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('problem_manage_id');
            $table->unsignedBigInteger('inputer_id')->nullable();
            $table->string('progress_type');
            // ['Analysis', 'Result']
            $table->longText('information')->nullable();
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
        Schema::dropIfExists('problem_manage_progress');
    }
}
