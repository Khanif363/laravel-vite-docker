<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangesIdTicketInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('change_manage_id')->nullable()->after('trouble_ticket_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_infos', function (Blueprint $table) {
            $table->dropColumn('change_manage_id');
        });
    }
}
