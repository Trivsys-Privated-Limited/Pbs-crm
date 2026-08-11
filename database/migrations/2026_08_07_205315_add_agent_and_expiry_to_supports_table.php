<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('supports', function (Blueprint $table) {
        $table->string('agent_name')->nullable();
        $table->date('expiry_date')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supports', function (Blueprint $table) {
            //
        });
    }
};