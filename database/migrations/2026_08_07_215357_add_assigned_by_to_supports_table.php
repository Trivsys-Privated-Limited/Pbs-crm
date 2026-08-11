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
        $table->string('assigned_by_name')->nullable(); // Jisne assign kiya (e.g., John)
        $table->string('assigned_by_role')->nullable(); // Uska role (e.g., sales coordinator)
        $table->date('assigned_date')->nullable();      // Jis din assign kiya
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

