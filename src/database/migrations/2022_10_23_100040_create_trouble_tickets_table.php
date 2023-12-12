<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTroubleTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trouble_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->unsignedBigInteger('last_progress_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('resume_id')->nullable();
            $table->string('nomor_ticket');
            $table->string('priority')->nullable();
            // ['Low', 'Medium', 'Hight']
            $table->string('type')->nullable();
            // ['Assurance', 'Fulfillment', 'Administrasi']
            // $table->string('category')->nullable();
            // ['Corrective', 'Preventive']
            $table->string('problem_type')->nullable();
            // ['Non Gamas', 'Gamas Non Impact', 'Gamas Impact']
            $table->longText('subject')->nullable();
            $table->longText('problem')->nullable();
            $table->tinyInteger('step')->default(1);
            $table->boolean('is_visited')->nullable();
            $table->dateTime('event_datetime')->nullable();
            $table->string('event_location')->nullable();
            $table->decimal('rate')->nullable();
            $table->string('status')->default('Open');
            $table->time('close_estimation')->nullable();
            $table->tinyInteger('resume_gm')->default(0);
            $table->tinyInteger('resume_cto')->default(0);
            $table->timestamp('created_date');
            $table->timestamp('updated_date');
            $table->dateTime('last_updated_date')->nullable();
            $table->dateTime('closed_date')->nullable();
            $table->dateTime('technical_closed_date')->nullable();
            // ['Open', 'Pending', 'Closed']
            // $table->tinyInteger('urgency_level')->default(3);
            // $table->string('source_info_trouble');
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
        Schema::dropIfExists('trouble_tickets');
    }
}
