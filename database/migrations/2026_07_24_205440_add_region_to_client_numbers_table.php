<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('client_numbers', function (Blueprint $table) {
            // Number column ke baad region column add kar rahy hain
            $table->string('region')->nullable()->after('number');
        });
    }

    public function down()
    {
        Schema::table('client_numbers', function (Blueprint $table) {
            $table->dropColumn('region');
        });
    }
};

