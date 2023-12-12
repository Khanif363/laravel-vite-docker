<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('approvalable_id');
            $table->string('approvalable_type');
            $table->unsignedBigInteger('inputer_id');
            $table->tinyInteger('status_approval')->default(0);
            $table->text('reason_reject')->nullable();
            $table->timestamp('inputed_date');
            $table->timestamp('updated_date');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvals');
    }
}
