<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->unsignedBigInteger('inputer_id');
            $table->unsignedBigInteger('trouble_ticket_id')->nullable();
            $table->unsignedBigInteger('trouble_ticket_progress_id')->nullable();
            $table->unsignedBigInteger('profile_id')->nullable();
            $table->unsignedBigInteger('problem_manage_id')->nullable();
            $table->unsignedBigInteger('problem_manage_progress_id')->nullable();
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
        Schema::dropIfExists('attachments');
    }
}
