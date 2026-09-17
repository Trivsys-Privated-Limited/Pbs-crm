<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   /* public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            //
        });
    } */

    /**
     * Reverse the migrations.
     */
  /*  public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            //
        });
    } */


    public function up()
{
    Schema::table('payrolls', function (Blueprint $table) {
        // none = no request, requested = admin ko request gayi, approved = admin ne accept karli
        $table->string('slip_status')->default('none')->after('net_salary');
    });
}

    public function down()
{
    Schema::table('payrolls', function (Blueprint $table) {
        $table->dropColumn('slip_status');
    });
}


};
