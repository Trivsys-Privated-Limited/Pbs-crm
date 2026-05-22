<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('helps', function (Blueprint $table) {
            $table->id();
            $table->string('c_name');
            $table->string('c_number');
            $table->string('c_email')->default('No Email');
            $table->string('make_address');
            $table->longText('help_reason');
            $table->string('user_id');
            $table->string('user_name');
            $table->enum('status',['pending','resolve','refund','working'])->default('pending');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helps');
    }
};
