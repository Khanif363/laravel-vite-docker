<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEditorTicketProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trouble_ticket_progress', function (Blueprint $table) {
            $table->unsignedBigInteger('editor_id')->nullable()->after('inputer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trouble_ticket_progress', function (Blueprint $table) {
            $table->dropColumn('editor_id');
        });
    }
}
