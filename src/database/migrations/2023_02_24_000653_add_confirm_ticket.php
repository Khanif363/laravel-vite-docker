<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfirmTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trouble_tickets', function (Blueprint $table) {
            $table->tinyInteger('confirmation_count')->default(0)->after('resume_cto');
            $table->dateTime('last_confirmation_date')->nullable()->after('confirmation_count');
            $table->boolean('notif_rate')->default(false)->after('last_confirmation_date');
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
            $table->dropColumn('confirmation_count');
            $table->dropColumn('last_confirmation_date');
            $table->dropColumn('notif_rate');
        });
    }
}
