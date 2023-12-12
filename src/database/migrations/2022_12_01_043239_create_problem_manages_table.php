<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_manages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->unsignedBigInteger('last_progress_id')->nullable();
            $table->string('nomor_ticket')->nullable();
            $table->string('nomor_problem');
            $table->string('problem_type');
            $table->longText('content')->nullable();
            // ['Reaktive', 'Proaktive']
            $table->boolean('is_verified')->default(false)->nullable();
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
        Schema::dropIfExists('problem_manages');
    }
}
