<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChangeManages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('change_manages', function (Blueprint $table) {
            $table->string('type')->change();
            $table->unsignedBigInteger('engineer_id')->nullable()->after('ticket_reference_id');
            $table->boolean('is_draft')->default(false)->after('content');
            $table->dateTime('submited_date')->nullable()->after('is_draft');
            $table->boolean('email_to_level0')->default(false)->nullable()->after('submited_date');
            $table->boolean('email_to_level1')->default(false)->nullable()->after('email_to_level0');
            $table->boolean('email_to_level2')->default(false)->nullable()->after('email_to_level1');
            $table->dropColumn('status_approval1');
            $table->dropColumn('status_approval2');
            $table->dropColumn('reason_reject1');
            $table->dropColumn('reason_reject2');
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
            $table->json('type')->change();
            $table->dropColumn('engineer_id');
            $table->dropColumn('is_draft');
            $table->dropColumn('submited_date');
            $table->dropColumn('email_to_level0');
            $table->dropColumn('email_to_level1');
            $table->dropColumn('email_to_level2');
            $table->tinyInteger('status_approval1')->default(0);
            $table->tinyInteger('status_approval2')->default(0);
            $table->text('reason_reject1')->nullable();
            $table->text('reason_reject2')->nullable();
        });
    }
}
