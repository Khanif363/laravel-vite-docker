<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTroubleTicketProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trouble_ticket_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trouble_ticket_id');
            $table->unsignedBigInteger('inputer_id')->nullable();
            $table->string('update_type');
            // ['Diagnose', 'Pending, 'Engineer Assignment', 'Dispatch', 'Engineer Troubleshoot', 'Technical Close', 'Monitoring', 'Closed']
            $table->longText('content')->nullable();
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
        Schema::dropIfExists('trouble_ticket_progress');
    }
}
