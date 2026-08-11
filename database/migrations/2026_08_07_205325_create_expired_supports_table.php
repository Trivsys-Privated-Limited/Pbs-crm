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
    Schema::create('expired_supports', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('number');
        $table->string('agent_name')->nullable();
        $table->date('old_expiry_date')->nullable(); // Puraani expiry date record karne ke liye
        $table->string('show_status')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expired_supports');
    }
};