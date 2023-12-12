<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChangeManageProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('change_manage_progress', function (Blueprint $table) {
            $table->string('edited')->nullable()->after('information');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('change_manage_progress', function (Blueprint $table) {
            $table->dropColumn('edited');
        });
    }
}
