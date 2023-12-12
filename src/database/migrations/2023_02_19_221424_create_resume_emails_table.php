<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumeEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resume_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trouble_ticket_id')->nullable();
            $table->unsignedBigInteger('inputer_id')->nullable();
            $table->longText('content')->nullable();
            $table->timestamp('created_date');
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
        Schema::dropIfExists('resume_emails');
    }
}
