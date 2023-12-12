<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_manages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->unsignedBigInteger('last_progress_id')->nullable();
            $table->unsignedBigInteger('ticket_reference_id')->nullable();
            $table->unsignedBigInteger('approval_level1_id')->nullable();
            $table->unsignedBigInteger('approval_level2_id')->nullable();
            $table->string('nomor_changes')->nullable();
            $table->string('reference')->nullable();
            $table->string('title')->nullable();
            $table->string('status')->nullable();
            $table->json('type')->nullable();
            $table->string('priority')->nullable();
            $table->dateTime('datetime_action')->nullable();
            $table->string('pic_telkomsat')->nullable();
            $table->string('pic_nontelkomsat')->nullable();
            $table->longText('agenda')->nullable();
            $table->longText('content')->nullable();
            $table->tinyInteger('step')->default(1);
            // $table->tinyInteger('status_approval1')->nullable();
            $table->tinyInteger('status_approval1')->default(0);
            $table->tinyInteger('status_approval2')->default(0);
            $table->text('reason_reject1')->nullable();
            $table->text('reason_reject2')->nullable();
            $table->dateTime('last_updated_date')->nullable();
            $table->dateTime('closed_date')->nullable();
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
        Schema::dropIfExists('change_manages');
    }
}
