<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdChangesEngineer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('engineers', function (Blueprint $table) {
            $table->unsignedBigInteger('engineer_assignment_id')->nullable()->change();
            $table->unsignedBigInteger('engineer_assignment_changes_id')->nullable()->after('engineer_assignment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('engineers', function (Blueprint $table) {
            $table->unsignedBigInteger('engineer_assignment_id')->change();
            $table->dropColumn('engineer_assignment_changes_id');
        });
    }
}
