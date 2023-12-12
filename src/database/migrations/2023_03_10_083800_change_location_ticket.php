<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLocationTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trouble_tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('event_location_id')->nullable()->after('event_datetime');
            $table->dropColumn('event_location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trouble_tickets', function (Blueprint $table) {
            $table->dropColumn('event_location_id');
            $table->string('event_location')->nullable()->after('event_datetime');
        });
    }
}
