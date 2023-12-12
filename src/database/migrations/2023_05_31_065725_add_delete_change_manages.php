<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteChangeManages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('change_manages', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false)->after('is_draft');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('change_manages', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
    }
}
